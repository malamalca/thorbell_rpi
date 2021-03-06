<?php
declare(strict_types=1);

namespace App\Controller;

use App\Core\App;
use App\Core\Configure;
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
        $mqtt_server = $SettingsTable->get('mqtt_server');
        $mqtt_port = $SettingsTable->get('mqtt_port');
        $mqtt_username = $SettingsTable->get('mqtt_username');
        $mqtt_password = $SettingsTable->get('mqtt_password');
        $mqtt_mdnsname = $SettingsTable->get('mqtt_mdnsname', Configure::read('Mqtt.mdns_name'));

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $SettingsTable->patchAndSave($name);
            $SettingsTable->patchAndSave($mqtt_server);
            $SettingsTable->patchAndSave($mqtt_port);
            $SettingsTable->patchAndSave($mqtt_username);
            $SettingsTable->patchAndSave($mqtt_password);
            $SettingsTable->patchAndSave($mqtt_mdnsname);

            if ($name->hasErrors || $mqtt_server->hasErrors) {
                App::setFlash('There are some errors. Please check your input.', 'error');
            } else {
                App::setFlash('Settings have been saved.');
                App::redirect('/');
            }
        }

        App::set(compact('name', 'mqtt_server', 'mqtt_port', 'mqtt_username', 'mqtt_password', 'mqtt_mdnsname'));
    }

    /**
     * Change user password
     *
     * @return void
     */
    public function changePassword()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['password']) && !empty($_POST['old_password'])) {
            if ($_POST['repeat_password'] === $_POST['password']) {
                if (pam_chpass('thorbell', $_POST['old_password'], $_POST['password'])) {
                    App::setFlash('Admin password has been changed.');
                    App::redirect('/');
                } else {
                    App::setFlash('Password change failed.', 'error');
                }
            } else {
                App::setFlash('Entered passwords are not equal.', 'error');
            }

            /*$checkPasswd = $this->getAdminPassword();

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
            }*/
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
            /*$checkPasswd = $this->getAdminPassword();

            if (password_verify($_POST['password'], $checkPasswd)) {
                $_SESSION['isLoggedIn'] = true;
                App::redirect('/');
            }*/

            if (pam_auth('thorbell', $_POST['password'], $errMsg)) {
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
     * Video relay
     *
     * @return void
     */
    public function video()
    {
        ob_end_clean();

        $url = 'http://192.168.88.9:9090/stream/video.mjpeg';
        $buffersize = 1024 * 1024;

        ini_set('memory_limit', '1024M');
        set_time_limit(3600);
        ob_start();

        if (isset($_SERVER['HTTP_RANGE'])) {
            $opts['http']['header'] = 'Range: ' . $_SERVER['HTTP_RANGE'];
        }

        $opts['http']['method'] = 'HEAD';
        $conh = stream_context_create($opts);
        $opts['http']['method'] = 'GET';
        $cong = stream_context_create($opts);
        $out[] = file_get_contents($url, false, $conh);
        $out[] = $http_response_header;

        ob_end_clean();

        array_map('header', $http_response_header);
        readfile($url, false, $cong);
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

    /**
     * Reboots system
     *
     * @return void
     */
    public function reboot()
    {
        exec('sudo reboot');
        App::redirect('/');
    }
}
