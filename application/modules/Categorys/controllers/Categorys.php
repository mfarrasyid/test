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
        $nama_category = $this->get('nama_category');

        if ($nama_category == null) {
            $category = $this->Categorys_model->getCategory();
        } else {
            $category = $this->Categorys_model->getCategory($nama_category);
        }
        if ($category) {
            dd($category);
            $this->set_response([
                "status" => true,
                "message" => "data berhasil ditemukan",
                "data" => $category
            ], REST_Controller::HTTP_OK);
        } else {
            $this->set_response([
                "status" => false,
                "message" => "data tidak dapat ditemukan"
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
    public function index_post()
    {
        $this->form_validation->set_rules('nama', 'Nama Category', 'required');

        if ($this->form_validation->run() == TRUE) {

            $data = [
                'nama_category' => $this->post('nama'),
                'description' => $this->post('description')
            ];
            $simpan = ($this->Categorys_model->createCategory($data) > 0);

            // dd($data);
            // die;
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

        // public function index_post()
        // {

        //     $data = [
        //         'nama_category' => $this->post('nama'),
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
}
