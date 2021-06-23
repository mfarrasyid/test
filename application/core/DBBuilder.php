<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Illuminate\Database\Capsule\Manager as DB;

class DBBuilder extends MX_Controller
{


    function __construct()
    {
        parent::__construct();
        include(APPPATH . '/config/database.php');

        $active = $db[$active_group];
        $capsule = new DB();
        $capsule->addConnection([
            'driver'    => 'mysql',
            'host'      => $active['hostname'],
            'database'  => $active['database'],
            'username'  => $active['username'],
            'password'  => $active['password'],
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ]);
        $capsule->setAsGlobal();
        $this->load->helper('db');

        $this->xdb = XDB();
    }
    //     public function get_compiled_select($table = '', $reset = TRUE)
    // {
    //     if ($table !== '')
    //     {
    //         $this->_track_aliases($table);
    //         $this->from($table);
    //     }

    //     $select = $this->_compile_select();

    //     if ($reset === TRUE)
    //     {
    //         $this->_reset_select();
    //     }

    //     return $select;
    // }
}
