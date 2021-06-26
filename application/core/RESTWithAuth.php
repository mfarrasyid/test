<?php
defined('BASEPATH') or exit('No direct script access allowed');

//use Closure;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;

class RESTWithAuth extends RESTNoAuth
{


    function __construct()
    {
        parent::__construct();
        // try {


        //     $token = $this->input->request_headers()['Authorization'];
        $token = $this->input->request_headers()['Authorization'];
        if ($token != null) {
            return $token;
        } else {
        }
        //     if ($token != null) {
        //         return $token;
        //     }
        //     return TRUE;
        // } catch (ExpiredException $e) {
        //     // this will not catch DB related errors. But it will include them, because this is more general. 
        //     $this->response([
        //         "status" => false,
        //         "message" => " Username / Password salah",
        //     ]);
        // } catch (SignatureInvalidException $e) {
        //     $this->response([
        //         "status" => false,
        //         "message" => "token exfired",
        //     ]);
        // }
        // $this->response([
        //     "status" => false,
        //     "message" => " Username / Password salah",
        // ]);
        // print_r($token);
        // $hook['post_controller_constructor'][] = array(
        //     'class'    => 'Autologin',  
        //     'function' => 'cookie_check', 
        //     'filename' => 'autologin.php',  
        //     'filepath' => 'hooks'
        // );
    }
}
