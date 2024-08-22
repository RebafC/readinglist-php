<?php

namespace App\Repositories;

use App\Twig;
use App\SqliteConnection;

class ReadinglistRepository
{
    private $db;
    private $sqlTable;

    public function __construct()
    {
        $this->db = SqliteConnection::connect();
        Twig::addGlobalVar('dbase', $this->db->getDriver());
        $this->sqlTable = $_ENV['sqlTable'];

    }

    public function getAllNotDeleted()
    {
        return $this->db->run(<<<SQL
SELECT `id`, `added_at`, `title`, `url` FROM `{$this->sqlTable}`
WHERE `deleted_at` IS NULL ORDER BY `id` DESC LIMIT 0,50;
SQL);
    }

    public function getAllDeleted()
    {
        return $this->db->run(<<<SQL
SELECT `id`, `title`, `url`, `added_at`, `deleted_at` FROM `{$this->sqlTable}`
WHERE `deleted_at` IS NOT NULL ORDER BY `id` DESC LIMIT 0,50;
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
                'deleted_at' => date('Y-m-d H:i:s')
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
                'deleted_at' => null
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
