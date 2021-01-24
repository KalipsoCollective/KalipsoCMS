<?php

use \app\core\App;

/**
 * KalipsoCMS - PHP-based, open source, powerful and experimental content management system.
 * @package  KalipsoCMS
 * @author   koalapix. <hello@koalapix.com>
 */

define('KALIPSO_GO', microtime());

$app = require __DIR__.'/app/start.php';

$app = new App();
$app->start();