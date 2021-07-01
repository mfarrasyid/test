<?php

class Register extends RESTNoAuth
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Register_model');
        $this->load->library('form_validation');
    }
    public function index_post()
    {
        $this->form_validation->set_rules('nama', 'Name', 'required');
        $this->form_validation->set_rules('address', 'Address', 'required');
        $this->form_validation->set_rules('email', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password ', 'required');

        if ($this->form_validation->run() == TRUE) {

            $data_register = [
                'nama' => $this->post('nama'),
                'address' => $this->post('address'),
                'email' => $this->post('email'),
                'password' => $this->post('password'),
            ];
            $simpan = ($this->Register_model->createRegister($data_register) > 0);

            // dd($data);
            // die;
            if ($simpan) {
                header('Content-Type: application/json');
                echo json_encode(
                    array(
                        'success' => true,
                        'data' => $data_register,
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

        // public function register_post()
        // {
        //     $data_register = [
        //         // 'id' => $this->post('id'),
        //         'nama' => $this->post('nama'),
        //         'email' => $this->post('email'),
        //         'password' => $this->post('password'),
        //         'address' => $this->post('address')
        //         // 'deskripsi' => $this->post('deskripsi')
        //     ];
        //     if ($this->Register_model->createRegister($data_register) > 0) {

        //         $this->set_response([
        //             'status' => true,
        //             'message' => 'new products has been created.',
        //             'data' => $data_register

        //         ], REST_Controller::HTTP_CREATED);
        //     } else {
        //         $this->set_response([
        //             'status => false',
        //             'message => fialed to created'
        //         ], REST_Controller::HTTP_NOT_FOUND);
        //     }
        // }
    }
}
