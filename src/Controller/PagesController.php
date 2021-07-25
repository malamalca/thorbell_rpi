<?php
namespace App\Controller;

use App\Core\App;
use App\Core\Configure;
use App\Model\Table\DevicesTable;
use App\Model\Table\SettingsTable;

class PagesController
{
    /**
     * Dashboard action
     *
     * @return void
     */
    public function home()
    {
    }

    /**
     * Settings action
     *
     * @return void
     */
    public function settings()
    {
        $SettingsTable = new SettingsTable();
        $name = $SettingsTable->get('name', Configure::read('App.defaultName'));

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!empty($_POST['name'])) {
                $name->value = $_POST['name'];
                $SettingsTable->validateAndSave($name);
            }

            if ($name->hasErrors) {
                App::setFlash('There are some errors. Please check your input.', 'error');
            } else {
                App::setFlash('Settings have been saved.');
                App::redirect('/');
            }
        }

        App::set(compact('name'));
    }

    /**
     * Change user password
     * 
     * @return void
     */
    public function changePassword()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['password'])) {
            $checkPasswd = $this->getAdminPassword();

            if (password_verify($_POST['old_password'], $checkPasswd)) {
                if ($_POST['repeat_password'] === $_POST['password']) {

                    $SettingsTable = new SettingsTable();
                    $setting = $SettingsTable->get('passwd');
                    if ($setting === null) {
                        $setting = $SettingsTable->newEntity(['id' => 'passwd']);
                    }
                    $setting->value = password_hash($_POST['password'], PASSWORD_DEFAULT);

                    if ($SettingsTable->save($setting)) {
                        App::setFlash('Admin password has been changed.');
                        App::redirect('/');
                    } else {
                        App::setFlash('Password change failed.', 'error');
                    }
                } else {
                    App::setFlash('New passwords are not equal.', 'error');
                }
            } else {
                App::setFlash('Old password is invalid.', 'error');
            }
        }
    }

    /**
     * User login function
     * 
     * @return void
     */
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['password'])) {
            $checkPasswd = $this->getAdminPassword();

            if (password_verify($_POST['password'], $checkPasswd)) {
                $_SESSION['isLoggedIn'] = true;
                App::redirect('/');
            }

            App::setFlash('Invalid username or password', 'error');
        }
    }


    /**
     * Logs user out.
     * 
     * @return void
     */
    public function logout()
    {
        unset($_SESSION['isLoggedIn']);

        App::redirect('/');
    }

    
    /**
     * Fetches password hash from settings table or returns default password
     * 
     * @return string
     */
    private function getAdminPassword()
    {
        $checkPasswd = (new SettingsTable())->get('passwd');
        if ($checkPasswd === null) {
            $checkPasswd = password_hash(Configure::read('App.defaultPassword'), PASSWORD_DEFAULT);
        } else {
            $checkPasswd = $checkPasswd->value;
        }

        return $checkPasswd;
    }
}
