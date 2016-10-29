<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends CI_Controller {

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
        $query = $this->db->get('clients');
        $clients = $query->result();
        $data = array('products' => $products, 'clients' => $clients);
        $this->load->view('orders', $data);
    }
    public function new_order()
    {
        if($this->input->post('new_client'))
        {
            $data = array(
                'name' => $this->input->post('new_client_name') ,
                'own' => $this->input->post('own_client')?'yes':'no' ,
                'debt' => 0
            );
            $this->db->insert('clients', $data);
            $client_id = $this->db->insert_id();
        }
        else
        {
            $client_id = $this->input->post('client_to_pick');
        }
        if($this->input->post('daily'))
        {
            $sale_price = 0;
            $daily_price = $this->input->post('product_price');
        }
        else
        {
            $daily_price = 0;
            $sale_price = $this->input->post('product_price');
        }
        $data = array(
            'client_id' => $client_id ,
            'product_id' => $this->input->post('product_to_pick') ,
            'product_quantity' => $this->input->post('product_quantity'),
            'sale_price' => $sale_price,
            'daily_price' => $daily_price
        );
        $this->db->insert('orders', $data);
        redirect('orders');
    }
}
