<?php
namespace App\Model\Table;

use App\Core\DB;
use App\Core\Table;

class EventsTable extends Table
{
    public $entityName = '\App\Model\Entity\Event';
    public $tableName = 'events';
    public $fieldList = ['id', 'kind', 'datestamp'];

    public function validate($event) {
        $event->hasErrors = false;

        $event->hasErrors = empty($event->id);

        return !$event->hasErrors;
    }

}
