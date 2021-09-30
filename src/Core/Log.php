<?php

namespace App\Core;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Log {
    // Hold the class instance.
    private static $instance = null;
    private static $logger = null;

    // The constructor is private
    // to prevent initiation with outer code.
    private function __construct()
    {
        // The expensive process (e.g.,db connection) goes here.
        self::$logger = new Logger('app');

        $handlers = Configure::read('Log');

        foreach ((array)$handlers as $handler) {
            $class = reset($handler);
            $params = array_values(array_slice($handler, 1));

            self::$logger->pushHandler(new $class(...$params));
        }
    }

    // The object is created from within the class itself
    // only if the class has no instance.
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Log();
        }

        return self::$instance;
    }

    public static function getLogger()
    {
        if (self::$instance == null) {
            self::$instance = new Log();
        }

        return self::$logger;
    }

    public static function info() {
        return call_user_func_array([self::getLogger(), 'info'], func_get_args());
    }

    public static function warn() {
        return call_user_func_array([self::getLogger(), 'warn'], func_get_args());
    }

    public static function error() {
        return call_user_func_array([self::getLogger(), 'error'], func_get_args());
    }

    public static function critical() {
        return call_user_func_array([self::getLogger(), 'critical'], func_get_args());
    }
}