<?php

define('BASE_PATH', dirname(__DIR__));

use App\Twig;
use Dotenv\Dotenv;
use Tracy\Debugger;
use FastRoute\Dispatcher;
use FastRoute\HttpException;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;
use FastRoute\HttpRequestMethodException;

require_once BASE_PATH . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

Debugger::enable();

$dispatcher = simpleDispatcher(function (RouteCollector $routeCollector) {
    $routes = include BASE_PATH . '/routes/web.php';
    
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
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        list($class, $method) = explode('#', $handler, 2);
        call_user_func_array([new $class(), $method], $vars);
        break;
    case Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        http_response_code(405);
        header('HTTP/1.1 405 Not supported');
        break;
    default:
        http_response_code(404);
        header('HTTP/1.1 404 Not Found');
        break;
}
