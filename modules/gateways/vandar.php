<?php

/**
 * 
 * @copyright	(c) 2024 - limoo.host Team
 * @website		limoo.host
 * @author	Mohammad javad Azimi
 */

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

function vandar_MetaData()
{
    return [
        'DisplayName' => 'vandar',
        'gatewayType' => 'Bank',
        'APIVersion' => '1.1',
        'DisableLocalCredtCardInput' => true,
        'TokenisedStorage' => false,
    ];
}

function vandar_config()
{
    return [
        'FriendlyName' => [
            'Type' => 'System',
            'Value' => 'درگاه وندار',
        ],
        'apiKey' => [
            'FriendlyName' => 'کلید api',
            'Type' => 'text',
            'Size' => '45',
            'Default' => '{vandar-api-key}',
            'Description' => null,
        ],
        'currencyType' => [
            'FriendlyName' => 'واحد پول',
            'Type' => 'dropdown',
            'Options' => [
                'Rial' => 'ریال',
                'Toman' => 'تومان',
            ],
            'Description' => null,
        ]
    ];
}

function vandar_link($params)
{
    $apiKey = $params['apiKey'];
    $currencyType = $params['currencyType'];

    $invoiceId = $params['invoiceid'];
    $description = $params['description'];
    $amount = $params['amount'];
    $currency = $params['currency'];

    $firstName = $params['clientdetails']['firstname'];
    $lastName = $params['clientdetails']['lastname'];
    $email = $params['clientdetails']['email'];
    $address1 = $params['clientdetails']['address1'];
    $address2 = $params['clientdetails']['address2'];
    $city = $params['clientdetails']['city'];
    $state = $params['clientdetails']['state'];
    $postCode = $params['clientdetails']['postcode'];
    $country = $params['clientdetails']['country'];
    $phone = $params['clientdetails']['phonenumber'];

    $systemUrl = $params['systemurl'];
    $returnUrl = $params['returnurl'];
    $langPayNow = $params['langpaynow'];
    $moduleName = $params['paymentmethod'];

    $paymentAmount = round($amount);
    if ($currencyType == 'Toman') {
        $paymentAmount = round($paymentAmount * 10);
    }

    $url = $systemUrl . '/modules/gateways/' . $moduleName . '/request.php';

    $postfields = [
        'api_key' => $apiKey,
        'invoice_id' => $invoiceId,
        'description' => $description,
        'paymentAmount' => $paymentAmount,
        'currency' => $currency,
        'first_name' => $firstName,
        'last_name' => $lastName,
        'email' => $email,
        'address1' => $address1,
        'address2' => $address2,
        'city' => $city,
        'state' => $state,
        'postcode' => $postCode,
        'country' => $country,
        'phone' => $phone,
        'callback_url' => $systemUrl.'/modules/gateways/callback/' . $moduleName . '.php',
        'return_url' => $returnUrl,
    ];

    $htmlOutput = '<form id="gateway" name="gateway" method="post" action="' . $url . '">';
    foreach ($postfields as $key => $value) {
        $htmlOutput .= '<input id="' . $key . '" name="' . $key . '" type="hidden" value="' . $value . '" />';
    }
    $htmlOutput .= '<input style="margin-top:0px" class="btn btn--green btn--xm fw-700 fz--small" type="submit" value="' . $langPayNow . '" />';
    $htmlOutput .= '</form>';

    return $htmlOutput;
}