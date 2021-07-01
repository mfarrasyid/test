<?php

class Register_model extends CI_Model
{
    public function createRegister($data)
    {
        $this->db->insert('users', $data);
        return $this->db->affected_rows();
    }
}
