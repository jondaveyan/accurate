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
		$this->db->order_by("name", "esc");
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
				if($res[$key][$k] == 0)
				{
					unset($res[$key][$k]);
				}
            }
        }
		foreach($clients as $key => $client)
		{
			$check = true;
			foreach($res as $element)
			{
				if(array_key_exists($client, $element))
				{
					$check = false;
				}
			}
			if($check)
			{
				unset($clients[$key]);
			}
		}
		$this->db->select('name, id');
		$this->db->where('debt !=', 0);
		$query = $this->db->get('clients');
		$clients_with_debt = $query->result();
		foreach($clients_with_debt as $key => $value)
		{
			if(in_array($value->name, $clients))
			{
				unset($clients_with_debt[$key]);
			}
		}
		$data = array('res' => $res, 'clients' => $clients, 'products' => $products, 'client_ids' => $client_ids, 'clients_with_debt' => $clients_with_debt);
		$this->load->view('dashboard', $data);
	}

    public function get_client_info()
    {
		$client_id = $this->input->get('client_id');
		$date = NULL;
		if($this->input->get('date'))
		{
			$date = $this->input->get('date');
		}
        $this->db->where('id', $client_id);
        $query = $this->db->get('clients');
        $client = $query->result()[0];
        if($client->own == "yes")
        {
			$this->db->select('orders.id, products.name, orders.product_id, orders.product_quantity, orders.date');
			$this->db->where('client_id', $client_id);
			$this->db->from('orders');
			$this->db->join('products', 'products.id = orders.product_id');
			$orders = $this->db->get()->result();
			$html = '<div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Օբյեկտ</h4></div>';
			$html .= '<div class="modal-body"><div class="col-md-12"><h3>Գործարքներ</h3>';
			$html .= '<table class="table"><th>Product</th><th>Quantity</th><th>Ամսաթիվ</th><th>Գործողություններ</th>';
			foreach($orders as $order)
			{
				$html .= '<tr data-id="'.$order->id.'" data-product_id="'.$order->product_id.'"><td>'.$order->name.'</td><td>'.$order->product_quantity.'</td><td>'.$order->date.'</td><td><button class="btnEditOwn btn-default btn-xs">Փոփոխել</button><button class="btnDeleteOwn btn-danger btn-xs">Ջնջել</button></td><tr>';
			}
			$html .= '</table></div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Փակել</button></div>';

            echo json_encode(array('html' => $html));
        }
        else
        {
            $this->db->select('debt');
            $this->db->where('id', $client_id);
            $this->db->from('clients');
            $debt = $this->db->get()->result();
            $debt = $debt[0]->debt;
            $this->db->select('orders.id, products.id as product_id, orders.daily_sale, orders.date, orders.product_quantity, orders.daily_price, orders.sale_price, products.name');
            $this->db->where('client_id', $client_id);
			$this->db->from('orders');
			$this->db->join('products', 'products.id = orders.product_id');
            $orders = $this->db->get()->result();
            $order_debt = 0;
            foreach($orders as $order)
            {
                if($order->daily_sale == "daily")
                {
					$now = time();
					if($date)
					{
						$now = strtotime($date);
					}
					$your_date = strtotime($order->date);
					$datediff = $now - $your_date;

					$day = floor($datediff / (60 * 60 * 24));
                    $order_debt += intval($order->product_quantity) * intval($order->daily_price) * $day;
                }
                else
                {
					if($date)
					{
						if(strtotime($order->date) > strtotime($date))
						{
							$order_debt += intval($order->product_quantity) * intval($order->sale_price);
						}
					}
					else
					{
						$order_debt += intval($order->product_quantity) * intval($order->sale_price);
					}
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
				if($date)
				{
					$now = strtotime($date);
				}
				$your_date = strtotime($giveback->date);
				$datediff = $now - $your_date;

				$day = floor($datediff / (60 * 60 * 24));
				if($date)
				{
					if(strtotime($giveback->date) > strtotime($date))
					{
						$giveback_amount += intval($giveback->quantity) * intval($product_price) * $day;
					}
				}
				else
				{
					$giveback_amount += intval($giveback->quantity) * intval($product_price) * $day;
				}
            }
            $this->db->where('client_id', $client_id);
            $query = $this->db->get('payment');
            $payments = $query->result();
            $paid = 0;
            foreach($payments as $payment)
            {
				if($date)
				{
					if(strtotime($payment->date) > strtotime($date))
					{
						$paid += $payment->amount;
					}
				}
				else
				{
					$paid += $payment->amount;
				}
            }

            $final_debt = $debt + $order_debt - $giveback_amount - $paid;
			if($date)
			{
				echo $final_debt; die();
			}
            $datepicker_value = date('d/m/Y');
			$html = '<div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title" id="client_debt">Պարտք: '.$final_debt.' դրամ</h4>
			<div class="input-group date" style="width: 200px;" data-provide="datepicker">
                <input type="text" data-client_id="'.$client_id.'" value="'.$datepicker_value.'" id="debt_date" class="form-control datepicker" name="debt_date">
                <div class="input-group-addon">
                    <span class="glyphicon glyphicon-th"></span>
                </div>
            </div></div>';
			$html .= '<div class="modal-body"><div class="col-md-3"><h3>Վճարումներ</h3>';
			$html .= '<table class="table"><th>Գին</th><th>Ամսաթիվ</th>';
			foreach($payments as $payment)
			{
				$html .= '<tr><td>'.$payment->amount.'</td><td>'.$payment->date.'</td></tr>';
			}
			$html .= '</table></div><div class="col-md-9"><h3>Գործարքներ</h3>';
			$html .= '<table class="table"><th>Ապրանք</th><th>Քանակ</th><th>Գին</th><th>Ամսաթիվ</th><th>Գործարքի տեսակ</th><th>Գործողություններ</th>';
			foreach($orders as $order)
			{
				if($order->daily_sale == 'daily')
				{
					$daily_sale = '<td>Օրավարձ</td>';
				}
				else
				{
					$daily_sale = '<td>Վաճառք</td>';
				}
				if($order->daily_sale == 'daily')
				{
					$price = $order->daily_price;
				}
				else
				{
					$price = $order->sale_price;
				}
				$html .= '<tr data-id="'.$order->id.'" data-product_id="'.$order->product_id.'"><td>'.$order->name.'</td><td>'.$order->product_quantity.'</td><td>'.$price.'</td><td>'.$order->date.'</td>'.$daily_sale.'<td><button class="btnEdit btn-default btn-xs">Փոփոխել</button><button class="btnDelete btn-danger btn-xs">Ջնջել</button></td></tr>';
			}
			$html .= '</table></div></div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Փակել</button></div>';
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

		$html = '<div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Ապրանքի գործարքներ</h4></div>';
		$html .= '<div class="modal-body"><div class="col-md-6"><h3>Ապրանքի վերադարձ</h3>';
		$html .= '<table class="table"><th>Քանակ</th><th>Ամսաթիվ</th>';
		foreach($givebacks as $giveback)
		{
			$html .= '<tr><td>'.$giveback->quantity.'</td><td>'.$giveback->date.'</td></tr>';
		}
		$html .= '</table></div><div class="col-md-6"><h3>Գործարքներ</h3>';
		$html .= '<table class="table"><th>Քանակ</th><th>Գին</th><th>Ամսաթիվ</th><th>Գործարքի տեսակ</th>';
		foreach($orders as $order)
		{
			if($order->daily_sale == 'daily')
			{
				$daily_sale = '<td>Օրավարձ</td>';
			}
			else
			{
				$daily_sale = '<td>Վաճառք</td>';
			}
			if($order->daily_sale == 'daily')
			{
				$price = $order->daily_price;
			}
			else
			{
				$price = $order->sale_price;
			}
			$html .= '<tr><td>'.$order->product_quantity.'</td><td>'.$price.'</td><td>'.$order->date.'</td>'.$daily_sale.'</tr>';
		}
		$html .= '</table></div></div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Փակել</button></div>';

        echo json_encode(array('orders' => $orders, 'givebacks' => $givebacks, 'html' => $html));
    }

	public function get_product_info($product_id)
	{
		$this->db->where('product_id', $product_id);
		$orders = $this->db->get('orders')->result();

		$this->db->where('product_id', $product_id);
		$givebacks = $this->db->get('giveback')->result();

		$html = '<div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Ապրանքի գործարքներ</h4></div>';
		$html .= '<div class="modal-body"><div class="col-md-4"><h3>Ապրանքի վերադարձ</h3>';
		$html .= '<table class="table"><th>Քանակ</th><th>Ամսաթիվ</th>';
		foreach($givebacks as $giveback)
		{
			$html .= '<tr><td>'.$giveback->quantity.'</td><td>'.$giveback->date.'</td></tr>';
		}
		$sales = array();
		$dailies = array();
		foreach($orders as $value)
		{
			if($value->daily_sale == 'daily')
			{
				$dailies[] = $value;
			}
			else
			{
				$sales[] = $value;
			}
		}

		$html .= '</table></div><div class="col-md-4"><h3>Օրավարձ</h3>';
		$html .= '<table class="table"><th>Կլիենտ</th><th>Քանակ</th><th>Գին</th><th>Ամսաթիվ</th>';
		foreach($dailies as $order)
		{
			$this->db->where('id', $order->client_id);
			$this->db->select('name');
			$query = $this->db->get('clients');
			$client_name = $query->result()[0]->name;
			if($order->daily_sale == 'daily')
			{
				$price = $order->daily_price;
			}
			else
			{
				$price = $order->sale_price;
			}
			$html .= '<tr><td>'.$client_name.'</td><td>'.$order->product_quantity.'</td><td>'.$price.'</td><td>'.$order->date.'</td></tr>';
		}
		$html .= '</table>';

		$html .= '</table></div><div class="col-md-4"><h3>Վաճառք</h3>';
		$html .= '<table class="table"><th>Կլիենտ</th><th>Քանակ</th><th>Գին</th><th>Ամսաթիվ</th>';
		foreach($sales as $order)
		{
			$this->db->where('id', $order->client_id);
			$this->db->select('name');
			$query = $this->db->get('clients');
			$client_name = $query->result()[0]->name;
			if($order->daily_sale == 'daily')
			{
				$price = $order->daily_price;
			}
			else
			{
				$price = $order->sale_price;
			}
			$html .= '<tr><td>'.$client_name.'</td><td>'.$order->product_quantity.'</td><td>'.$price.'</td><td>'.$order->date.'</td></tr>';
		}
		$html .= '</table></div></div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Փակել</button></div>';

		echo json_encode(array('orders' => $orders, 'givebacks' => $givebacks, 'html' => $html));
	}

    public function get_debts()
    {
        $date = NULL;
        if($this->input->get('date'))
        {
            $date = $this->input->get('date');
        }
        $query = $this->db->get('clients');
        $clients = $query->result();
        $result_array = array();
        foreach($clients as $client)
        {
            $client_id = $client->id;
            $this->db->select('debt');
            $this->db->where('id', $client_id);
            $this->db->from('clients');
            $debt = $this->db->get()->result();
            $debt = $debt[0]->debt;
            $this->db->select('orders.id, products.id as product_id, orders.daily_sale, orders.date, orders.product_quantity, orders.daily_price, orders.sale_price, products.name');
            $this->db->where('client_id', $client_id);
            $this->db->from('orders');
            $this->db->join('products', 'products.id = orders.product_id');
            $orders = $this->db->get()->result();
            $order_debt = 0;
            foreach($orders as $order)
            {
                if($order->daily_sale == "daily")
                {
                    $now = time();
                    if($date)
                    {
                        $now = strtotime($date);
                    }
                    $your_date = strtotime($order->date);
                    $datediff = $now - $your_date;

                    $day = floor($datediff / (60 * 60 * 24));
                    $order_debt += intval($order->product_quantity) * intval($order->daily_price) * $day;
                }
                else
                {
                    if($date)
                    {
                        if(strtotime($order->date) > strtotime($date))
                        {
                            $order_debt += intval($order->product_quantity) * intval($order->sale_price);
                        }
                    }
                    else
                    {
                        $order_debt += intval($order->product_quantity) * intval($order->sale_price);
                    }
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
                if($date)
                {
                    $now = strtotime($date);
                }
                $your_date = strtotime($giveback->date);
                $datediff = $now - $your_date;

                $day = floor($datediff / (60 * 60 * 24));
                if($date)
                {
                    if(strtotime($giveback->date) > strtotime($date))
                    {
                        $giveback_amount += intval($giveback->quantity) * intval($product_price) * $day;
                    }
                }
                else
                {
                    $giveback_amount += intval($giveback->quantity) * intval($product_price) * $day;
                }
            }
            $this->db->where('client_id', $client_id);
            $query = $this->db->get('payment');
            $payments = $query->result();
            $paid = 0;
            foreach($payments as $payment)
            {
                if($date)
                {
                    if(strtotime($payment->date) > strtotime($date))
                    {
                        $paid += $payment->amount;
                    }
                }
                else
                {
                    $paid += $payment->amount;
                }
            }

            $final_debt = $debt + $order_debt - $giveback_amount - $paid;
            if($final_debt > 0)
            {
                $result_array[$client->name] = $final_debt;
            }
        }
        if($date)
        {
            $datepicker_value = $date;
        }
        else
        {
            $datepicker_value = date('d/m/Y');
        }
        $html = '<div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title" id="client_debt">Պարտքերի ցուցակ</h4>
            <div class="input-group date" style="width: 200px;" data-provide="datepicker">
                <input type="text" id="debts_date" value="'.$datepicker_value.'" class="form-control datepicker" name="debts_date">
                <div class="input-group-addon">
                    <span class="glyphicon glyphicon-th"></span>
                </div>
            </div></div>';
        $html .= '<div class="modal-body"><div class="col-md-12">';
        $html .= '<table class="table"><th>Կլիենտ</th><th>Պարտք</th>';
        foreach($result_array as $name => $debt)
        {
            $html .= '<tr><td>'.$name.'</td><td>'.$debt.' դրամ</td></tr>';
        }
        $html .= '</table></div></div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Փակել</button></div>';
        echo json_encode(array('html' => $html));
    }
}
