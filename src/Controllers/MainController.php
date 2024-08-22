<?php

namespace App\Controllers;

use App\Twig;
use App\Repositories\ReadinglistRepository;

class MainController
{
    private $db;

    private $twigvars = [];

    public function __construct()
    {
    }

    public function index()
    {
        $rlrepo = new ReadinglistRepository($this->db);
        $rows = $rlrepo->getAllNotDeleted();

        foreach ($rows as &$r) {
                $r['title'] = urldecode($r['title']);
        }

        $twigvars = [
            'rows' => $rows,
        ];

        Twig::render('list.html.twig', $twigvars);
    }

    public function showdeleted()
    {   
        $rlrepo = new ReadinglistRepository($this->db);
        $rows = $rlrepo->getAllDeleted();

        foreach ($rows as &$r) {
                $r['title'] = urldecode($r['title']);
        }

        $twigvars = [
            'rows' => $rows,
            'showdel' => true,
        ];

        Twig::render('list.html.twig', $twigvars);
    }

    public function showXml()
    {
        $xml = new \SimpleXMLElement('<rss version="2.0"></rss>');

        $xml->addChild('channel');
        $xml->channel->addChild('title', 'Reading list');
        $xml->channel->addChild('link', $_ENV['linkToSelf']);
        $xml->channel->addChild('description', 'Contains saved urls');
        $xml->channel->addChild('pubDate', date(DATE_RSS));

        $rlrepo = new ReadinglistRepository($this->db);
        $rows = $rlrepo->getAllNotDeleted();

        foreach ($rows as $row) {
            $inlineDescription = sprintf(
                'Link: &lt;a href="%sindex.php?redirect=%s"&gt;%s&lt;/a&gt;<br/>',
                $_ENV['linkToSelf'],
                $row['id'],
                htmlspecialchars($row['url'])
            );
            $item = $xml->channel->addChild('item');
            $item->addChild('title', $row['title']);
            $item->addChild('description', $inlineDescription);
            $item->addChild('link', "{$_ENV['linkToSelf']}/index.php?redirect={$row['id']}");
            $item->addChild('pubDate', date(DATE_RSS, strtotime($row['timestamp'])));
        }

        header('Content-Type:text/xml');
        echo $xml->asXML();
    }

    public function add($url, $title)
    {
        $url = rawurldecode($url);
        
        $rlrepo = new ReadinglistRepository($this->db);
        $rlrepo->add($url, $title);

        include BASE_PATH . '/templates/base.html.twig';
    }

    public function redirect()
    {
        $rlrepo = new ReadinglistRepository($this->db);
        $url = $rlrepo->getUrlFromId();

        if ($url !== []) {
            header("location: {$url}");
            die();
        }
        echo('Error fetching url');
    }

    public function delete($id)
    {
        $rlrepo = new ReadinglistRepository($this->db);
        $rlrepo->delete($id);
    }

    public function activate($id)
    {
        $rlrepo = new ReadinglistRepository($this->db);
        $rlrepo->activate($id);
    }
}
