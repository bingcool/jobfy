<?php
date_default_timezone_set('Asia/Shanghai');
define("START_SCRIPT_FILE", $_SERVER['PWD'].'/'.$_SERVER['SCRIPT_FILENAME']);

$paths = explode('/', pathinfo(START_SCRIPT_FILE, PATHINFO_DIRNAME));
$currentDir = @array_pop($paths);

define("PID_FILE_ROOT", '/tmp/jobfy-test/log/'.$currentDir);
define("PID_FILE", PID_FILE_ROOT.'/'.pathinfo(START_SCRIPT_FILE,PATHINFO_FILENAME).'.pid');
define('APP_ROOT', dirname(__DIR__));

include APP_ROOT . "/vendor/autoload.php";
$workerConf = require __DIR__.'/conf.php';

$globalEnv = 'dev';
$envFile = APP_ROOT . '/env.ini';
if(file_exists($envFile)) {
    $options = parse_ini_file($envFile, true);
    $env = $options['global']['env'] ?? '';
    if($env) {
        $globalEnv = $env;
    }
}
defined('WORKERFY_ENV') or define('WORKERFY_ENV', $globalEnv);


$workerConfScopeEnv = $options['global']['worker_conf_scope'] ?? '*';
$workerConfScopeEnv = explode(',', $workerConfScopeEnv);
$workerConfScope = [];
if($workerConfScopeEnv[0] != '*') {
    foreach ($workerConfScopeEnv as $confScope) {
        $workerConfScope[$confScope] = $workerConf[$confScope];
        write_info("【Job info】This machine worker conf:{$confScope}");
    }
}else {
    $workerConfScope = $workerConf;
    write_info("【Job info】This machine worker conf is all conf score");
}


$configFilePath = __DIR__."/Config/config.php";
// load config
\Workerfy\ConfigLoader::getInstance()->loadConfig($configFilePath);