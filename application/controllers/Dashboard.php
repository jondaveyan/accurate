<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	function __construct() {
		parent::__construct();
		$this->load->database();
	}

	public function index()
	{
		$query = $this->db->get('products');
		$products = $query->result();
		$this->db->where('orders.daily_sale', 'daily');
		$this->db->select('clients.name as client_name,clients.id as client_id,products.id as product_id, products.name as product_name, orders.product_quantity');
		$this->db->from('orders');
		$this->db->join('products', 'products.id = orders.product_id');
		$this->db->join('clients', 'clients.id = orders.client_id');
		$query = $this->db->get();
		$data = $query->result();
		$clients = array();
		$client_ids = array();
		foreach($data as $key => $val)
		{
			if(!in_array($val->client_name, $clients))
			{
				$clients[] = $val->client_name;
				$client_ids[] = $val->client_id;
			}
		}
		$res = array();
        $giveback_quantity = array();
		foreach($data as $key => $value)
		{
            $this->db->where('product_id', $value->product_id);
            $this->db->where('client_id', $value->client_id);
            $query = $this->db->get('giveback');
            $givebacks = $query->result();
            $giveback_q = 0;
            foreach($givebacks as $giveback)
            {
                 $giveback_q += $giveback->quantity;
            }
            $giveback_quantity[$value->product_name][$value->client_name] = $giveback_q;
			if(isset($res[$value->product_name][$value->client_name]))
			{
				$res[$value->product_name][$value->client_name] += intval($value->product_quantity);
			}
			else
			{
				$res[$value->product_name][$value->client_name] = intval($value->product_quantity);
			}
		}
        foreach($res as $key => $value)
        {
            foreach($value as $k => $v)
            {
                $res[$key][$k] -= $giveback_quantity[$key][$k];
            }
        }
		$data = array('res' => $res, 'clients' => $clients, 'products' => $products, 'client_ids' => $client_ids);
		$this->load->view('dashboard', $data);
	}

    public function get_client_info($client_id)
    {
        $this->db->where('id', $client_id);
        $query = $this->db->get('clients');
        $client = $query->result()[0];
        if($client->own == "yes")
        {
			$this->db->where('client_id', $client_id);
			$this->db->from('orders');
			$this->db->join('products', 'products.id = orders.product_id');
			$orders = $this->db->get()->result();
			$html = '<div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Own Client</h4></div>';
			$html .= '<div class="modal-body"><div class="col-md-12"><h3>Orders</h3>';
			$html .= '<table class="table"><th>Product</th><th>Quantity</th><th>Date</th>';
			foreach($orders as $order)
			{
				$html .= '<tr><td>'.$order->name.'</td><td>'.$order->product_quantity.'</td><td>'.$order->date.'</td><tr>';
			}
			$html .= '</table></div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>';

            echo json_encode(array('html' => $html));
        }
        else
        {
            $this->db->select('debt');
            $this->db->where('id', $client_id);
            $this->db->from('clients');
            $debt = $this->db->get()->result();
            $debt = $debt[0]->debt;
            $this->db->where('client_id', $client_id);
			$this->db->from('orders');
			$this->db->join('products', 'products.id = orders.product_id');
            $orders = $this->db->get()->result();
            $order_debt = 0;
            foreach($orders as $order)
            {
                if($order->daily_sale == "daily")
                {
					$now = time(); // or your date as well
					$your_date = strtotime($order->date);
					$datediff = $now - $your_date;

					$day = floor($datediff / (60 * 60 * 24));
                    $order_debt += intval($order->product_quantity) * intval($order->daily_price) * $day;
                }
                else
                {
                    $order_debt += intval($order->product_quantity) * intval($order->sale_price);
                }
            }
            $this->db->where('client_id', $client_id);
            $givebacks = $this->db->get('giveback')->result();
            $giveback_amount = 0;
            foreach($givebacks as $giveback)
            {
                $this->db->where('client_id', $client_id);
                $this->db->where('product_id', $giveback->product_id);
                $query = $this->db->get('orders');
                $product_price = $query->result();
                $product_price = $product_price[0]->daily_price;
				$now = time(); // or your date as well
				$your_date = strtotime($giveback->date);
				$datediff = $now - $your_date;

				$day = floor($datediff / (60 * 60 * 24));
                $giveback_amount += intval($giveback->quantity) * intval($product_price) * $day;
            }
            $this->db->where('client_id', $client_id);
            $query = $this->db->get('payment');
            $payments = $query->result();
            $paid = 0;
            foreach($payments as $payment)
            {
                $paid += $payment->amount;
            }

            $final_debt = $debt + $order_debt - $giveback_amount - $paid;

			$html = '<div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Debt: '.$final_debt.' dram</h4></div>';
			$html .= '<div class="modal-body"><div class="col-md-6"><h3>Payments</h3>';
			$html .= '<table class="table"><th>Amount</th><th>Date</th>';
			foreach($payments as $payment)
			{
				$html .= '<tr><td>'.$payment->amount.'</td><td>'.$payment->date.'</td></tr>';
			}
			$html .= '</table></div><div class="col-md-6"><h3>Orders</h3>';
			$html .= '<table class="table"><th>Product</th><th>Quantity</th><th>Price</th><th>Date</th>';
			foreach($orders as $order)
			{
				if($order->daily_sale == 'daily')
				{
					$price = $order->daily_price;
				}
				else
				{
					$price = $order->sale_price;
				}
				$html .= '<tr><td>'.$order->name.'</td><td>'.$order->product_quantity.'</td><td>'.$price.'</td><td>'.$order->date.'</td></tr>';
			}
			$html .= '</table></div></div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>';
            echo json_encode(array('html' => $html));
        }
    }

    public function get_product_client_info()
    {
        $client_id =  $this->uri->segment(3);
        $product_id =  $this->uri->segment(4);

        $this->db->where('client_id', $client_id);
        $this->db->where('product_id', $product_id);
        $orders = $this->db->get('orders')->result();

        $this->db->where('client_id', $client_id);
        $this->db->where('product_id', $product_id);
        $givebacks = $this->db->get('giveback')->result();

		$html = '<div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Product orders</h4></div>';
		$html .= '<div class="modal-body"><div class="col-md-6"><h3>Give Back</h3>';
		$html .= '<table class="table"><th>Quantity</th><th>Date</th>';
		foreach($givebacks as $giveback)
		{
			$html .= '<tr><td>'.$giveback->quantity.'</td><td>'.$giveback->date.'</td></tr>';
		}
		$html .= '</table></div><div class="col-md-6"><h3>Orders</h3>';
		$html .= '<table class="table"><th>Quantity</th><th>Price</th><th>Date</th>';
		foreach($orders as $order)
		{
			if($order->daily_sale == 'daily')
			{
				$price = $order->daily_price;
			}
			else
			{
				$price = $order->sale_price;
			}
			$html .= '<tr><td>'.$order->product_quantity.'</td><td>'.$price.'</td><td>'.$order->date.'</td></tr>';
		}
		$html .= '</table></div></div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>';

        echo json_encode(array('orders' => $orders, 'givebacks' => $givebacks, 'html' => $html));
    }
}
