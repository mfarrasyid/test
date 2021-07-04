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
        $this->form_validation->set_rules('id_products', 'Id Products', 'required');
        $this->form_validation->set_rules('qty', 'Qty', 'required');

        //mengambil id yang di post dan select harga dan stok 
        $id_products =  $this->post('id_products');
        $products = $this->db->from('products')->select(['harga', 'stok'])->where('id', $id_products)->get()->row();

        //perkaliaan untuk hasil total
        $harga_value = $products->harga;
        $qty = $this->post('qty');
        $total = $harga_value * $qty;
        if ($this->form_validation->run() == TRUE) {
            $data = array(
                'id_users' => $this->post('id_users'),
                'id_product' => $this->post('id_products'),
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
                $updateqq = $this->Orders_model->update('products', $datanya, $id_products);

                //menampilkan hasil transaksi sesuai id transaksi
                $order_id = $this->db->insert_id();
                $simpan = $this->db->insert('order', $data);
                if ($simpan) {
                    $query = $this->db->select('p.id,o.id_product,p.nama,o.id_users,p.deskripsi,p.id_category,c.nama_category,o.qty, p.harga, o.total')
                        ->from('order o')
                        ->join('users u', 'o.id_users=u.id', 'left')
                        ->join('products p', 'p.id = o.id_product')->where('o.id', $order_id)
                        ->join('category c', 'p.id_category=c.id')
                        ->get()->row();
                    //respon oke
                    $this->set_response([
                        'status => true',
                        'message' => 'Data Berhasil Disimpan!',
                        'id' => $query,
                    ], REST_Controller::HTTP_OK);
                } else {
                    //respon gagal
                    $this->set_response([
                        'status => false',
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
