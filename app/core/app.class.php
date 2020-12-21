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
    public $route;
    public $currentDirectory = '';

    /*
     * Default page parts
     */
    public $pageParts = [
        'head',
        'nav',
        '_',
        'footer',
        'end'
    ];

    function __construct()
    {
        $this->url = parse_url(base().trim(strip_tags($_SERVER['REQUEST_URI']), '/'));
        $this->url = trim($this->url['path'], '/');

        $this->request = strpos($this->url, '/') ? explode('/', $this->url) : [$this->url];

        ob_start();

        // session_name(config('app.session'));
        session_start();
    }

    public function routeDetector() {



    }

    public function fire() {

        foreach ($this->pageParts as $part) {

            $filePath = path('view/' .trim($this->currentDirectory.'/'.$part, '/').'.php');
            require includeFile( $filePath );

        }

    }

    public function start()
    {

        $this->routeDetector();
        $this->fire();

    }
}