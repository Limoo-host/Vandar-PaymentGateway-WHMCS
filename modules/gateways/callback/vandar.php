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

$gatewayParams = getGatewayVariables('vandar');

if ($gatewayParams['type'] == FALSE) {

    die('Module Not Activated');
}

$success = FALSE;


function postToVandar()
{
    $data = [
        "api_key" => getGatewayVariables('vandar')['apiKey'],
        "token" => $_GET['token']
      ];

    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://ipg.vandar.io/api/v4/verify',
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
    return json_decode($response,true);
}

$postToVandar  = postToVandar();


if ($postToVandar['status'] == 1) {

  addInvoicePayment(
    $postToVandar['factorNumber'],
    $postToVandar['transId'],
    $postToVandar['amount'],
    0,
    'vandar'
  );
  logTransaction($gatewayParams['name'], array(
    'Code'        => 'Success',
    'Message'     => 'تراکنش با موفقیت انجام شد',
    'Transaction' => $postToVandar['transId'],
    'Invoice'     => $postToVandar['factorNumber']
  ), 'Success');

  header('Location: ' . $gatewayParams['systemurl'] . '/viewinvoice.php?id=' . $postToVandar['factorNumber']);
  exit;

}else{


  logTransaction($gatewayParams['name'], array(
    'Code'        => 'Failure',
    'Message'     => $postToVandar['errors'][0],
    'Transaction' => $postToVandar['transId'],
    'Invoice'     => $postToVandar['factorNumber']
  ), 'Failure');

  header('Location: ' . $gatewayParams['systemurl'] . '/clientarea.php?action=invoices');
}

header('Location: ' . $gatewayParams['systemurl'] . '/clientarea.php?action=invoices');