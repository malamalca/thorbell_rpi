<?php
namespace App\Model\Table;

use App\Core\DB;
use App\Core\Table;
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

        $stmt = $pdo->query('SELECT id, title, token FROM devices WHERE token IS NOT NULL ORDER BY title');

        $devices = [];
        if ($stmt) {
            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $devices[] = $this->newEntity($row);
            }
        }

        return $devices;
    }

    public function generatePairingDevice()
    {
        // clear all other temporary pairing devices
        $pdo = DB::getInstance()->connect();
        $pdo->exec('DELETE FROM devices WHERE token IS NULL');

        $device = new Device();
        $device->id = substr(uniqid(), -5);

        if ($this->save($device, ['id'])) {
            return $device;
        }

        return false;
    }

}
