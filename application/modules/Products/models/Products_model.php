<?php

class Products_model extends CI_Model
{
    public function getProducts($nama = null)
    {

        if ($nama === null) {

            return $this->db->get('products')->result_array();
        } else {
            return $this->db->get_where('products', ['nama' => $nama])->result_array();
        }
    }

    // public function data_anggota(){
    //     $this->db->select('*');
    //     $this->db->from('products');	
    //     $query = $this->db->get();
    //     return $query;
    //  }

    //  public function data_simpanan(){
    //     $this->db->select('*');
    //     $this->db->from('products');
    //     $this->db->join('category','products.id_category = catgeory.id');		
    //     $query = $this->db->get();
    //     return $query;
    //  }

    //  function join2table(){
    //     $this->db->select('*');
    //     $this->db->from('simpanan');
    //     $this->db->join('anggota','anggota.id_anggota = simpanan.id_anggota');		
    //     $query = $this->db->get();
    //     return $query;
    //  }}
    public function createProducts($data)
    {
        $this->db->insert('products', $data);
        // $this->db->insert('category', $data);
        return $this->db->affected_rows();
    }

    public function deleteProducts($id)
    {
        $this->db->delete('products', ['id' => $id]);
        return $this->db->affected_rows();
    }
}
