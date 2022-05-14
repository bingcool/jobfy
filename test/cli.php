#!/usr/bin/env php
<?php
require __DIR__.'/Common.php';

use Jobfy\Manager;
use Workerfy\ConfigLoader;

// load config
$conf = ConfigLoader::getInstance()->loadConfig(__DIR__ . '/conf.php');
// load conf
$manager = Manager::getInstance()->loadConf($conf);
$manager->start();