<?php
//require dirname(dirname(dirname(__FILE__))) . '/vendor/autoload.php';


require dirname(dirname(__DIR__)) . '/config/bootstrap.php';

use PiPHP\GPIO\GPIO;
use PiPHP\GPIO\Pin\InputPinInterface;

define('PIN_BUTTON', 23);
define('SOUND_FILE', RESOURCES . 'doorbell.wav');
define('SNAPSHOT_URL', 'http://localhost:9090/stream/snapshot.jpeg');


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
        file_put_contents(PHOTOS . strftime('%Y%m%d%H%M%S', time()) . '.jpg', $imageData);
    }

    if ($value == 1) {
        shell_exec('aplay -q ' . SOUND_FILE);
    }

    // Returning false will make the watcher return false immediately
    return true;
});

// Watch for interrupts, timeout after 5000ms (5 seconds)
while ($interruptWatcher->watch(5000));
