<?php

function radomString(){
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $randomString = substr(str_shuffle($characters), 0, 10); // Change 10 to the desired length
    return  $randomString;
}

$clientId = 'BRN-0234-1680189385870';
$secretKey = 'SK-4ca6ijDaCzNSQyZjUZ4s';
$requestId = radomString();
$requestTemp = str_replace('##','T',date('Y-m-d##H:i:s#|#'));
$requestTemp = str_replace('#|#','Z',$requestTemp);
$requestTarget= '/checkout/v1/payment';

$json = [
    'order'=> [
      'amount'=> 80000,
      'invoice_number'=> 'OCKTY-'.$requestId,
      'currency'=> 'IDR',
      'callback_url'=> 'https=>//okecity.com/payment/mt/notify',
      'callback_url_cancel'=> 'https=>//okecity.com/payment/mt/notify',
      'language'=>'EN',
      'auto_redirect'=>true,
      'disable_retry_payment' =>true,
      'line_items'=> [
        [
            'id'=>'001',
            'name'=>'Fresh flowers',
            'quantity'=>1,
            'price'=>40000,
            'sku'=> 'FF01',
            'category'=> 'gift-and-flowers',
            'url'=> 'https=>//okecity.com/payment/mt/notify',
            'image_url'=>'https=>//okecity.com/payment/mt/notify',
            'type'=>'ABC'
        ],
        [
            'id'=>'002',
            'name'=>'T-shirt',
            'quantity'=>1,
            'price'=>40000,
            'sku'=> 'T01',
            'category'=> 'clothing',
            'url'=> 'https=>//okecity.com/payment/mt/notify',
            'image_url'=>'https=>//okecity.com/payment/mt/notify',
            'type'=>'ABC'
        ]
      ]
    ],
      'payment'=> [
          'payment_due_date'=> 60,
          'payment_method_types'=> [
              'VIRTUAL_ACCOUNT_BCA',
              'VIRTUAL_ACCOUNT_BANK_MANDIRI',
              'VIRTUAL_ACCOUNT_BANK_SYARIAH_MANDIRI',
              'VIRTUAL_ACCOUNT_DOKU',
              'VIRTUAL_ACCOUNT_BRI',
              'VIRTUAL_ACCOUNT_BNI',
              'VIRTUAL_ACCOUNT_BANK_PERMATA',
              'VIRTUAL_ACCOUNT_BANK_CIMB',
              'VIRTUAL_ACCOUNT_BANK_DANAMON',
              'ONLINE_TO_OFFLINE_ALFA',
              'QRIS'
          ]
      ],
      'customer'=>[
          'id'=>'JC-01',
          'name'=>'Heri',
          'last_name'=>'Setiyawan',
          'phone'=>'6287888808822',
          'email'=> 'heriaileen@gmail.com',
          'address'=>'taman setiabudi',
          'postcode'=>'120129',
          'state'=>'Jakarta',
          'city'=>'Jakarta Selatan',
          'country'=>'ID'
    ],
    'shipping_address'=>[
      'first_name'=>'Zolanda',
      'last_name'=>'Anggraeni',
      'address'=>'Jalan Teknologi Indonesia No. 25',
      'city'=>'Jakarta',
      'postal_code'=>'12960',
      'phone'=>'081513114262',
      'country_code'=>'IDN'
    ],
    'billing_address'=>[
      'first_name'=>'Zolanda',
      'last_name'=>'Anggraeni',
      'address'=>'Jalan Teknologi Indonesia No. 25',
      'city'=>'Jakarta',
      'postal_code'=>'12960',
      'phone'=>'081513114262',
      'country_code'=>'IDN'
    ]
    ];

    $json = json_encode($json);

    $digestValue = base64_encode(hash('sha256', $json, true));

    $componentSignature = "Client-Id:" . $clientId . "\n" . 
                      "Request-Id:" . $requestId . "\n" .
                      "Request-Timestamp:" . $requestTemp . "\n" . 
                      "Request-Target:" . $requestTarget . "\n" .
                      "Digest:" . $digestValue;

                      $signature = base64_encode(hash_hmac('sha256', $componentSignature, $secretKey, true));

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.doku.com/checkout/v1/payment',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => $json,
  CURLOPT_HTTPHEADER => array(
    'Client-Id:'.$clientId,
    'Request-Id:'.$requestId,
    'Request-Timestamp:'.$requestTemp,
    'Signature: HMACSHA256='.$signature,
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
