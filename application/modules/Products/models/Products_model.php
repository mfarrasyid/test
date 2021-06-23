<?php

class Products_model extends CI_Model
{
    public function getProducts($id = null)
    {
        if ($id === null) {

            return $this->db->get('products')->result_array();
        } else {
            return $this->db->get_where('products', ['id' => $id])->result_array();
        }
    }

    public function createProducts($data)
    {
        $this->db->insert('products', $data);
        return $this->db->affected_rows();
    }

    public function deleteProducts($id)
    {
        $this->db->delete('products', ['id' => $id]);
        return $this->db->affected_rows();
    }
}
