<?php

namespace App;

use Twig\Environment;
use Twig\TwigFunction;
use Twig\Loader\FilesystemLoader;

class TPL
{
    private static $twig;

    private static function init()
    {
        if (self::$twig !== null) {
            return;
        }

        $loader = new FilesystemLoader(['..\templates']);
        self::$twig = new Environment($loader, [
            'cache' => false,
            'debug' => true,
            'paths' => [
                'public_html/' => 'assets',
            ],
        ]);
        self::$twig->addExtension(new \Twig\Extension\DebugExtension());

        return self::$twig;
    }

    public static function addGlobalVar($key, $value)
    {
        if (!isset(self::$template)) {
            self::init();
        }

        return self::$twig->addGlobal($key, $value);
    }

    public static function render($template, $params)
    {
        if (!isset(self::$template)) {
            self::init();
        }

        return self::$twig->render($template, $params);
    }
}
