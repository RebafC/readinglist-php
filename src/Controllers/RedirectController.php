<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Twig;
use App\Controllers\AbstractController;
use App\Repositories\BloglistRepository;

class RedirectController extends AbstractController
{
    private $twigvars = [];

    public function redirect()
    {
        $blrepo = new BloglistRepository();
        $url = $blrepo->getUrlFromId();

        if ($url !== []) {
            header("location: {$url}");
            die();
        }
        echo('Error fetching url');
    }
}
