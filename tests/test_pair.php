<?php
//
// Test pair with device. Run from console. Add pairing id as parameter
//

if (empty($argv[1])) {
    die('No pairing id');
}

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL,"http://localhost/thorbell_rpi/devices/pair");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'id' => $argv[1],
    'title' => 'Mihas Iphone',
    'token' => 'fdsf',
]));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'X-Requested-With: XMLHttpRequest',
]);

// Receive server response ...
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close ($ch);

var_dump($response);
var_dump($httpcode);
