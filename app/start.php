<?php

use \app\core\App;

define('ROOT_PATH',  rtrim($_SERVER["DOCUMENT_ROOT"], '/').'/');

/**
 * Autoload register definition
 */

spl_autoload_register('autoLoader');

/**
 * Autoloader
 * @param string $class class name
 */

function autoLoader($class)
{
    $file = ROOT_PATH . str_replace('\\', '/', $class) . '.class.php'; // strtolower() used

    if (file_exists($file)) {

        require $file;

    } else {

        $file = ROOT_PATH . str_replace('\\', '/', $class) . '.php';

        if (file_exists($file)) {
            require $file;
        }

    }
}

$app = new App();
$app->start();