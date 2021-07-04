<?php

use Firebase\JWT\JWT;
use Illuminate\Http\Request;

class Access extends RESTNoAuth
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Access_model');
        $this->load->library('form_validation');
    }

    function index_get()
    {
        $users = $this->Access_model->getUser();
        if ($users) {
            $this->set_response([
                "status" => true,
                "data" => $users
            ], REST_Controller::HTTP_OK);
        } else {
            $this->set_response([
                "status" => false,
                "message" => "id not found"
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    function login_post()
    {
        $today =  time();
        $exp = strtotime("2021-07-07");
        $email = $this->input->post('email', TRUE);
        $password = $this->input->post('password', TRUE);
        $users = $this->Access_model->getUserWhere($email, $password);
        // dd($users->row()->id);
        $key = "43211234";
        $payload = array(
            "iat" => $today,
            "exp" => $exp,
            "id" => $users->row()->users_id,
            "data" => [
                "password" => $password,
                "email" => $email,
            ]
        );

        $token = JWT::encode($payload, $key);
        $user = $users->result();
        $return_data = [
            "token" => $token,
            "user"  => $user,
        ];
        // dd($return_data);
        if ($users->num_rows() > 0) {

            $this->set_response([
                "status" => true,
                "message" => "Login Berhasil",
                "data" => $return_data,
            ], REST_Controller::HTTP_OK);
        } else {

            $this->set_response([
                "status" => false,
                "message" => "Username / Password salah"
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
}
