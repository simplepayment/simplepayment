<?php

include_once __DIR__ . "/SimplePayment.php";

// FROM Simplepayment
$app_id = '58c787cf398e82bea93ce070';
$secret_key = '686hp2j143l37lsapgdfhl4n29';

$redirect_url = 'http://your-domain.com/redirect_url';
$testing = false; // development mode

$data = [
  'timestamp' => time(),
  'id' => '5b2e1344eef87c12b425f701'
];

$json_data = json_encode( $data );

$signature = SimplePayment::encrypt( $json_data, $secret_key);

$response = SimplePayment::check( $app_id, $signature, $json_data, $testing );

$arrayHeaders = SimplePayment::getHeaders( $response[0] );
//verify the returned signature if you wish
var_dump( $arrayHeaders );

$arrayResponse = json_decode( $response[1], true );

var_dump( SimplePayment::encrypt(  $response[1], $secret_key) );

var_dump( $response[1]);
