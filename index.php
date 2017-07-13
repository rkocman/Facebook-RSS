<?php

/**
 * Facebook RSS
 * Author: Radim Kocman
 */

namespace FacebookRSS;

// load a config
require_once 'config.php';

// composer autoloader
require_once 'vendor/autoload.php';

// app autoloader
require_once 'src/autoload.php';

// start the app
Engine::run();
