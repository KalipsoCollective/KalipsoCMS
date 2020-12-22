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
 * @param string $dir
 * @return string $base main url
 */

function base($dir = ''): string
{
    return (config('settings.ssl') ? 'https://' : 'http://') .
        $_SERVER['HTTP_HOST'] . '/'. trim($dir, '/');
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


/**
 * Configuration Value
 * @param $setting
 * @return string|array|object main path
 */

function config($setting)
{
    global $sysSettings;

    $return = '';

    if (strpos($setting, '.') !== false) {

        $setting = explode('.', $setting, 2);
        if (isset($sysSettings[$setting[0]]) !== false AND isset($sysSettings[$setting[0]][$setting[1]]) !== false) {

            $return = $sysSettings[$setting[0]][$setting[1]];

        }

    }

    return $return;
}



if (! function_exists("array_key_last")) { // for <= PHP 7.0

    /**
     * Array Key Last for Older PHP Versions
     * @param $array
     * @return mixed|null
     */
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

        /*
            // $log = new Log();
            // $log->errorRecord('sys_file', $file);
            exit("$file" . ' not loaded');
        */

        if ($create) touch($file);
    }
    return $file;
}

/**
 * Assets File Controller
 * @param string $filename
 * @param bool $version
 * @param bool $tag
 * @param bool $echo
 * @param array $externalParameters
 * @return string|null
 */

function assets(string $filename, $version = true, $tag = false, $echo = false, $externalParameters = []): ?string
{

    $fileDir = rtrim( path().'assets/'.$filename, '/' );
    $return = trim( base().'assets/'.$filename, '/' );
    if (file_exists( $fileDir )) {

        $return = $version==true ? $return.'?v='.filemtime($fileDir) : $return;
        if ( $tag==true ) // Only support for javascript and stylesheet files
        {
            $_externalParameters = '';
            foreach ($externalParameters as $param => $val) {
                $_externalParameters = ' ' . $param . '="' . $val . '"';
            }

            $file_data = pathinfo( $fileDir );
            if ( $file_data['extension'] == 'css' )
            {
                $return = '<link'.$_externalParameters.' rel="stylesheet" href="'.$return.'" type="text/css"/>'.PHP_EOL.'		';

            }elseif ( $file_data['extension'] == 'js' )
            {
                $return = '<script'.$_externalParameters.' src="'.$return.'"></script>'.PHP_EOL.'		';
            }
        }

    } else {
        $return = null;
        // new app\core\Log('sys_asset', $filename);
    }

    if ( $echo == true ) {

        echo $return;
        return null;

    } else {
        return $return;
    }
}

/**
 * Language Translation Return
 * @param string $key
 * @param string $transform
 * @param null $change
 * @return string
 */

function lang($key='', $transform='', $change=null): string
{

    global $languageKeys;

    if (isset($languageKeys[$key]) !== false) {

        $key = $languageKeys[$key];

    }/* else {

        // $log = new Log();
        // $log->errorRecord('sys_lang', $key);
    }*/

    if ($transform != '' OR $change != null) {

        $key = $languageKeys($key, $transform, $change);
    }

    return $key;
}