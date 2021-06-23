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

        // $this->db->select('*');
        // $this->db->from('products');
        // $subQuery = $this->db->get_compiled_select();

        // // Main Query
        // $this->db->select('*');
        // $this->db->from('category');
        // $this->db->join("($subQuery) AS nama_category ", 'category.id = products.id_category');
        // $r = $this->db->get();
        // var_dump($r);
        $id = $this->post('id_products');
        $this->db->select('*');
        // $this->db->select('nama');
        $this->db->where('id', $id);
        $this->db->from('products');

        $this->db->where('id', $id);
        $query2 = $this->db->get();

        $row = $query2->row();
        var_dump($row);
        die;

        // $data = $this->db->table("products")
        //     ->select(
        //         "category.*",
        //         $this->db->raw("(SELECT SUM(products.id) FROM products
        //                         WHERE products.id_product = category.id
        //                         GROUP BY products.id_product) as product"),
        //         // $this->db->raw("(SELECT SUM(products_sell.sell) FROM products_sell
        //         //                 WHERE products_sell.product_id = products.id
        //         //                 GROUP BY products_sell.product_id) as product_sell")
        //     )
        //     ->get();
        //     $row = $data->row();
        //     var_dump($row);
        //     die  ;

        // $qty = $this->post('qty');
        // $harga = 4000;
        // $total = $qty * $harga;

        if ($this->form_validation->run() == TRUE) {

            $data = array(
                'id_product' => $this->post('id_products'),
                'qty' => $this->post('qty')
                // 'category_barang' => $this->post('category_barang'),
                // 'nama_barang' => $this->post('nama_barang'),
                // 'total' => $total
            );

            $simpan = $this->Orders_model->createOrder($data);
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
