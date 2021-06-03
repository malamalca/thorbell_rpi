<?php
namespace App;

class App
{
    /**
     * dispatch function
     *
     * @return void
     */
    public function dispatch($controllerName, $methodName, $vars)
    {
        $controllerClass = 'App\Controller\\' . $controllerName . 'Controller';
        $controller = new $controllerClass();

        $controller->$methodName($vars);

        $templatePath = TEMPLATES . $controllerName . DS;
        $templateFile = realpath($templatePath . $methodName . '.php');

        if (strpos($templateFile, $templatePath) !== 0 || strpos($templateFile, $templatePath) === false) {
            die(sprintf('Template "%s" does not exist', $methodName));
        }

        ob_start();
        include($templateFile);
        $contents = ob_get_contents();
        ob_end_clean();

        if (empty($title)) {
            $title = $controllerName . '::' . $methodName;
        }

        include(TEMPLATES . 'layouts' . DS . 'default.php');
    }

    /**
     * Build url with specified base
     *
     * @param string|array $params Url params
     * @return string
     */
    protected function url($params)
    {
        $url_base = substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['SCRIPT_NAME'], '/webroot') + 1);

        return $url_base . substr($params, 1);
    }
}
