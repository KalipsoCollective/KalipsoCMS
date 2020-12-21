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

    public ?string $url;
    public ?bool $isLogged = false;
    public ?string $currentLang;
    public ?array $request;
    public ?string $route;
    public ?string $contentFile = null;
    public ?string $currentDirectory = '';
    /*
     * Default page parts
     */
    public array $pageParts = [
        'head',
        'nav',
        '_',
        'footer',
        'end'
    ];

    public function __construct()
    {
        global $sysSettings;
        global $routeSchema;

        $routeSchema = include(path('app/core/defs/route_schema.php'));

        // configuration files initialize
        $confFiles = glob(path('app/core/config/*.php'));

        $sysSettings = [];
        if (is_array($confFiles)) {

            foreach ($confFiles as $file) {

                $data = include $file;

                if (is_array($data)) {

                    $file = explode('/', $file);
                    $file = end($file);
                    $file = str_replace('.php', '', $file);

                    $sysSettings[$file] = $data;

                }
            }
        }

        $url = parse_url(base() . trim(strip_tags($_SERVER['REQUEST_URI']), '/'));
        $this->url = trim($url['path'], '/');

        $this->request = strpos($this->url, '/') ? explode('/', $this->url) : [$this->url];

        $this->currentLang = config('app.default_lang');

        ob_start();

        session_name(config('app.session'));
        session_start();
    }

    public function routeDetector()
    {
        global $routeSchema;

        // Language Detection from URL
        if ($this->request[0] !== ''  AND in_array($this->request[0], config('app.available_langs'))) {

            $this->currentLang = $this->request[0];
            array_shift($this->request);

        }

        // Directory Detection from URL
        if (isset ($this->request[0]) !== false
            AND $this->request[0] !== ''
            AND is_dir(path('app/view/' . $this->request[0]))
        ) {

            $this->currentDirectory = $this->request[0];
            array_shift($this->request);

        }

        foreach ($routeSchema[$this->currentDirectory] as $key => $value) {

            if ($value['auth'] == $this->isLogged) {

                $this->route = $key;
                $this->contentFile = isset($value['file']) !== false ? $value['file'] : $key;

                if (isset($value['page_parts']) !== false) {
                    $this->pageParts = $value['page_parts'];
                }
                break;

            }

        }

    }

    public function fire()
    {

        foreach ($this->pageParts as $part) {

            if ($part == '_') {

                if (is_null($this->contentFile)) {
                    continue;
                } else {
                    $part = $this->contentFile;
                }

            }

            $filePath = path('app/view/' . trim($this->currentDirectory . '/' . $part, '/') . '.php');
            require includeFile($filePath, true);

        }

    }

    public function start()
    {

        $this->routeDetector();
        $this->fire();

    }
}