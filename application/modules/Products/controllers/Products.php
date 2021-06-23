<?php


class Products extends RESTNoAuth
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Products_model');
    }
    public function index_get()
    {
        $id = $this->get('id');
        if ($id === null) {
            $products = $this->Products_model->getProducts();
        } else {
            $products = $this->Products_model->getProducts($id);
        }
        if ($products) {
            $this->set_response([
                'status => true',
                'data' => $products
            ], REST_Controller::HTTP_OK);
        } else {
            $this->set_response([
                'status => false',
                'message => id not found'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function index_post()
    {
        $data = [
            'id' => $this->post('id'),
            'nama_barang' => $this->post('nama_barang'),
            'harga' => $this->post('harga'),
            'stok' => $this->post('stok'),
            'id_category' => $this->post('id_category'),
            'deskripsi' => $this->post('deskripsi')
        ];
        if ($this->Products_model->createProducts($data) > 0) {

            $this->set_response([
                'status' => true,
                'message' => 'new products has been created.',
                'data' => $data

            ], REST_Controller::HTTP_CREATED);
        } else {
            $this->set_response([
                'status => false',
                'message => fialed to created'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }


    public function index_delete()
    {
        $id = $this->get('id');
        if ($id === null) {
            $this->set_response([
                'status => false',
                'message => provide in id'
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {
            if ($this->Products_model->deleteProducts($id) > 0) {
                //ok
                $this->set_response([
                    'status => true',
                    'id' => $id,
                    'message' => 'deleted'
                ], REST_Controller::HTTP_OK);
            } else {
                //gagal
                $this->set_response([
                    'status => false',
                    'message => id not found'
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }
    }
}
