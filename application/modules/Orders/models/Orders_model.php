<?php

use CodeIgniter\Database\BaseBuilder;

class Orders_model extends CI_Model
{
    // protected $db;
    // public function __construct()
    // {
    //     $this->db = db_connect();
    // }
    // public function getdataid($id_users)
    // {
    //     // $this->db->select('*');
    //     $this->db->select('p.id,o.id_product,p.nama,o.id_users,p.deskripsi,p.id_category,c.nama_category,o.qty, p.harga, o.total', 'd_users')
    //         ->from('order o')
    //         ->join('users u', 'o.id_users=u.id')
    //         ->join('products p ', 'o.id_product=p.id')
    //         ->join('category c', 'p.id_category=c.id')
    //         ->where('o.id_users', 'u.id')
    //         ->get()->result_array();
    //     $query = $this->db->get();
    //     if ($query->num_rows() != 0) {
    //         return $query->result_array();
    //     } else {
    //         return false;
    //     }
    // }
    public function getOrder()
    {
        $this->load->model('Orders_model', 'model');
        $data = [
            'order' => $this->model->getOrder()
        ];
        return $this->db->get($data)->result_array();
    }

    public function update($table, $array, $id)
    {

        $this->db->set($array);
        $this->db->where('id', $id);
        $this->db->update($table);
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
