<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Initiate extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $tbl = $this->table('bloglist', ['comment' => 'Track interesting blog pages']);
        $tbl->addcolumn('title', 'char', ['limit' => 255])
            ->addcolumn('url', 'char', ['limit' => 255])
            ->addcolumn('host', 'char', ['limit' => 255, 'comment' => 'domain name'])
            ->addcolumn('notes', 'text')
            ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'comment' => 'Record created time'])
            ->addColumn('modified', 'datetime', ['null' => true, 'update' => 'CURRENT_TIMESTAMP', 'comment' => 'Record modified'])
            ->addColumn('deleted', 'datetime', ['null' => true, 'comment' => 'Record deleted'])
            ->addindex(['created'])
            ->addindex(['deleted'])
            ->addindex(['host'])
            ->create();
            
        $tbl = $this->table('tag', ['comment' => 'Keywords associated with a page']);
        $tbl->addcolumn('name', 'char', ['limit' => 25])
            ->addindex(['name'])
            ->create();

        $tbl = $this->table('bloglist_tag_xref', ['comment' => 'cross references between bloglist & tag tables']);
        $tbl->addcolumn('bloglist_id', 'integer')
            ->addcolumn('tag_id', 'integer')
            ->addindex(['bloglist_id'])
            ->addindex(['tag_id'])
            ->create();
    }
}
