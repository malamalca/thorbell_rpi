<?php
namespace App\Lib;

use App\Core\Configure;

class ApnsPush
{

    public static function sendNotification($message, $device_token)
    {
        if(defined('CURL_HTTP_VERSION_2_0'))
        {
            $alert = sprintf('{"aps":{"alert":"%s","sound":"default"}}', json_encode($message));

            $url = Configure::read('Apns.url') . $device_token;

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $alert);
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['apns-topic: ' . Configure::read('Apns.topic')]);

            curl_setopt($ch, CURLOPT_SSLCERT, Configure::read('Apns.certificate'));
            curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'P12');
            curl_setopt($ch, CURLOPT_SSLCERTPASSWD, Configure::read('Apns.password'));

            //curl_setopt($ch, CURLOPT_SSLCERT, CERTS . 'aps.pem');
            //curl_setopt($ch, CURLOPT_SSLKEY, CERTS . 'ios_app.key');
            //curl_setopt($ch, CURLOPT_SSLCERTPASSWD, '');
            //curl_setopt($ch, CURLOPT_SSLKEYPASSWD, '');

            $response = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if ($response === false) {
                echo 'Curl error: ' . curl_error($ch);
            } else {
                var_dump($response);
                var_dump($httpcode);
            }
        }
    }
}
