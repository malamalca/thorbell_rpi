<?php
namespace App;

class App
{
    private static $instance = null;

    private $_vars = [];

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new App();
        }

        return self::$instance;
    }

    /**
     * Dispatch function
     *
     * @param string $controllerName Controller name
     * @param string $methodName Method name
     * @param array $vars Variables
     * @return void
     */
    public static function dispatch($controllerName, $methodName, $vars)
    {
        if (!self::isLoggedIn()) {
            $controllerName = 'Pages';
            $methodName = 'login';
        }

        $controllerClass = 'App\Controller\\' . $controllerName . 'Controller';
        $controller = new $controllerClass();

        call_user_func_array([$controller, $methodName], $vars);

        self::render($controllerName, $methodName);
    }

    /**
     * Render function
     *
     * @return void
     */
    private static function render($controllerName, $methodName)
    {

        $templatePath = TEMPLATES . $controllerName . DS;
        $templateFile = realpath($templatePath . $methodName . '.php');

        if (strpos($templateFile, $templatePath) !== 0 || strpos($templateFile, $templatePath) === false) {
            die(sprintf('Template "%s" does not exist', $templatePath . $methodName . '.php'));
        }

        $App = self::getInstance();
        extract($App->_vars);

        ob_start();
        include($templateFile);
        $contents = ob_get_contents();
        ob_end_clean();

        if (empty($title) && $title !== false) {
            $title = $controllerName . '::' . $methodName;
        }

        require(TEMPLATES . 'layouts' . DS . 'default.php');
    }

    /**
     * Set variable for view render
     * 
     * @param string|array $varName Variable name or array with variables
     * @param mixed $varValue Variable value
     */
    public static function set($varName, $varValue = null)
    {
        $App = self::getInstance();
        if (is_array($varName)) {
            foreach ($varName as $arrName => $arrValue) {
                $App->_vars[$arrName] = $arrValue;    
            }
        } else {
            $App->_vars[$varName] = $varValue;
        }
    }

    /**
     * Build url with specified base
     *
     * @param string|array $params Url params
     * @return string
     */
    public static function url($params)
    {
        $url_base = substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['SCRIPT_NAME'], '/webroot') + 1);

        return $url_base . substr($params, 1);
    }

    public static function redirect($dest)
    {
        header('Location: ' . self::url($dest));
        die;
    }

    public static function setFlash($msg, $code = 'success')
    {
        $_SESSION['flash.message'] = $msg;
        $_SESSION['flash.class'] = $code;
    }

    public static function flash()
    {
        if (!empty($_SESSION['flash.message'])) {
            $msg = $_SESSION['flash.message'];
            $code = $_SESSION['flash.class'];

            unset($_SESSION['flash.message']);
            unset($_SESSION['flash.class']);

            return '<div id="notification" class="' . htmlspecialchars($code) . '">' . htmlspecialchars($msg) . '</div>';
        }
    }

    public static function isLoggedIn()
    {
        return !empty($_SESSION['isLoggedIn']);
    }
}
