<?php
require dirname(dirname(__DIR__)) . '/config/bootstrap.php';

use Bluerhinos\phpMQTT;
use App\Model\Table\SettingsTable;
use App\Lib\Mqtt;

$ret = Mqtt::publish();

var_dump($ret);