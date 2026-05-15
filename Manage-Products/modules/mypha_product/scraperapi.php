<?php
$asin = $_REQUEST['asin'];
$apikey = '99e033dcd64f860968ca9592de426e65';

$scrapapiUrl = "https://api.scraperapi.com/structured/amazon/product?api_key={$apikey}&asin={$asin}&country_code=in&tld=in";

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => $scrapapiUrl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json'
    ),
));

$response = curl_exec($curl);
curl_close($curl);

// Decode the JSON response
$data = json_decode($response, true);
unset($data['reviews']);
header('Content-Type: application/json');
echo json_encode($data, JSON_PRETTY_PRINT);
?>
