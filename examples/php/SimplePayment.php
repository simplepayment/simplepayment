<?php

class SimplePayment
{

  const SANDBOX_URL = 'https://staging.simplepayment.solutions/';

  const PRODUCTION_URL = 'https://api.simplepayment.solutions/';

  public static function encrypt( $json_data, $secret ) {
    return hash_hmac('sha256', base64_encode($json_data), $secret);
  }

  public static function submit( $app_id, $signature, $json_data, $testing = true ){

    $headers = [
      'Content-Type: application/json',
      'AppId: '.$app_id,
      'Bodysign: ' .$signature,
    ];

    $url = $testing ? self::SANDBOX_URL : self::PRODUCTION_URL;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url.'api/v1/create');
    curl_setopt($ch, CURLOPT_VERBOSE, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);

    $rs = curl_exec($ch);

    curl_close($ch);

    return explode("\r\n\r\n", $rs, 2);
  }

  public static function check( $app_id, $signature, $json_data, $testing = true ){

    $headers = [
      'Content-Type: application/json',
      'AppId: '.$app_id,
      'Bodysign: ' .$signature,
    ];

    $url = $testing ? self::SANDBOX_URL : self::PRODUCTION_URL;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url.'api/v1/check_order');
    curl_setopt($ch, CURLOPT_VERBOSE, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);

    $rs = curl_exec($ch);

    curl_close($ch);

    return explode("\r\n\r\n", $rs, 2);
  }

  public static function getHeaders( $response ){

    $headers = array();

    foreach (explode("\r\n", $response) as $i => $line)
    if ($i === 0)
    $headers['http_code'] = $line;
    else
    {
      list ($key, $value) = explode(': ', $line);
      $headers[$key] = $value;
    }

    return $headers;

  }

}
