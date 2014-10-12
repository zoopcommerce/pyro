<?php

putenv("SERVER_TYPE=test");

ini_set('error_reporting', E_ALL);
ini_set('display_errors', '1');
ini_set('memory_limit', '256M');

$applicationRoot = '/../';

chdir(__DIR__ . $applicationRoot);

$loader = require_once('vendor/autoload.php');

if (!isset($loader)) {
    throw new RuntimeException('vendor/autoload.php could not be found. Did you run `php composer.phar install`?');
}

$loader->add('Zoop\Pyro\Test', __DIR__);
