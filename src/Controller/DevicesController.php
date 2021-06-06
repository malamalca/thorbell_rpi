<?php
namespace App\Controller;

use App\Core\App;
use App\Core\Configure;
use App\Model\Table\DevicesTable;

class DevicesController {
    private $DevicesTable = null;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->DevicesTable = new DevicesTable();
    }

    /**
     * Add action
     *
     * @return void
     */
    public function add()
    {
        $pairCode = $_SESSION['pairCode'] ?? substr(uniqid(), -5);
        if (empty($_SESSION['pairCode'])) {
            $_SESSION['pairCode'] = $pairCode;
        }

        App::set('pairCode', $pairCode);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!empty($_POST['id']) && $_POST['id'] == $pairCode) {
                $device = $this->DevicesTable->newEntity($_POST);

                if ($this->DevicesTable->save($device)) {
                    unset($_SESSION['pairCode']);
                    App::setFlash('Success');
                    App::redirect('/');
                }
            }
            App::setFlash('Error', 'error');
        }
    }

    /**
     * Delete device.
     *
     * @param string $id Device id
     * @return void
     */
    public function delete($id)
    {
        $device = $this->DevicesTable->get($id);
        if ($this->DevicesTable->delete($device)) {
            App::setFlash('Device has been deleted.');
            App::redirect('/');
        } else {
            App::setFlash('Error Deleteing Device', 'error');
        }
    }
}
