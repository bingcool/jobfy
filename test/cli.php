#!/usr/bin/env php
<?php
use Jobfy\Manager;
use Workerfy\ConfigLoader;

require __DIR__.'/Common.php';

// set into global conf
ConfigLoader::getInstance()->setConfig($workerConfScope);

// load conf
$manager = Manager::getInstance()->loadConf($workerConfScope);
$manager->start();