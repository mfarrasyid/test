<?php

use GuzzleHttp\Psr7\Message;

class Orders extends RESTWithAuth
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Orders_model');
        $this->load->library('form_validation');
    }

    public function index_get()
    {
        //data user ini warisan dari middleware 
        $datauser = $this->datausers->id;

        // // dd($data_order);
        // $this->db->select('u.nama')->from('users u')->where('u.users_id=o.id_users');
        // $subQuery = $this->db->get_compiled_select();
        $data_order = $this->db->select('
           id,id_users,
           (SELECT u.nama FROM users u WHERE u.users_id = o.id_users) as nama_user,
           id_product,
            (SELECT p.nama_product FROM products p WHERE p.id=o.id_product) as nama_products,
            (SELECT p.deskripsi FROM products p WHERE p.id=o.id_product) as deskripsi,
            (SELECT p.id_category FROM products p WHERE p.id=o.id_product) as id_category,
            (SELECT c.nama_category FROM category c WHERE c.id=
            (SELECT p.id_category FROM products p WHERE p.id=o.id_product)) as nama_category,    
            qty,
            (SELECT p.harga FROM products p WHERE p.id=o.id_product) as harga,
            total
            ')->from('order o')->where('o.id_users ', $datauser)->get()->result_array();
        // dd($this->db->get_compiled_select());

        // $data_order = [];
        if ($data_order) {
            //respon jika berhasil
            $this->set_response([
                'status' => true,
                'message' => 'data berhasil ditemukan',
                'data' => $data_order
            ], REST_Controller::HTTP_OK);
        } else {
            //respon jika user belum melakukan transaksi 
            $this->set_response([
                'status' => true,
                'message' => 'Anda belum melakukan transaksi'
            ], REST_Controller::HTTP_OK);
        }
    }

    public function index_post()
    {
        //validasi untuk form
        $this->form_validation->set_rules('id_product', 'Id Product', 'required');
        $this->form_validation->set_rules('qty', 'Qty', 'required');

        //mengambil id yang di post dan select harga dan stok 
        $id_product =  $this->post('id_product');
        $products = $this->db
            ->select([
                '(SELECT p.harga FROM products p WHERE p.id=' . $id_product . ') as harga,
                 (SELECT p.stok FROM products p WHERE p.id=' . $id_product . ') as stok'
            ])->from('order')->get()->row();
        //perkaliaan untuk hasil total
        // dd($products);
        $harga_value = $products->harga;
        $qty = $this->post('qty');
        $total = $harga_value * $qty;
        // dd($total);
        if ($this->form_validation->run() == TRUE) {
            $data = array(
                // 'id_users' => $this->post('id_users'),
                'id_product' => $this->post('id_product'),
                'qty' => $this->post('qty'),
                'total' => $total
            );
            //condition saat jumlah order melebihi stok barang
            $qtyproduct = $products->stok;
            if ($qty > $qtyproduct) {
                //respon qty > stok
                $this->response([
                    'status'    => false,
                    'message'   => 'Maaf stok kami tidak mencukupi'
                ], REST_Controller::HTTP_NOT_FOUND);
            } else {
                //update untuk stok-qty saat melakukan transaksi
                $qtyreal = $qtyproduct - $qty;
                $datanya = array(
                    'stok' => $qtyreal,
                );
                $updateqq = $this->Orders_model->update('products', $datanya, $id_product);

                //menampilkan hasil transaksi sesuai id transaksi
                $simpan = $this->db->insert('order', $data);
                $order_id = $this->db->insert_id();
                // dd($order_id);
                // die;
                if ($simpan) {
                    $data_query = $this->db->select('
                    id,
                    id_product,
                     (SELECT p.nama_product FROM products p WHERE p.id=o.id_product) as nama_products,
                     (SELECT p.deskripsi FROM products p WHERE p.id=o.id_product) as deskripsi,
                     (SELECT p.id_category FROM products p WHERE p.id=o.id_product) as id_category,
                     (SELECT c.nama_category FROM category c WHERE c.id=
                     (SELECT p.id_category FROM products p WHERE p.id=o.id_product)) as nama_category,    
                     qty,
                     (SELECT p.harga FROM products p WHERE p.id=o.id_product) as harga,
                     total
                     ')->from('order o')->where('o.id ', $order_id)->get()->result_array();
                    //respon oke
                    // dd($data_query);
                    $this->set_response([
                        'status' => true,
                        'message' => 'Data Berhasil Disimpan!',
                        'data' => $data_query,
                    ], REST_Controller::HTTP_OK);
                } else {
                    //respon gagal
                    $this->set_response([
                        'status' => false,
                        'message' => 'field is required'
                    ], REST_Controller::HTTP_NOT_FOUND);
                }
            }
        } else {
            //mengatul validasi
            header('Content-Type: application/json');
            echo json_encode(
                array(
                    'success'    => false,
                    'message'    => validation_errors()
                )
            );
        }
    }
}
