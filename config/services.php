<?php

declare(strict_types=1);

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$container = new \League\Container\Container();

$container->delegate(new \League\Container\ReflectionContainer(true));

# parameters for application config
$container->add('basePath', new \League\Container\Argument\Literal\StringArgument(BASE_PATH));

$routes = include BASE_PATH . '/routes/web.php';
$appEnv = $_SERVER['APP_ENV'];
$templatesPath = BASE_PATH . '/templates';

// $container->add('flashmsg', new \Plasticbrain\FlashMessages\FlashMessages());

// $container->add(\App\Controllers\AbstractController::class);
// $container->inflector(\App\Controllers\AbstractController::class)
//     ->invokeMethod('setContainer', [$container]);

return $container;
