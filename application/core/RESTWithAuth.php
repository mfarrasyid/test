<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

//use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;

class RESTWithAuth extends RESTNoAuth
{


    function __construct()
    {
        parent::__construct();

        $key = "43211234";
        $token = $this->input->request_headers()['Authorization'];
        // $token = JWT::encode($payload, $key);

        if ($token != null) {
            try {
                $token = JWT::decode($token, $key, array('HS256'));
            } catch (ExpiredException $th) {
                // print("Token Expired");
                $this->response([
                    "status" => false,
                    "message" => " token failed",
                ]);
            } catch (SignatureInvalidException $th) {
                $this->response([
                    "status" => false,
                    "message" => " Username / Password salah",
                ]);
            }
            return $token;
        } else {
            $this->response([
                "status" => false,
                "message" => " Username / Password salah",
            ]);
        }
    }
}
