<?php

/**
 * Facebook RSS
 * Author: Radim Kocman
 */

namespace FacebookRSS;

use Tracy\Debugger;
use Nette\Loaders\RobotLoader;
use Nette\Caching\Storages\FileStorage;

// load a config
require_once __DIR__.'/config.php';

// composer autoloader
require_once __DIR__.'/vendor/autoload.php';

// tracy debugger
$mode = (AppConfig::devel)? Debugger::DEVELOPMENT : Debugger::PRODUCTION;
Debugger::enable($mode, __DIR__.'/log');
Debugger::$maxDepth = 6;
Debugger::$maxLength = 500;

// robot loader for the app
$loader = new RobotLoader;
$loader->addDirectory(__DIR__.'/app');
$storage = new FileStorage(__DIR__.'/temp');
$loader->setCacheStorage($storage);
$loader->register();

// start the app
Engine::run();
