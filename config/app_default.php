<?php

return [
    'debug' => false,

    'App' => [
        'namespace' => 'App',
        'encoding' => 'UTF-8',
        'baseUrl' => '/www/thorbell_rpi',
        'db' => ROOT . DS . 'db' . DS . 'thorbell.sqlite',
        'defaultPassword' => 'admin',
        'defaultName' => 'Thorbell Console',
    ],

    'Log' => [
        'debug' => [
            'className' => 'StreamHandler',
            'file' => 'php://stderr', //LOGS . 'debug.log',
            'level' => \Monolog\Logger::DEBUG,
        ],
    ],

    'Doorbell' => [
        'gpio_pin' => 23,
        'sound_file' => RESOURCES . 'doorbell.wav',
        'snapshot_url' => 'http://localhost:9090/stream/snapshot.jpeg',
    ],

    'Apns' => [
        'key_id' => 'XXXXXXXX',                         // The Key ID obtained from Apple developer account
        'team_id' => 'XXXXXXXX',                        // The Team ID obtained from Apple developer account
        'app_bundle_id' => 'com.example.app',           // The bundle ID for app obtained from Apple developer account
        'private_key_path' => CERTS . '/AuthKey.p8',    // Path to private key
        'private_key_secret' => null,                   // Private key secret
    ],

    'Mqtt' => [
        'port' => 1883,                                 // default port when not specified in settings
        'mdns_name' => 'thorbell_door',                 // default name of mqttclient when not specified in settings
        'topic' => 'thorbell',
        'message_ring' => 'thorbellRing',
    ],
];
