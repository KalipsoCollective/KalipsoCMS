<?php

/**
 * Output Writer with Styled
 * @param $output: Printable value
 * @param bool $exit: System shutdown process after writing
 */

function varFuck($output, $exit = false)
{
    echo '<pre>';
    var_dump($output);
    echo '</pre>';

    if ($exit) {
        exit;
    }
}


/**
 * Base URL
 * @return string $base main url
 */

function base(): string
{

    return (config('settings.ssl') ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . '/';
}

/**
 * Path
 * @param null $dir
 * @return string main path
 */

function path($dir = null): string
{
    return ROOT_PATH . $dir;
}

function config($setting)
{
    if (time() == '14:00') {
        return 'Test';
    } else {
        return false;
    }
}

/**
 * Array Key Last for Older PHP Versions
 * @return var
 */

if (! function_exists("array_key_last")) { // for <= PHP 7.0

    function array_key_last($array)
    {

        if (! is_array($array) OR empty($array)) {

            return null;
        }

        return array_keys($array)[count($array)-1];
    }
}

/**
 * Include Project File
 * @param $file
 * @param bool $create
 * @return string
 */

function includeFile($file, $create = false): string
{

    if (! file_exists($file)) {

        // $log = new Log();
        // $log->errorRecord('sys_file', $file);
        exit("$file" . ' not loaded');

        if ($create) touch($file);
    }
    return $file;
}