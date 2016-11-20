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
}
