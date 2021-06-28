<?php
require_once './vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;

// $today =  time();
// $exp = "2021-06-25";
// $email = $this->input->post('email', TRUE);
// $password = $this->input->post('password', TRUE);
// $users = $this->Access_model->getUserWhere($email, $password);
$key = "43211234";
$payload = array(
    "iss" => "dari kami",
    "aud" => "untuk user",
    "iat" => time(),
    "nbf" => "2021-07-29",
    "data" => [
        "password" => '1234',
        "email" => 'febri@gmail.com',
    ]
);

$token = JWT::encode($payload, $key);
// $decoded = JWT::decode($token, $key, array('HS256'));
// $token = $this->input->request_headers()['Authorization'];

print($token);
// if ($token != null) {
try {
    $decoded = JWT::decode($token, 's', array('HS256'));
    print_r($decoded);
} catch (ExpiredException $s) {
    print("token failed ");
} catch (SignatureInvalidException $s) {
    print("Username / Password salah");
}
// return $token;
// } else {
//     $this->response([
//         "status" => false,
//         "message" => " Username / Password salah",
//     ]);
// }
