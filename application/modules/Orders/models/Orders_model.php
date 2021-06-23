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

        $order_id = $this->db->insert_id();
        if ($nama_barang === null) {
            $query = $this->db->from('order')->join('products', 'products.id = order.id_product')->where('order.id', $order_id)->result_array();
            DD($query);
        } else {
            return $this->db->get_where('order', ['nama_barang' => $nama_barang])->result_array();
        }
    }

    // public function createOrder($data)

    // {

    //     // untuk ngirim 
    //     $save = $this->db->insert('order', $data); // => 100
    //     // get id order
    //     // dd($save);
    //     $order_id = $this->db->insert_id('id');
    //     // dd($order_id);
    //     //get data dar id order

    //     $data = $this->db->where('id', $order_id)->get('order')->row();
    //     $query = $this->db->from('order')->join('products', 'products.id = order.id_product')->where('order.id', $order_id)->get()->row();
    //     dd($query);
    //     return $save;
    // }
}
