<?php
//error_reporting(E_ALL);
//ini_set('display_errors', true);

require dirname(__DIR__) . '/config/bootstrap.php';

use App\Core\App;
use App\Core\Configure;

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->addRoute(['GET', 'POST'], '/', ['Pages', 'home']);
    $r->addRoute(['GET', 'POST'], '/settings', ['Pages', 'settings']);


    $r->addRoute('GET', '/logout', ['Pages', 'logout']);
    $r->addRoute(['GET', 'POST'], '/login', ['Pages', 'login']);
    $r->addRoute(['GET', 'POST'], '/changepasswd', ['Pages', 'changePassword']);
    $r->addRoute(['GET', 'POST'], '/resetpasswd', ['Pages', 'resetPassword']);
    
    $r->addRoute(['GET', 'POST'], '/devices', ['Devices', 'index']);
    $r->addRoute(['GET', 'POST'], '/devices/add', ['Devices', 'add']);
    $r->addRoute('GET', '/devices/delete/{id}', ['Devices', 'delete']);
    $r->addRoute(['GET', 'POST'], '/devices/pair', ['Devices', 'pair']);

    $r->addRoute('GET', '/events', ['Events', 'index']);
    // {id} must be a number (\d+)
    //$r->addRoute('GET', '/user/{id:\d+}', 'get_user_handler');
    // The /{title} suffix is optional
    //$r->addRoute('GET', '/articles/{id:\d+}[/{title}]', 'get_article_handler');
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
if (Configure::read('App.baseUrl') == '') {
    $uri = $_SERVER['REQUEST_URI'];
} else {
    $uri = substr(
        $_SERVER['REQUEST_URI'], 
        strpos($_SERVER['REQUEST_URI'], Configure::read('App.baseUrl')) + strlen(Configure::read('App.baseUrl'))
    );
}

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        header("HTTP/1.0 404 Not Found");
        echo "Route Not Found.\n";
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        header("HTTP/1.0 405 Method Not Allowed");
        echo "Route Not Allowed.\n";
        break;
    case FastRoute\Dispatcher::FOUND:
        $className = $routeInfo[1][0];
        $method = $routeInfo[1][1];

        App::dispatch($className, $method, $routeInfo[2]);
        break;
}
