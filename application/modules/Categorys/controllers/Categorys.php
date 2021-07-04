<?php

use GuzzleHttp\Psr7\Message;

class Categorys extends RESTWithAuth
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Categorys_model');
        $this->load->library('form_validation');
    }

    public function index_get()
    {
        //condition search by nama
        $nama_category = $this->get('nama_category');
        //nama null = findall
        if ($nama_category == null) {
            $category = $this->Categorys_model->getCategory();
        } else {
            // get data by nama 
            $category = $this->Categorys_model->getCategory($nama_category);
        }
        if ($category) {
            //oke
            $this->set_response([
                "status" => true,
                "message" => "data berhasil ditemukan",
                "data" => $category
            ], REST_Controller::HTTP_OK);
        } else {
            //gagal
            $this->set_response([
                "status" => false,
                "message" => "data tidak dapat ditemukan"
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
    public function index_post()
    {
        //validasi form
        $this->form_validation->set_rules('nama', 'Nama Category', 'required');

        if ($this->form_validation->run() == TRUE) {
            $data = [
                'nama_category' => $this->post('nama'),
                'description' => $this->post('description')
            ];
            //simpan ke database
            $simpan = ($this->Categorys_model->createCategory($data) > 0);
            if ($simpan) {
                //ok
                $this->set_response([
                    'status' => true,
                    'message' => 'data berhasil ditemukan',
                    'data' => $data
                ], REST_Controller::HTTP_OK);
            } else {
                //gagal
                $this->set_response([
                    'status' => true,
                    'message' => 'data berhasil ditemukan'
                ], REST_Controller::HTTP_OK);
            }
        } else {
            //validasi form
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
