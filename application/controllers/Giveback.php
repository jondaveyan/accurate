<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Giveback extends CI_Controller {

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
        $query = $this->db->get('clients');
        $clients = $query->result();
        $query = $this->db->get('products');
        $products = $query->result();
        $data = array('clients' => $clients, 'products' => $products);
        $this->load->view('giveback', $data);
    }
    public function new_giveback()
    {
        $data = array(
            'client_id' => $this->input->post('client_to_pick'),
            'product_id' => $this->input->post('product_to_pick'),
            'quantity' => $this->input->post('product_quantity'),
            'date' => date('Y-m-d', strtotime($this->input->post('date')))
        );
        $this->db->insert('giveback', $data);
        redirect('giveback');
    }
    public function get_client_products($client_id)
    {
        $this->db->where('orders.daily_sale', 'daily');
        $this->db->select('clients.name as client_name,clients.id as client_id,products.id as product_id, products.name as product_name, orders.product_quantity');
        $this->db->from('orders');
        $this->db->join('products', 'products.id = orders.product_id');
        $this->db->join('clients', 'clients.id = orders.client_id');
        $query = $this->db->get();
        $data = $query->result();
        $products = array();
        foreach($data as $key => $value)
        {
            if($value->client_id == $client_id)
            {
                $products[$value->product_id] = $value->product_name;
            }
        }
        $products = array_unique($products);
        $res = '<option selected disabled>Ընտրել</option>';
        foreach($products as $key => $value)
        {
            $res .= '<option value="'.$key.'">'.$value.'</option>';
        }
        echo json_encode(array('result' => $res));
    }
}
