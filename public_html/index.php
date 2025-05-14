<?php

define('BASE_PATH', dirname(__DIR__));

use App\Twig;
use Dotenv\Dotenv;
use Tracy\Debugger;
use App\SessionManager;
use FastRoute\Dispatcher;
use FastRoute\HttpException;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;
use FastRoute\HttpRequestMethodException;

require_once BASE_PATH . '/vendor/autoload.php';

session_start();

Debugger::enable();

$container = require BASE_PATH . '/config/services.php';

$dispatcher = simpleDispatcher(function (RouteCollector $routeCollector) use ($routes) {
    foreach ($routes as $route) {
        $routeCollector->addRoute(...$route);
    }
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

$urlparts = parse_url($uri);

if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
// NB: $uri can't be decode here as it is a full url
// $uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case Dispatcher::FOUND:
        [$class, $method] = $routeInfo[1];
        $parms = $routeInfo[2];

        // This step is critical to ensure the container is available in the controller
        // It changes the class name in the route definition into an instance of the class.
        $controller = $container->get($class);

        call_user_func_array([$controller, $method], $parms);
        break;
    case Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        http_response_code(405);
        header('HTTP/1.1 405 Not supported');
        break;
    default:
        http_response_code(404);
        dd($routeInfo, $_SERVER['REQUEST_URI'], 'HTTP/1.1 404 Not Found');
        header('HTTP/1.1 404 Not Found');
        break;
}
