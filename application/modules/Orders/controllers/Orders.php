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
        $data_order = $this->db->select('p.id,o.id_product,p.nama,o.id_users,p.deskripsi,p.id_category,c.nama_category,o.qty, p.harga, o.total', 'd_users')
            ->from('order o')
            ->join('users u', 'o.id_users=u.id', 'left')
            ->join('products p ', 'o.id_product=p.id', 'left')
            ->join('category c', 'p.id_category=c.id', 'left')
            ->where('o.id_users', $datauser)
            ->get()->result_array();
        // dd($data_order);
        if ($data_order) {

            // dd($data_order);
            $this->set_response([
                'status' => true,
                'message' => 'data berhasil ditemukan',
                'data' => $data_order
            ], REST_Controller::HTTP_OK);
        } else {
            $this->set_response([
                'status => true',
                'message => Anda belum melakukan transaksi'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function index_post()
    {
        $this->form_validation->set_rules('id_products', 'Id Products', 'required');
        $this->form_validation->set_rules('qty', 'Qty', 'required');

        $id_products =  $this->post('id_products');
        $products = $this->db->from('products')->select(['harga', 'stok'])->where('id', $id_products)->get()->row();

        $harga_value = $products->harga;
        $qty = $this->post('qty');
        $total = $harga_value * $qty;
        // dd($total);
        // die;
        if ($this->form_validation->run() == TRUE) {
            $data = array(
                'id_users' => $this->post('id_users'),
                'id_product' => $this->post('id_products'),
                'qty' => $this->post('qty'),
                'total' => $total
            );

            $qtyproduct = $products->stok;
            if ($qty > $qtyproduct) {
                // dd('haooo');
                // die;
                $this->response([
                    'status'    => false,
                    'message'   => 'Maaf stok kami tidak mencukupi'
                ], REST_Controller::HTTP_NOT_FOUND);
            } else {
                $qtyreal = $qtyproduct - $qty;
                $datanya = array(
                    'stok' => $qtyreal,
                );

                $updateqq = $this->Orders_model->update('products', $datanya, $id_products);

                $simpan = $this->db->insert('order', $data);
                $order_id = $this->db->insert_id();

                // dd($data);
                if ($simpan) {
                    // $query = $stok - $qty;
                    $query = $this->db->select('p.id,o.id_product,p.nama,o.id_users,p.deskripsi,p.id_category,c.nama_category,o.qty, p.harga, o.total')
                        ->from('order o')
                        ->join('users u', 'o.id_users=u.id', 'left')
                        ->join('products p', 'p.id = o.id_product')->where('o.id', $order_id)
                        ->join('category c', 'p.id_category=c.id')
                        ->get()->row();

                    // $id_product =  $this->post('id_products');
                    // $query_stok = $this->db->from('products')->select('stok')->where('id', $id_product)->get()->row();
                    // $stok = $query_stok->stok;

                    // dd($query);
                    // die;
                    header('Content-Type: application/json');
                    echo json_encode(
                        array(
                            'success' => true,
                            'data' => $query,
                            // 'data' => $total_stok,
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
