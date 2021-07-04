<?php

class Orders_model extends CI_Model
{
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
}
