<?php

include_once __DIR__ . "/SimplePayment.php";

// FROM Simplepayment
$app_id = '5b18b66ceed56c5e2f13b122';
$secret_key = 's0nmjCAlP0GrDZDJWo0ELjuf1a3lGw2PlB5Ke8Hk';

$redirect_url = 'http://your-domain.com/redirect_url';
$testing = true; // development mode

$data = [
  'timestamp' => time(),
  'currency' => 'IDR',
  'amount' => 10000,
  'user_id' => time(),
  'item_name' => 'Test items',
  'payment_channel' => 'airtime_testing',
  'redirect_url' => $redirect_url,
  'merchant_transaction_id' => time().mt_rand(100000,999999),
];

$json_data = json_encode( $data );

$signature = SimplePayment::encrypt( $json_data, $secret_key);

$response = SimplePayment::submit( $app_id, $signature, $json_data, $testing );

$arrayHeaders = SimplePayment::getHeaders( $response[0] );
//verify the returned signature if you wish

$arrayResponse = json_decode( $response[1], true );

if ( json_last_error() ){
  //something is wrong
  var_dump( $response[1]);
  die('Please check your app id and secret');
}else{
  echo '<a href="'.$arrayResponse['data']['links']['href'].'">Continue to Web</a>';
}
