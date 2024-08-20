<?php

namespace App\Controllers;

use ParagonIE\EasyDB\Factory;
use App\Twig;


class MainController
{
    private $db;

    private $twigvars = [];

    private $assetPath;

    public function __construct()
    {
        $this->assetPath = dirname($_SERVER['PHP_SELF']) . '/';

        $this->db = Factory::fromArray([
            sprintf('mysql:host=%s;dbname=%s', $_ENV['mysqlHost'], $_ENV['mysqlDatabase']),
            $_ENV['mysqlUser'],
            $_ENV['mysqlPassword']
        ]);
    }

    public function index()
    {
        ## TODO :: move to a model
        ## TODO :: handle pagination
        $rows = $this->db->run(<<<SQL
SELECT `id`, `added`, `title`, `url` FROM `{$_ENV['mysqlTable']}`
WHERE `deleted` IS NULL ORDER BY `id` DESC LIMIT 0,50;
SQL);

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
        $rows = $this->db->run(<<<SQL
SELECT `id`, `added`, `title`, `url` FROM `{$_ENV['mysqlTable']}`
WHERE `deleted` IS NOT NULL ORDER BY `id` DESC LIMIT 0,50;
SQL);

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

        ## TODO :: move to a model
        ## TODO :: handle different limits
        $rows = $this->db->run(<<<SQL
SELECT `id`, `added`, `title`, `url` FROM `{$_ENV['mysqlTable']}`
WHERE `deleted` IS NULL ORDER BY `id` DESC LIMIT 0,50;
SQL);
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
        $this->addUrl($this->db, $_ENV['mysqlTable'], rawurldecode($url), $title);

        include BASE_PATH . '/templates/base.html.twig';
    }

    public function redirect()
    {
        ## TODO :: move to a model
        $url = $this->db->cell("SELECT `url` FROM `{$mysqlTable}` WHERE `id` = ?;", $_GET['redirect']);

        if ($row !== []) {
            header("location: {$url}");
            die();
        }
        echo('Error fetching row');
    }

    private function addUrl($db, $mysqlTable, $url, $title)
    {
        ## TODO :: move to a model
        $this->db->insert(
            $mysqlTable,
            [
                'title' =>  htmlspecialchars(str_replace("'", '', $title ?? $this->getTitleFromUrl($url, $title))),
                'url' => $url,
                'host' => $this->getHostFromUrl($url),
            ]
        );
    }

    private function getTitleFromUrl($url, $title)
    {
        if ($this->isUrl($url)) {
            $tags = get_meta_tags($url);
            if (isset($tags['title'])) {
                return $tags['title'];
            }
        }
        return substr($url, 0, 50);
    }

    private function getHostFromUrl($url)
    {
        $parse = parse_url($url);

        return $parse['host'] ?? $url;
    }

    private function isUrl($url)
    {
        return filter_var($url, FILTER_VALIDATE_URL);
    }
}
