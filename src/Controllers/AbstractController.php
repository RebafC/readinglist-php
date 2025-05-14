<?php

namespace App\Controllers;

use App\Twig;
use Psr\Container\ContainerInterface;

abstract class AbstractController
{
    protected ?ContainerInterface $container = null;

    public function __construct() 
    {
        Twig::addGlobalVar('title', $_ENV['SYSTEM']);
    }

    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }

    public function getFlashMessage()
    {
        $flash = $this->container->get('flashmsg');
        
        return $flash->display(
            [
                $flash::SUCCESS,
                $flash::INFO,
                $flash::ERROR,
                $flash::WARNING
            ],
            false // return not echo
        );
    }
}
