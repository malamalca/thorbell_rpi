<?php
namespace App\Model\Table;

use App\DB;
use App\Model\Entity\Device;

class DevicesTable extends Table
{
    public $entityName = '\App\Model\Entity\Device';
    public $tableName = 'devices';
    public $fieldList = ['id', 'title', 'token'];

    /**
     * Returns all devices
     *
     * @return array
     */
    public function getDevices()
    {
        $pdo = DB::getInstance()->connect();

        $stmt = $pdo->query('SELECT id, title, token FROM devices ORDER BY title');

        $devices = [];
        if ($stmt) {
            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $devices[] = $this->newEntity($row);
            }
        }

        return $devices;
    }

}
