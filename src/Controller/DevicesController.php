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
     * Index action
     *
     * @return void
     */
    public function index()
    {
        $devices = $this->DevicesTable->getDevices();
        App::set('devices', $devices);
    }

    /**
     * Add action
     *
     * @return void
     */
    public function add()
    {
        if (empty($_SESSION['pairDevice'])) {
            $_SESSION['pairDevice'] = $this->DevicesTable->generatePairingDevice();
        }

        App::set('pairDevice', $_SESSION['pairDevice']);
        
    }

    public function pair()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $device = $this->DevicesTable->get($_POST['id']);
            if (!empty($device) && !empty($_POST['token']) && !empty($_POST['title'])) {
                $device->token = $_POST['token'];
                $device->title = $_POST['title'];

                if ($this->DevicesTable->save($device)) {
                    unset($_SESSION['pairDevice']);

                    App::setFlash('Success');
                    App::redirect('/');
                }
            }
            
        }
        App::setFlash('Error', 'error');
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
