<?php

require dirname(__DIR__) . '/config/bootstrap.php';

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/', ['Lidar', 'input']);
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = substr($_SERVER['REQUEST_URI'], strpos($_SERVER['SCRIPT_NAME'], '/cam/webroot/index.php'));

var_dump($uri);
die;

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        header("HTTP/1.0 404 Not Found");
        echo "Not Found.\n";
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        header("HTTP/1.0 405 Method Not Allowed");
        echo "Not Allowed.\n";
        break;
    case FastRoute\Dispatcher::FOUND:
        $className = $routeInfo[1][0];
        $method = $routeInfo[1][1];
        $vars = $routeInfo[2];

        $appController = new \App\App();
        $appController->dispatch($className, $method, $vars);
        break;
}