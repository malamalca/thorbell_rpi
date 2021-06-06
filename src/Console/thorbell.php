<?php
require dirname(dirname(__FILE__)) . '/vendor/autoload.php';

use PiPHP\GPIO\GPIO;
use PiPHP\GPIO\Pin\InputPinInterface;

define('PIN_BUTTON', 23);
define('SOUND_FILE', dirname(dirname(__FILE__)) . '/resources/doorbell.wav');
define('SNAPSHOT_URL', 'https://localhost:9090/stream/snapshot.jpeg');
define('SNAPSHOT_TARGET_DIR', dirname(dirname(__FILE__)) . '/photos/');

function sendApnPush()
{
    // GuzzleHttp\Client

    $headers = [
        "apns-topic: com.example.exampleapp",
        "apns-push-type: alert",
        "Content-Type: application/x-www-form-urlencoded",
    ];

    $certificate_file = "iosCertificates/apple-push-dev-certificate-with-key.pem";

    $payloadArray['aps'] = [
        'alert' => [
            'title' => "Test Push Notification",
            'body' => "Ohhh yeah working",
        ],
        'sound' => 'default',
        'badge' => 1,

    ];

    $data = json_encode($payloadArray);

    $client = new Client();

    $response = $client->post($url, [
        'headers' => $headers,
        'cert' => $certificate_file,
        'curl' => [
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2_0,
        ],
        'body' => $data,
    ]);
}

// Create a GPIO object
$gpio = new GPIO();

// Retrieve pin 18 and configure it as an input pin
$pin = $gpio->getInputPin(PIN_BUTTON);

// Configure interrupts for both rising and falling edges
$pin->setEdge(InputPinInterface::EDGE_RISING);

// Create an interrupt watcher
$interruptWatcher = $gpio->createWatcher();

// Register a callback to be triggered on pin interrupts
$interruptWatcher->register($pin, function (InputPinInterface $pin, $value) {
    echo 'Pin ' . $pin->getNumber() . ' changed to: ' . $value . PHP_EOL;

    $context = ['http' => [ 'method' => 'GET' ], 'ssl' => [ 'verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true]];
    $imageData = file_get_contents(SNAPSHOT_URL, false, stream_context_create($context));

    if ($imageData) {
        file_put_contents(SNAPSHOT_TARGET_DIR . strftime('%Y%m%d%H%M%S', time()) . '.jpg', $imageData);
    }

    if ($value == 1) {
        //shell_exec('aplay -q ' . SOUND_FILE);
    }

    // Returning false will make the watcher return false immediately
    return true;
});

// Watch for interrupts, timeout after 5000ms (5 seconds)
while ($interruptWatcher->watch(5000));
