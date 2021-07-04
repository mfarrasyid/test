<?php


class Products extends RESTWithAuth
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Products_model');
    }
    public function index_get()
    {
        //search sesuai nama products
        $nama_product = $this->get('nama_product');
        if ($nama_product === null) {
            $data_order = $this->db->select('
            p.id, p.nama_product,
            (SELECT nama_category FROM category c WHERE c.id=p.id_category ) as nama_category,
            p.deskripsi, p.stok, p.harga
            ')->from('products p')->get()->result_array();
        } else {
            //menampilkan sesuai search nama products
            $data_order = $this->Products_model->getProducts($nama_product);
        }
        //respon search oke
        if ($data_order) {
            $this->set_response([
                'status' => true,
                'message' => 'data berhasil ditemukan',
                'data' => $data_order
            ], REST_Controller::HTTP_OK);
        } else {
            //respon search gagal/tidak ada data
            $this->set_response([
                'status => false',
                'message => data tidak dapat ditemukan'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function index_post()
    {
        //form create
        $data_order = [
            'id' => $this->post('id'),
            'nama_product' => $this->post('nama_product'),
            'harga' => $this->post('harga'),
            'stok' => $this->post('stok'),
            'id_category' => $this->post('id_category'),
            'deskripsi' => $this->post('deskripsi')
        ];
        if ($this->Products_model->createProducts($data_order) > 0) {
            //responn oke
            $this->set_response([
                'status' => true,
                'message' => 'new products has been created.',
                'data' => $data_order
            ], REST_Controller::HTTP_CREATED);
        } else {
            //respon gagal
            $this->set_response([
                'status => false',
                'message => fialed to created'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
}
