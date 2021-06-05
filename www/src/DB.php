<?php
namespace App;

use App\Configure;


/**
 * DB connnection
 */
class DB {
    private static $instance = null;

    /**
     * PDO instance
     * @var pdo 
     */
    protected $_pdo = null;

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new DB();
        }

        return self::$instance;
    }    

    /**
     * return in instance of the PDO object that connects to the SQLite database
     * @return \PDO
     */
    public static function connect()
    {
        $db = DB::getInstance();

        if ($db->_pdo == null) {
            $db->_pdo = new \PDO("sqlite:" . Configure::read('App.db'));
        }

        return $db->_pdo;
    }
}
