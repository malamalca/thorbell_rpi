<?php
namespace App\Model\Table;

use App\DB;

class SettingsTable extends Table
{
    public $entityName = '\App\Model\Entity\Setting';
    public $tableName = 'settings';
    public $fieldList = ['id', 'value'];
}
