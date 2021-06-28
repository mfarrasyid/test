<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Access_model extends CI_Model
{

    public function getUser()
    {

        return $this->db->get('users')->result_array();
    }

    public function getUserWhere($email, $pass)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('email', $email);
        $this->db->where('password', $pass);

        $data = $this->db->get();

        return $data;
    }
}
