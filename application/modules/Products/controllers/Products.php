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
        $nama = $this->get('nama');
        if ($nama === null) {
            $data_order = $this->db->select('p.id, p.nama,c.nama_category, c.description, p.stok,harga ')
                ->from('products p')
                ->join('category c ', 'p.id_category=c.id')
                ->get()->result_array();
        } else {
            $data_order = $this->Products_model->getProducts($nama);
        }
        if ($data_order) {
            $this->set_response([
                'status' => true,
                'message' => 'data berhasil ditemukan',
                'data' => $data_order
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
        $data_order = [
            'id' => $this->post('id'),
            'nama' => $this->post('nama_barang'),
            'harga' => $this->post('harga'),
            'stok' => $this->post('stok'),
            'id_category' => $this->post('id_category'),
            'deskripsi' => $this->post('deskripsi')
        ];
        // $data_order = $this->db->select('p.id, p.nama,c.nama_category, c.description, p.stok,harga ')
        //     ->from('products p')
        //     ->join('category c ', 'p.id_category=c.id')
        //     ->get()->result_array();
        if ($this->Products_model->createProducts($data_order) > 0) {

            $this->set_response([
                'status' => true,
                'message' => 'new products has been created.',
                'data' => $data_order

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
