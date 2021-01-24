<?php
declare(strict_types=1);

namespace app\core;

use app\modules\Log;
use app\modules\User;

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
    public ?string $route = '';
    public ?string $contentFile = null;
    public ?string $currentDirectory = '';
    public ?string $pageTitle = '';
    public ?string $pageDescription = '';
    public ?object $db;
    public ?int $userId = 0;
    public ?string $authCode = '';
    public ?int $httpStatus = 200;
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
        global $routeSchema;
        global $authorityPoints;
        global $sysSettings;

        $routeSchema = include(path('app/core/defs/route_schema.php'));

        // view area's authority points init
        foreach ($routeSchema as $dir => $pages) {

            foreach ($pages as $key => $parameters) {

                $authorityPoints['view'][trim($dir . '/' .$key, '/')] = [
                    'name'      => $parameters['title'],
                    'default'   => $parameters['auth'],
                    'view'      => (! $parameters['auth'])
                ];
            }
        }

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
        } else {
            exit('Configuration files not found!');
        }

        http('powered_by');
        ob_start();
        session_name(config('app.session'));
        session_start();

        $this->db = new db();

        $url = parse_url(base() . trim(strip_tags($_SERVER['REQUEST_URI']), '/'));
        $this->url = trim($url['path'], '/');

        $this->request = strpos($this->url, '/') ? explode('/', $this->url) : [$this->url];

        $this->currentLang = config('app.default_lang');

        $this->authCode = (new User())->getAuthCode();
        $this->userId = (new User())->getUserId();

    }

    protected function localize()
    {
        global $languageKeys;

        if ($this->request[0] !== ''  AND in_array($this->request[0], config('app.available_langs'))) {

            $this->currentLang = $this->request[0];
            array_shift($this->request);
            $_SESSION['lang'] = $this->currentLang;

        }

        if (isset($_SESSION['lang']) === false) {
            $_SESSION['lang'] = $this->currentLang;
        } else {
            $this->currentLang = $_SESSION['lang'];
        }

        $languageKeys = include path('app/lang/' . $this->currentLang . '.php');

    }

    public function routeDetector()
    {
        global $routeSchema;

        // Language Detection from URL
        $this->localize();

        // Directory Detection from URL
        if (isset ($this->request[0]) !== false
            AND $this->request[0] !== ''
            AND is_dir(path('app/view/' . $this->request[0]))
        ) {

            $this->currentDirectory = $this->request[0];
            array_shift($this->request);

        }

        if (isset($this->request[0]) !== false AND $this->request[0] == 'script') {

            $this->loadJS();
            $this->pageParts = [];

        } elseif (isset($this->request[0]) !== false AND $this->request[0] == 'async') {

            $this->loadXhrResponse();
            $this->pageParts = [];

        } elseif (config('settings.debug_mode') AND
            (isset($this->request[0]) !== false AND $this->request[0] == 'sandbox')) {

            $this->loadDeveloperMode();
            $this->pageParts = [];

        } else {

            $detected = false;
            foreach ($routeSchema[$this->currentDirectory] as $key => $value) {

                if ($value['auth'] == $this->isLogged) { // temp
                    $detected = true;
                    $this->route = $key;
                    $this->contentFile = isset($value['file']) !== false ? $value['file'] : $key;
                    $this->pageTitle = lang(isset($value['title']) !== false ? $value['title'] : $key);

                    if (isset($value['page_parts']) !== false) {
                        $this->pageParts = $value['page_parts'];
                    }
                    break;

                }

            }
            if (! $detected) {

                $this->httpStatus = 404;

            }
        }

    }

    public function start()
    {
        $this->isLogged = ! ($this->userId === 0);
        $this->routeDetector();
        $this->fire();

    }

    public function loadJS()
    {
        require includeFile(
            path(
                'app/core/script.php'
            ), true
        );
    }

    public function loadXhrResponse()
    {
        array_shift($this->request);
        require includeFile(
            path(
                'app/controller/xhrController.php'
            ), true
        );
    }

    public function title(): string
    {
        return trim(
            $this->pageTitle . ' ' . config('settings.separator') . ' ' .config('settings.name'),
            config('settings.separator')
        );
    }

    public function description()
    {
        $description = $this->pageDescription;

        if ($description == '') {
            $description = config('settings.description');
        }
        return $description;
    }

    public function fire()
    {
        if (isset($_SERVER['HTTP_X_PJAX']) !== false) {
            echo '<title>'.$this->title().'</title>';
        }

        foreach ($this->pageParts as $part) {

            if ($part == '_') {

                if (is_null($this->contentFile)) {
                    continue;
                } else {
                    $part = $this->contentFile;
                }

            }

            if (isset($_SERVER['HTTP_X_PJAX']) !== false AND ($part == 'head' OR $part == 'footer')) {
                continue;
            }

            $filePath = path(
                'app/view/' . trim($this->currentDirectory . '/' . $part, '/') . '.php'
            );
            require includeFile($filePath, true);

        }

        if ($this->route != '') {
            (new Log())->record([
                'action'        => 'view',
                'route'         => $this->route,
                'http_status'   => $this->httpStatus
            ]);
        }
    }

    public function meta()
    {
        /*


         FB OG
        <meta property="fb:app_id" content="123456789">
        <meta property="og:url" content="https://example.com/page.html">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Content Title">
        <meta property="og:image" content="https://example.com/image.jpg">
        <meta property="og:image:alt" content="A description of what is in the image (not a caption)">
        <meta property="og:description" content="Description Here">
        <meta property="og:site_name" content="Site Name">
        <meta property="og:locale" content="en_US">
        <meta property="article:author" content="">

        Twitter Card
        <meta name="twitter:card" content="summary">
        <meta name="twitter:site" content="@site_account">
        <meta name="twitter:creator" content="@individual_account">
        <meta name="twitter:url" content="https://example.com/page.html">
        <meta name="twitter:title" content="Content Title">
        <meta name="twitter:description" content="Content description less than 200 characters">
        <meta name="twitter:image" content="https://example.com/image.jpg">
        <meta name="twitter:image:alt" content="A text description.">

        Robots
        <meta name="robots" content="index,follow,noodp">
        <meta name="googlebot" content="index,follow">

        Verification
        <meta name="google-site-verification" content="verification_token">
        <meta name="yandex-verification" content="verification_token">
        <meta name="msvalidate.01" content="verification_token">
        <meta name="alexaVerifyID" content="verification_token">
        <meta name="p:domain_verify" content="code from pinterest">
        <meta name="norton-safeweb-site-verification" content="norton code">
         */
    }

    public function generateLinkForOtherLanguages($lang, $route = '', $directory = ''): string
    {
        if ($route == '') {
            $directory = 'x';
        }

        if ($directory == '') $directory = $this->currentDirectory;

        return base(trim($lang . '/' . $directory . '/', '/'));
    }

    public function url(): string
    {
        return trim(base() . $this->currentLang . '/'. $this->currentDirectory, '/');
    }

    public function loadDeveloperMode()
    {
        require path('app/core/sandbox.php');
    }
}