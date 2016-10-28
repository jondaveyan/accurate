<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends CI_Controller {

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
        $data = array('products' => $products);
        $this->load->view('products', $data);
    }
    public function new_product()
    {
        $data = array(
            'name' => $this->input->post('name') ,
            'quantity' => $this->input->post('quantity') ,
            'new_quantity' => $this->input->post('new_quantity'),
            'type' => $this->input->post('type'),
            'state' => $this->input->post('state')
        );
        $this->db->insert('products', $data);
        redirect('products');
    }
    public function edit_product($id)
    {
        $data = array(
            'name' => $this->input->post('name') ,
            'quantity' => $this->input->post('quantity') ,
            'new_quantity' => $this->input->post('new_quantity'),
            'type' => $this->input->post('type'),
            'state' => $this->input->post('state')
        );
        $this->db->where('id', $id);
        $this->db->update('products', $data);
        redirect('products');
    }
    public function get_product_details($id)
    {
        $query = $this->db->get_where('products', array('id' => $id));
        echo json_encode(array('data' => $query->result()));
    }
}
