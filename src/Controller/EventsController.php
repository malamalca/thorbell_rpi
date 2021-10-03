<?php
declare(strict_types=1);

namespace App\Controller;

use App\Core\App;

class EventsController
{
    /**
     * Show latest rings
     *
     * @return void
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
