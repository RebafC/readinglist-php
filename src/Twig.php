<?php

declare(strict_types=1);

namespace App;

use App\TwigStringUtilities;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\Extension\DebugExtension;

class Twig
{
    public static $twig = null;
    /**
     * Render a view template using Twig
     *
     * @param string $template  The template file
     * @param array $args       Associative array of data to display in the view (optional)
     * @return mixed|void
     */
    public static function render(string $template, array $args = [], $return = false): void
    {
        if (self::$twig === null) {
            self::$twig = self::init();
        }

        echo self::$twig->render($template, $args);
    }

    /**
     * Return the view template using Twig
     */
    public static function fetch(string $template, array $args = []): string
    {
        if (self::$twig === null) {
            self::$twig = self::init();
        }

        return self::$twig->render($template, $args);
    }


    public static function addGlobalVar($key, $value)
    {
        if (self::$twig === null) {
            self::$twig = self::init();
        }

        return self::$twig->addGlobal($key, $value);
    }

    private static function init()
    {
        $templatedir = __DIR__ . '/../Templates';
        $filesystemLoader = new FilesystemLoader(__DIR__ . '/../templates');
        $twig = new Environment(
            $filesystemLoader,
            [
                'cache'            => false,
                'auto_reload'      => true,
                'debug'            => $_ENV['APP_ENV'] === 'dev',
                'strict_variables' => false,
            ]
        );
        $twig->addExtension(new TwigUtilities());
        $twig->addExtension(new DebugExtension());

        return $twig;
    }
}
