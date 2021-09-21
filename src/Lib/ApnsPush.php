<?php
namespace App\Lib;

use App\Core\Configure;
use Pushok\AuthProvider;
use Pushok\Client;
use Pushok\Notification;
use Pushok\Payload;
use Pushok\Payload\Alert;
use Ramsey\Uuid\Uuid;


class ApnsPush
{

    public static function sendNotification($message, \App\Model\Entity\Device $device, $doorbellName)
    {
        $options = [
            'key_id' => Configure::read('Apns.key_id'), // The Key ID obtained from Apple developer account
            'team_id' => Configure::read('Apns.team_id'), // The Team ID obtained from Apple developer account
            'app_bundle_id' => Configure::read('Apns.app_bundle_id'), // The bundle ID for app obtained from Apple developer account
            'private_key_path' => Configure::read('Apns.private_key_path'), // Path to private key
            'private_key_secret' => Configure::read('Apns.private_key_secret') // Private key secret
        ];
        
        // Be aware of thing that Token will stale after one hour, so you should generate it again.
        // Can be useful when trying to send pushes during long-running tasks
        $authProvider = AuthProvider\Token::create($options);
        
        $alert = Alert::create()->setTitle('Hello!');
        $alert = $alert->setBody('First push notification');
        
        $payload = Payload::create()->setAlert($alert);
        
        //set notification sound to default
        $payload->setSound('default');
        
        //add custom value to your notification, needs to be customized
        //$payload->setCustomValue('message', $message);
        $payload->setCustomValue('UUID', (Uuid::uuid4())->toString());
        $payload->setCustomValue('id', $device->id);
        $payload->setCustomValue('title', $doorbellName);

        $payload->setPushType('voip');
        
        $deviceTokens = [$device->token];
        
        $notifications = [];
        foreach ($deviceTokens as $deviceToken) {
            $notifications[] = new Notification($payload, $deviceToken);
        }
        
        // If you have issues with ssl-verification, you can temporarily disable it. Please see attached note.
        // Disable ssl verification
        // $client = new Client($authProvider, $production = false, [CURLOPT_SSL_VERIFYPEER=>false] );
        $client = new Client($authProvider, $production = false);
        $client->addNotifications($notifications);
        
        
        
        $responses = $client->push(); // returns an array of ApnsResponseInterface (one Response per Notification)
        
        foreach ($responses as $response) {
            // The device token
            echo $response->getDeviceToken() . PHP_EOL;
            // A canonical UUID that is the unique ID for the notification. E.g. 123e4567-e89b-12d3-a456-4266554400a0
            echo $response->getApnsId() . PHP_EOL;
            
            // Status code. E.g. 200 (Success), 410 (The device token is no longer active for the topic.)
            echo $response->getStatusCode() . PHP_EOL;
            // E.g. The device token is no longer active for the topic.
            echo $response->getReasonPhrase() . PHP_EOL;
            // E.g. Unregistered
            echo $response->getErrorReason() . PHP_EOL;
            // E.g. The device token is inactive for the specified topic.
            echo $response->getErrorDescription() . PHP_EOL;
            echo $response->get410Timestamp() . PHP_EOL;
        }
    }

    public static function sendNotification2($message, $device_token)
    {
        if(defined('CURL_HTTP_VERSION_2_0'))
        {
            //$alert = sprintf('{"aps":{"alert":"%s","sound":"default"}}', json_encode($message));
            $alert = sprintf('{"aps":{"alert":"%s","sound":"default"}, "data": {"apn_call_name": "Alex1", "apn_call_id": "Alex123"}}', json_encode($message));

            $url = Configure::read('Apns.url') . $device_token;

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $alert);
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'apns-push-type: voip',
                'apns-topic: ' . Configure::read('Apns.topic') . '.voip',
            ]);

            curl_setopt($ch, CURLOPT_SSLCERT, Configure::read('Apns.certificate'));
            curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'P12');
            curl_setopt($ch, CURLOPT_SSLCERTPASSWD, Configure::read('Apns.password'));
            curl_setopt($ch, CURLOPT_HEADER, 1);

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
