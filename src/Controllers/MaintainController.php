<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Twig;
use App\Controllers\AbstractController;
use App\Repositories\BloglistRepository;
use Plasticbrain\FlashMessages\FlashMessages;

class MaintainController extends AbstractController
{
    private $twigvars = [];

    public function verifyadd()
    {
        $url = str_replace('\\', '/', $_GET['page']);
        $url = rawurldecode($url);

        $title = $_GET['title'] ? str_replace('\\', '/', $_GET['title']) : '';

        $twigvars = [
            'pageurl' => $url,
            'pagetitle' => $title,
            'flashmsg' => $this->getFlashMessage(),
        ];
        
        Twig::render('/add.html.twig', $twigvars);
    }

    public function add()
    {
        $blrepo = new BloglistRepository();
        $blrepo->add($_POST['url'], $_POST['title']);
        
        $flash = $this->container->get('flashmsg');
        $flash->info(
            "Added item to reading list. Please stand by while I send you back.",
            null,
            true
        );
        /*
        "window.location.reload();" causes "To display this page, Firefox must send information
        that will repeat any action ...." alert. "window.location=window.location" doesn't seem to.
        */
        $twigvars = [
            'prior' => $_SERVER['HTTP_REFERER'],
            'flashmsg' => $flash->display([], false),
            'redirect' => <<<SCRIPT
        <script>setTimeout(() => {
            history.go(-2);window.location=window.location;
        }, 3000);
        </script>
SCRIPT,
        ];

        Twig::render('/addconfirm.html.twig', $twigvars);
    }

    public function delete($id)
    {
        $blrepo = new BloglistRepository();
        $blrepo->delete($id);

        $this->container->get('flashmsg')->success("Item '$id' deleted", '/', true);
    }

    public function remove($id)
    {
        # todo delete from dbase
        # but need a form to confirm

        // $blrepo = new BloglistRepository();
        // $blrepo->delete($id);

        $this->container->get('flashmsg')->warning(
            "I was going to permanently delete item '$id' would be deleted, but I got distracted.",
            '/showdeleted',
            true
        );
    }

    public function activate($id)
    {
        $blrepo = new BloglistRepository();
        $blrepo->activate($id);

        $this->container->get('flashmsg')->success("Item '$id' restored", '/showdeleted', true);
    }
}
