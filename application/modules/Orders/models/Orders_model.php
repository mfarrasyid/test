<?php

use CodeIgniter\Database\BaseBuilder;

class Orders_model extends CI_Model
{
    // protected $db;
    // public function __construct()
    // {
    //     $this->db = db_connect();
    // }

    public function getOrder($nama_barang = null)
    {
        if ($nama_barang === null) {
            return $this->db->get('order')->result_array();
        } else {
            return $this->db->get_where('order', ['nama_barang' => $nama_barang])->result_array();
        }
    }
    public function createOrder($data)

    {
        // $builder = $db->table('order');
        // $builder->select('*');
        // $builder->join('products', 'products.id = order.id');
        // $query = $builder->get();
        $save = $this->db->insert('order', $data);
        return $save;
    }
}
