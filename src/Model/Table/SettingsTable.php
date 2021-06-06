<?php
namespace App\Model\Table;

use App\Core\DB;
use App\Core\Table;

class SettingsTable extends Table
{
    public $entityName = '\App\Model\Entity\Setting';
    public $tableName = 'settings';
    public $fieldList = ['id', 'value'];
}
