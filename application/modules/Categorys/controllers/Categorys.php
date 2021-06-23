<?php

use GuzzleHttp\Psr7\Message;

class Categorys extends RESTNoAuth
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Categorys_model');
        $this->load->library('form_validation');
    }

    public function index_get()
    {
        $nama = $this->get('nama');

        if ($nama == null) {
            $category = $this->Categorys_model->getCategory();
        } else {
            $category = $this->Categorys_model->getCategory($nama);
        }
        if ($category) {
            $this->set_response([
                'status => true',
                'message => data berhasil ditemukan',
                'data' => $category
            ], REST_Controller::HTTP_OK);
        } else {
            $this->set_response([
                'status => false',
                'message => data tidak dapat ditemukan'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
    public function index_post()
    {
        $this->form_validation->set_rules('nama', 'nama', 'required');

        // $id_products = $this->post('id_products');
        // $this->db->select('*');
        // $this->db->from('order');
        // $this->db->join('products', 'products.id = order.id_products');
        // $query = $this->db->get();
        // var_dump($query);

        // $query = $this->db->query("SELECT harga FROM products WHERE id = 1 AS HARGA");


        // var_dump($query);


        // $qty = $this->post('qty');
        // $harga = 4000;
        // $total = $qty * $harga;


        if ($this->form_validation->run() == TRUE) {

            $data = array(
                'nama' => $this->post('nama'),
                'description' => $this->post('description')
            );
            // var_dump($data);
            // die;
            $simpan = $this->Categorys_model->createCategory($data);

            if ($simpan) {

                header('Content-Type: application/json');
                echo json_encode(
                    array(
                        'success' => true,
                        'data' => $data,
                        'message' => 'Data Berhasil Disimpan!'
                    )
                );
            } else {

                header('Content-Type: application/json');
                echo json_encode(
                    array(
                        'success' => false,
                        'message' => 'Data Gagal Disimpan!'
                    )
                );
            }
        } else {

            header('Content-Type: application/json');
            echo json_encode(
                array(
                    'success'    => false,
                    'message'    => validation_errors()
                )
            );
        }
    }
    // public function index_post()
    // {

    //     $data = [
    //         'id' => $this->post('id'),
    //         'nama' => $this->post('nama'),
    //         'description' => $this->post('description')
    //     ];
    //     if ($this->Categorys_model->createCategory($data) > 0) {
    //         $this->set_response([
    //             'status' => true,
    //             'message' => 'new products has been created.',
    //             'data' => $data
    //         ], REST_Controller::HTTP_CREATED);
    //     } else {
    //         $this->set_response([
    //             'status' => false,
    //             'message => fialed to created'
    //         ], REST_Controller::HTTP_NOT_FOUND);
    //     }
    // }
}
