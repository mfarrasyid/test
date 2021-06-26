<?php

class Categorys_model extends CI_Model
{
    public function getCategory($nama_category = null)
    {
        if ($nama_category === null) {
            return $this->db->get('category')->result_array();
        } else {
            return $this->db->get_where('category', ['nama_category' => $nama_category])->result_array();
        }
    }

    public function createCategory($data)
    {
        $this->db->insert('category', $data);
        return $this->db->affected_rows();
    }
}
