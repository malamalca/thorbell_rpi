<?php
namespace App\Controller;

use App\Core\App;
use App\Core\Configure;

class EventsController {
    /**
     * Show latest rings
     */
    public function index()
    {
        $events = [];
        foreach (new \DirectoryIterator(PHOTOS) as $fileInfo) {
            if ($fileInfo->isDot()) {
                continue;
            }

            echo $fileInfo->getFilename() . "<br>\n";
        }

        App::set(compact('events'));
    }

    /**
     * Delete ring.
     *
     * @param string $id Device id
     * @return void
     */
    public function delete($id)
    {
        
    }
}
