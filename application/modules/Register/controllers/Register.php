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
        //validasi form
        $this->form_validation->set_rules('nama', 'Name', 'required');
        $this->form_validation->set_rules('email', 'Username', 'required');
        $this->form_validation->set_rules('address', 'Address', 'required');
        $this->form_validation->set_rules('password', 'Password ', 'required');
        //condition jika validasi terpenuhi
        if ($this->form_validation->run() == TRUE) {
            $data_register = [
                'nama' => $this->post('nama'),
                'email' => $this->post('email'),
                'address' => $this->post('address'),
                'password' => $this->post('password'),
            ];
            $simpan = ($this->Register_model->createRegister($data_register) > 0);
            if ($simpan) {
                //respon oke
                $this->set_response([
                    'status => true',
                    'message' => 'Data Berhasil Disimpan!',
                    'data' => $data_register,
                ], REST_Controller::HTTP_OK);
            } else {
                //respon gagal
                $this->set_response([
                    'status => false',
                    'message' => 'field is required'
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            //validasi 
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
