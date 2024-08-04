<?php

/**
 * 
 * @copyright	(c) 2024 - limoo.host Team
 * @website		limoo.host
 * @author	Mohammad javad Azimi
 */

require_once(__DIR__ . '/../../../init.php');
require_once(__DIR__ . '/../../../includes/gatewayfunctions.php');
require_once(__DIR__ . '/../../../includes/invoicefunctions.php');

$curl = curl_init();
$paymentAmount = isset($_POST['paymentAmount']) ? $_POST['paymentAmount'] : NULL;
$callbackUrl   = isset($_POST['callback_url']) ? $_POST['callback_url'] : NULL;
$invoiceId     = isset($_POST['invoice_id']) ? $_POST['invoice_id'] : NULL;
$api_key     = isset($_POST['invoice_id']) ? $_POST['api_key'] : NULL;
$data = [
  "api_key"=> $api_key,
  "amount"=> $paymentAmount,
  "callback_url"=> $callbackUrl,
  "factorNumber"=> $invoiceId,
];


curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://ipg.vandar.io/api/v4/send',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>json_encode($data),
  CURLOPT_HTTPHEADER => array(
    'Accept: application/json',
    'Content-Type: application/json',
  ),
));

$response = curl_exec($curl);

curl_close($curl);
$response = json_decode($response);

header('Location: https://ipg.vandar.io/web/v4/'.$response->token);

die;