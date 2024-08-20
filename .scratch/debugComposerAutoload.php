<?php

/*
    cd [directory where composer.json is located]
    php debugComposerAutoload.php
*/
// location of composer.json */
$location = __DIR__ . '\..\\';

/* Find the files available via psr-4 autoloading */
$composer_json = file_get_contents($location . 'composer.json');
$decoded_composer = json_decode($composer_json, true);

$psr4Keys = array_keys($decoded_composer['autoload']['psr-4']);

$autoloads = include $location . 'vendor/composer/autoload_psr4.php';

echo 'Class files available for autoload' . PHP_EOL . PHP_EOL;

foreach (array_keys($autoloads) as $autoload_key) {
    if (!in_array($autoload_key, $psr4Keys)) {
        continue;
    }
    $dir = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $autoloads[$autoload_key][0]);
    echo $dir . PHP_EOL;
    listFiles($dir);
}

function listFiles($dir)
{
    $directory = new RecursiveDirectoryIterator($dir);
    $iterator = new RecursiveIteratorIterator($directory);
    $files = [];
    foreach ($iterator as $info) {
        if ($info->getFilename() == '.' || $info->getFilename() == '..') {
            continue;
        }
        echo str_replace($dir . DIRECTORY_SEPARATOR, '', $info->getRealPath() ?? $info->getPathname()). PHP_EOL;
    }
}
