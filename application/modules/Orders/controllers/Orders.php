<?php

use GuzzleHttp\Psr7\Message;

class Orders extends RESTNoAuth
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Orders_model');
        $this->load->library('form_validation');
    }

    public function index_get()
    {
        $nama = $this->get('nama_barang');
        if ($nama == null) {
            $orders = $this->Orders_model->getOrder();
        } else {
            $orders = $this->Orders_model->getOrder($nama);
        }
        if ($orders) {
            $this->set_response([
                'status => true',
                'message => data berhasil ditemukan',
                'data' => $orders
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
        $this->form_validation->set_rules('id_products', 'Id Products', 'required');
        $this->form_validation->set_rules('qty', 'Qty', 'required');


        if ($this->form_validation->run() == TRUE) {
            $data = array(
                'id_product' => $this->post('id_products'),
                'qty' => $this->post('qty'),
                // 'total' => $this->post(qty * )
            );
            $simpan = $this->db->insert('order', $data);
            $order_id = $this->db->insert_id();

            if ($simpan) {
                // $total = $this->db->select('SUM(qty) + SUM(harga) + SUM(total) as total', true);
                // dd($total);
                // die;
                $query = $this->db->from('order')->join('products', 'products.id = order.id_product')->where('order.id', $order_id)->get()->row();
                header('Content-Type: application/json');
                echo json_encode(
                    array(
                        'success' => true,
                        'data' => $query,
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

    //     $da1ta = [
    //         'id' => $this->post('id'),
    //         'id_product' => $this->post('id_product'),
    //         'id_user' => $this->post('id_user'),
    //         'id_category' => $this->post('id_category'),
    //         'nama_users' => $this->post('nama_users'),
    //         'category_barang' => $this->post('category_barang'),
    //         'nama_barang' => $this->post('nama_barang'),
    //         'qty' => $this->post('qty'),
    //         'total' => $this->post('total')

    //     ];
    //     // var_dump($data);
    //     // $order = $this->Orders_model->createOrder($data);

    //     if ($this->Orders_model->createOrder($data) > 0) {
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
