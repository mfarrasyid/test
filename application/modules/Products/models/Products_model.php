<?php

class Products_model extends CI_Model
{
    public function getProducts($nama = null)
    {
        //conditon search 
        if ($nama === null) {
            //findall
            return $this->db->get('products')->result_array();
        } else {
            //get_where mengambil id sesuai nama
            return $this->db->get_where('products', ['nama' => $nama])->result_array();
        }
    }

    public function createProducts($data)
    {
        $this->db->insert('products', $data);
        return $this->db->affected_rows();
    }
}
