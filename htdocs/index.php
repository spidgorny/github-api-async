<?php

use GitGuzzle\AsyncGithub;
use GitGuzzle\TestGithub;
use GitGuzzle\TestGuzzle;

require_once __DIR__.'/../vendor/autoload.php';

define('BR', php_sapi_name() == 'cli' ? PHP_EOL : "<br />\n");
define('TAB', php_sapi_name() == 'cli' ? "\t"
	: '<span style="width: 4em; display: inline-block"></span>');

//$tg = new TestGuzzle();
//$tg = new TestGithub();
$tg = new AsyncGithub();

$tg->render();

