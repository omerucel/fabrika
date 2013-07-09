<?php

$autoloader = require __DIR__ . '/vendor/autoload.php';
$autoloader->add('Fabrika\\', realpath(__DIR__ . '/tests/src/'));

date_default_timezone_set('UTC');
