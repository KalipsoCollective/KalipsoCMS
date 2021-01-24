<?php

define('ROOT_PATH',  rtrim($_SERVER["DOCUMENT_ROOT"], '/').'/');

/**
 * Autoload register definition
 */

spl_autoload_register('autoLoader');

/**
 * Autoloader
 * @param string $class class name
 */

function autoLoader(string $class)
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

require ROOT_PATH . 'app/core/libs/system.php';
require ROOT_PATH . 'app/core/libs/tools.php';
require ROOT_PATH . 'app/core/libs/io.php';