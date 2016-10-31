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
		$this->db->select('clients.name as client_name,clients.id as client_id, products.name as product_name, orders.product_quantity');
		$this->db->from('orders');
		$this->db->join('products', 'products.id = orders.product_id');
		$this->db->join('clients', 'clients.id = orders.client_id');
		$query = $this->db->get();
		$data = $query->result();//var_dump($data);
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
		foreach($data as $key => $value)
		{
			if(isset($res[$value->product_name][$value->client_name]))
			{
				$res[$value->product_name][$value->client_name] += intval($value->product_quantity);
			}
			else
			{
				$res[$value->product_name][$value->client_name] = intval($value->product_quantity);
			}
		}
		$data = array('res' => $res, 'clients' => $clients, 'products' => $products, 'client_ids' => $client_ids);
		$this->load->view('dashboard', $data);
	}
}
