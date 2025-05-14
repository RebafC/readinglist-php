<?php

namespace App\Repositories;

use App\Twig;

use App\Traits\GetConnection;
use App\Repositories\AbstractRepository;

class BloglistRepository
{
    use GetConnection;

    private $db;
    private $sqlTable;

    public function __construct()
    {
        $this->db = $this->getDB($_ENV['DB_TYPE']);

        Twig::addGlobalVar('dbase', $this->db->getDriver());
        $this->sqlTable = $_ENV['DB_TABLE'];
    }
    public function getAllNotDeleted()
    {
        return $this->db->run(<<<SQL
SELECT `id`, datetime(`created`, 'localtime') as `localtime`, `title`, `url` FROM `{$this->sqlTable}`
WHERE `deleted` IS NULL ORDER BY `id` DESC LIMIT 0,50;
SQL);
    }

    public function getAllDeleted()
    {
        return $this->db->run(<<<SQL
SELECT `id`, `title`, `url`, datetime(`created`, 'localtime') as `localadd`, `created`, 
    datetime(`deleted`, 'localtime') as `localdel`, `deleted` FROM `{$this->sqlTable}`
WHERE `deleted` IS NOT NULL ORDER BY `id` DESC LIMIT 0,50;
SQL);
    }

    public function getUrlFromId($id)
    {
        return $this->db->cell("SELECT `url` FROM `{$this->sqlTable}` WHERE `id` = ?;", $id);
    }

    public function add($url, $title)
    {
        $this->db->insert(
            $this->sqlTable,
            [
                'title' =>  htmlspecialchars(str_replace("'", '', $title ?? $this->getTitleFromUrl($url, $title))),
                'url' => $url,
                'host' => $this->getHostFromUrl($url),
            ]
        );
    }

    public function delete($id)
    {
        $this->db->update(
            $this->sqlTable,
            [
                'deleted' => gmdate('Y-m-d H:i:s')
            ],
            [
                'id' => $id
            ]
        );
    }
    public function activate($id)
    {
        $this->db->update(
            $this->sqlTable,
            [
                'deleted' => null
            ],
            [
                'id' => $id
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
