<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Twig;
use App\Controllers\AbstractController;
use App\Repositories\BloglistRepository;
use Plasticbrain\FlashMessages\FlashMessages;

class ListController extends AbstractController
{
    private $twigvars = [];

    public function index()
    {
        $blrepo = new BloglistRepository();
        $rows = $blrepo->getAllNotDeleted();
        
        foreach ($rows as &$r) {
            $r['title'] = urldecode($r['title']);
            $r['created'] = urldecode($r['localtime']);
        }

        $twigvars = [
            'rows' => $rows,
            'flashmsg' => $this->getFlashMessage(),
        ];

        Twig::render('list.html.twig', $twigvars);
    }

    public function showdeleted()
    {   
        $blrepo = new BloglistRepository();
        $rows = $blrepo->getAllDeleted();

        foreach ($rows as &$r) {
            $r['title'] = urldecode($r['title']);
            $r['created'] = urldecode($r['localadd']);
            $r['deleted'] = urldecode($r['localdel']);
        }

        $twigvars = [
            'rows' => $rows,
            'showdel' => true,
            'flashmsg' => $this->getFlashMessage(),
        ];

        Twig::render('list.html.twig', $twigvars);
    }

    public function showXml()
    {
        $xml = new \SimpleXMLElement('<rss version="2.0"></rss>');

        $xml->addChild('channel');
        $xml->channel->addChild('title', $_ENV['SYSTEM']);
        $xml->channel->addChild('link', $_ENV['LINKTOSELF']);
        $xml->channel->addChild('description', 'Contains saved urls');
        $xml->channel->addChild('pubDate', date(DATE_RSS));

        $blrepo = new BloglistRepository();
        $rows = $blrepo->getAllNotDeleted();

        foreach ($rows as $row) {
            $inlineDescription = sprintf(
                'Link: &lt;a href="%sindex.php?redirect=%s"&gt;%s&lt;/a&gt;<br/>',
                $_ENV['LINKTOSELF'],
                $row['id'],
                htmlspecialchars($row['url'])
            );
            $item = $xml->channel->addChild('item');
            $item->addChild('title', $row['title']);
            $item->addChild('description', $inlineDescription);
            $item->addChild('link', "{$_ENV['LINKTOSELF']}/index.php?redirect={$row['id']}");
            $item->addChild('pubDate', date(DATE_RSS, strtotime($row['timestamp'])));
        }

        header('Content-Type:text/xml');
        echo $xml->asXML();
    }
}
