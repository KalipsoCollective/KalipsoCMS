<?php

use \app\controller\App;

define('ROOT_PATH',  rtrim($_SERVER["DOCUMENT_ROOT"], '/').'/');

ob_start();

session_name(config('app.session'));
session_start();

$app = new App();
$app->start();