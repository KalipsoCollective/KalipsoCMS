<?php

/**
 * Basic constants
 **/
define('KN_START', microtime(true)); // We use it for the exec. time recorded in the log.
define('KN_ROOT',  rtrim($_SERVER["DOCUMENT_ROOT"], '/').'/');
<<<<<<< HEAD
define('KN_VERSION', '1.0.2.8');
<<<<<<< HEAD
define('KN_CORE_VERSION', '1.0.2.7');
=======
=======
define('KN_VERSION', '1.0.2.7');
>>>>>>> ee62a7d (remove)
define('KN_CORE_VERSION', '1.0.2.6');
>>>>>>> 7b50d0f (While preparing the auto-complete list in the content controller, an arrangement has been made so that the records with a large number of content are displayed on the top.)

/**
 * Shutdown function registration
 **/
register_shutdown_function( function() {
    KN\Core\Exception::fatalHandler();
});

/**
 * Error handler set
 **/
set_error_handler( function($level, $error, $file, $line) {
	if (0 === error_reporting()) {
        return false;
    }
    KN\Core\Exception::errorHandler($level, $error, $file, $line);
}, E_ALL);

/**
 * Exception handler set
 **/
set_exception_handler( function($e) {
    KN\Core\Exception::exceptionHandler($e);
});

/**
 * php.ini set and error reporting setting
 **/
ini_set('display_errors', 'on');
error_reporting(E_ALL);