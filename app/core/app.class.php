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

    public $url;
    public $request;

    function __construct()
    {
        $this->url = trim($_SERVER['REQUEST_URI'], '/');

        $this->request = strpos($this->url, '/') ? explode('/', $this->url) : [$this->url];

        ob_start();

        // session_name(config('app.session'));
        session_start();
    }

    public function start()
    {

    }
}