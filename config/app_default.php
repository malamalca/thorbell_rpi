<?php
return [
    'debug' => false,

    'App' => [
        'namespace' => 'App',
        'encoding' => 'UTF-8',
        'baseUrl' => '/www/thorbell_rpi',
        'db' => ROOT . DS . 'db' . DS . 'thorbell.sqlite',
        'defaultPassword' => 'admin',
    ],

    'Apns' => [
        'url' => 'https://api.development.push.apple.com/3/device/',
        'certificate' => CERTS . 'ios_app.pfx', // p12 certificate
        'password' => 'testtest',
        'topic' => 'thorbell.malamalca.com',
    ]
];
