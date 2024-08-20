<?php

declare(strict_types=1);

namespace App;

use \App\TwigStringUtilities;
use \Twig\Environment;
use \Twig\Loader\FilesystemLoader;
use \Twig\Extension\DebugExtension;

class Twig
{
    /**
     * Render a view template using Twig
     *
     * @param string $template  The template file
     * @param array $args       Associative array of data to display in the view (optional)
     * @return mixed|void
     */
    public static function render($template, $args = [], $return = false)
    {
        static $twig = null;

        if ($twig === null) {
            $filesystemLoader = new FilesystemLoader(__DIR__ . '/../templates');
            $twig = new Environment(
                $filesystemLoader,
                [
                    'cache'            => false,
                    'auto_reload'      => true,
                    'debug'            => $_ENV['environment'] === 'dev',
                    'strict_variables' => false,
                ]
            );
            $twig->addExtension(new TwigUtilities());
            $twig->addExtension(new DebugExtension());
        }

        if ($return) {
            return $twig->render($template, $args);
        }

        echo $twig->render($template, $args);
    }
}
