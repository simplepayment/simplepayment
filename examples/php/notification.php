<?php

include_once __DIR__ . "/SimplePayment.php";

// FROM Simplepayment
$app_id = '58c787cf398e82bea93ce070';
$secret_key = '686hp2j143l37lsapgdfhl4n29';

$json = file_get_contents("php://input");

$signature = SimplePayment::encrypt( $json, $secret_key);

//check Bodysign
$incoming_signature = $_SERVER['HTTP_BODYSIGN'];

$incoming_app_id = $_SERVER['HTTP_APPID'];

if ( $incoming_signature === $signature && $incoming_app_id === $app_id){
  var_dump( json_decode( $json, true ));

  //do your magic here

}
