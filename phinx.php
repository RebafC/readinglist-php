<?php

/* currently configured for Sqlite
    to use mysql
        change the adapter from'sqlite' to 'mysql' 
        change name from 'db\bloglist' to 'bloglist' i.e. drop the path
    */
return
[
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/db/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'development',
        'production' => [
            'adapter' => 'sqlite',
            'host' => 'localhost',
            'name' => 'db\bloglist',
            'user' => 'root',
            'pass' => '',
            'port' => '3306',
            'charset' => 'utf8',
            'logfile' => '%%PHINX_CONFIG_DIR%%\db\sqllog\my.sql.log',
        ],
        'development' => [
            'adapter' => 'sqlite',
            'host' => 'localhost',
            'name' => 'db\bloglist',
            'user' => 'root',
            'pass' => '',
            'port' => '3306',
            'charset' => 'utf8',
            'logfile' => '%%PHINX_CONFIG_DIR%%\db\sqllog\my.sql.log',
        ],
        'testing' => [
            'adapter' => 'sqlite',
            'host' => 'localhost',
            'name' => 'db\bloglist',
            'user' => 'root',
            'pass' => '',
            'port' => '3306',
            'charset' => 'utf8',
            'logfile' => '%%PHINX_CONFIG_DIR%%\db\sqllog\my.sql.log',
        ]
    ],
    'version_order' => 'creation'
];