<?php

class Access extends RESTNoAuth
{

    function index_get()
    {
        echo "INDEX";
    }

    function login_post()
    {

        $data["status"] = true;
        $data['message'] = "berhasil ditampilkan";

        $users = $this->db->select('*')->from('users')->get()->row();

        $data['data']   =  $users;

        $this->set_response($data);
    }
}
