<?php
declare(strict_types=1);

namespace app\core;


/**
 * Main App Class
 * @package  Kalipso
 * @author   koalapix <hello@koalapix.com>
 */

/**
 *
 */
class App
{

    function __construct()
    {
        ob_start();

        // session_name(config('app.session'));
        session_start();
    }

    public function start()
    {

    }
}