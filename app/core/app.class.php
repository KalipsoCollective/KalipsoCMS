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
    public $contentFile = null;
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

        if (in_array('', available_langs)) $this->request[0]

        varFuck($this->request);

    }

    public function fire() {

        foreach ($this->pageParts as $part) {

            if ($part == '_') {
                if (is_null($this->contentFile)) {
                    continue;
                } else {
                    $part = $this->contentFile;
                }
            }
            $filePath = path('app/view/' .trim($this->currentDirectory.'/'.$part, '/').'.php');
            require includeFile( $filePath, true );

        }

    }

    public function start()
    {

        $this->routeDetector();
        $this->fire();

    }
}