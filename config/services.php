<?php

declare(strict_types=1);

date_default_timezone_set('Australia/Sydney');

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__), '.env.sqlite');
// $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__), '.env.mysql');
$dotenv->load();

$container = new \League\Container\Container();

$container->delegate(new \League\Container\ReflectionContainer(true));

# parameters for application config
$basePath = dirname(__DIR__);
$container->add('basePath', new \League\Container\Argument\Literal\StringArgument($basePath));

$routes = include $basePath . '/routes/web.php';
$appEnv = $_SERVER['APP_ENV'];
$templatesPath = $basePath . '/templates';

$container->add('flashmsg', new \Plasticbrain\FlashMessages\FlashMessages());
$msg = $container->get('flashmsg');
$msg->setMsgCssClass('alert');
$msg->setCssClassMap([
    $msg::INFO    => 'alert-info',
    $msg::SUCCESS => 'alert-success',
    $msg::WARNING => 'alert-warning',
    $msg::ERROR   => 'alert-error',
]);
$msg->setMsgWrapper('<div class="%s" role="alert">%s</div>');

$container->add(\App\Controllers\AbstractController::class);
$container->inflector(\App\Controllers\AbstractController::class)
    ->invokeMethod('setContainer', [$container]);

return $container;
