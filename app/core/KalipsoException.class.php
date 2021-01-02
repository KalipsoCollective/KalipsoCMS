<?php
declare(strict_types=1);

namespace app\core;

/**
 * Kalipso Exception Handler
 * @package  Kalipso
 * @author   koalapix <hello@koalapix.com>
 */

/**
 *
 */
class KalipsoException
{
    /**
     * Active stack.
     *
     * @var array
     */
    public static array $stack;

    /**
     * Style load validator.
     *
     * @var bool
     */
    public static ?bool $styles = false;

    /**
     * Custom methods.
     *
     * @since 1.1.3
     *
     * @var bool
     */
    public static ?bool $customMethods = false;

    public function __construct()
    {
        register_shutdown_function( [$this, 'exception'] );
        set_error_handler( [$this, 'error'] );
        set_exception_handler( [$this, 'exception'] );

        error_reporting(E_ALL);
    }

    /**
     * Handle exceptions catch.
     *
     * Optionally for libraries used in Eliasis PHP Framework: $e->statusCode
     *
     * @param object $e
     *                  string $e->getMessage()       → exception message
     *                  int    $e->getCode()          → exception code
     *                  string $e->getFile()          → file
     *                  int    $e->getLine()          → line
     *                  string $e->getTraceAsString() → trace as string
     *                  int    $e->statusCode         → HTTP response status code
     * @return bool
     */
    public function exception(object $e)
    {
        $traceString = preg_split("/#[\d]/", $e->getTraceAsString());

        unset($traceString[0]);
        array_pop($traceString);

        $trace = "\r\n<hr>BACKTRACE:\r\n";

        foreach ($traceString as $key => $value) {
            $trace .= "\n" . $key . ' ·' . $value;
        }

        $this->setParams(
            'Exception',
            $e->getCode(),
            $e->getMessage(),
            $e->getFile(),
            $e->getLine(),
            $trace,
            (isset($e->statusCode)) ? $e->statusCode : 0
        );

        return $this->render();
    }

    /**
     * Handle error catch.
     *
     * @param int $code → error code
     * @param int $msg → error message
     * @param int $file → error file
     * @param int $line → error line
     *
     * @return bool
     */
    public function error(int $code, int $msg, int $file, int $line): bool
    {
        $type = $this->getErrorType($code);

        $this->setParams($type, $code, $msg, $file, $line, '', 0);

        return $this->render();
    }

    /**
     * Convert error code to text.
     *
     * @param int $code → error code
     *
     * @return string → error type
     */
    public function getErrorType(int $code): string
    {
        switch ($code) {
            // 1
            case E_WARNING:
                return self::$stack['type'] = 'Warning'; // 2
            case E_PARSE:
                return self::$stack['type'] = 'Parse'; // 4
            case E_NOTICE:
                return self::$stack['type'] = 'Notice'; // 8
            case E_CORE_ERROR:
                return self::$stack['type'] = 'Core-Error'; // 16
            case E_CORE_WARNING:
                return self::$stack['type'] = 'Core Warning'; // 32
            case E_COMPILE_ERROR:
                return self::$stack['type'] = 'Compile Error'; // 64
            case E_COMPILE_WARNING:
                return self::$stack['type'] = 'Compile Warning'; // 128
            case E_USER_ERROR:
                return self::$stack['type'] = 'User Error'; // 256
            case E_USER_WARNING:
                return self::$stack['type'] = 'User Warning'; // 512
            case E_USER_NOTICE:
                return self::$stack['type'] = 'User Notice'; // 1024
            case E_STRICT:
                return self::$stack['type'] = 'Strict'; // 2048
            case E_RECOVERABLE_ERROR:
                return self::$stack['type'] = 'Recoverable Error'; // 4096
            case E_DEPRECATED:
                return self::$stack['type'] = 'Deprecated'; // 8192
            case E_USER_DEPRECATED:
                return self::$stack['type'] = 'User Deprecated'; // 16384
            default:
                return self::$stack['type'] = 'Error';
        }
    }

    /**
     * Set customs methods to renderizate.
     *
     * @param string|object $class → class name or class object
     * @param string $method → method name
     * @param int $repeat → number of times to repeat method
     * @param bool $default → show default view
     * @since 1.1.3
     *
     */
    public static function setCustomMethod($class, string $method, $repeat = 0, $default = false)
    {
        self::$customMethods[] = [$class, $method, $repeat, $default];
    }

    /**
     * Handle error catch.
     *
     * @param string $type
     * @param int $code → exception/error code
     * @param ?string $msg → exception/error message
     * @param ?string $file → exception/error file
     * @param int $line → exception/error line
     * @param ?string $trace → exception/error trace
     * @param ?int $http → HTTP response status code
     *
     * @return array → stack
     * @since 1.1.3
     */
    protected function setParams(
        string $type, int $code, ?string $msg, ?string $file, int $line, ?string $trace, ?int $http
    ): array
    {
        return self::$stack = [
            'type' => $type,
            'message' => $msg,
            'file' => $file,
            'line' => $line,
            'code' => $code,
            'http-code' => ($http === 0) ? http_response_code() : $http,
            'trace' => $trace,
            'preview' => '',
        ];
    }

    /**
     * Get preview of the error line.
     *
     * @since 1.1.0
     */
    protected function getPreviewCode()
    {
        $file = file(self::$stack['file']);
        $line = self::$stack['line'];

        $start = ($line - 5 >= 0) ? $line - 5 : $line - 1;
        $end = ($line - 5 >= 0) ? $line + 4 : $line + 8;

        for ($i = $start; $i < $end; $i++) {
            if (! isset($file[$i])) {
                continue;
            }

            $text = trim($file[$i]);

            if ($i == $line - 1) {
                self::$stack['preview'] .=
                    "<span class='jst-line'>" . ($i + 1) . '</span>' .
                    "<span class='jst-mark text'>" . $text . '</span><br>';
                continue;
            }

            self::$stack['preview'] .=
                "<span class='jst-line'>" . ($i + 1) . '</span>' .
                "<span class='text'>" . $text . '</span><br>';
        }
    }

    /**
     * Get customs methods to renderizate.
     *
     * @since 1.1.3
     */
    protected function getCustomMethods(): bool
    {
        $showDefaultView = true;
        $params = [self::$stack];

        unset($params[0]['trace'], $params[0]['preview']);

        $count = count(self::$customMethods);
        $customMethods = self::$customMethods;

        for ($i = 0; $i < $count; $i++) {
            $custom = $customMethods[$i];
            $class = isset($custom[0]) ? $custom[0] : false;
            $method = isset($custom[1]) ? $custom[1] : false;
            $repeat = $custom[2];
            $showDefault = $custom[3];

            if ($showDefault === false) {
                $showDefaultView = false;
            }

            if ($repeat === 0) {
                unset(self::$customMethods[$i]);
            } else {
                self::$customMethods[$i] = [$class, $method, $repeat--];
            }

            call_user_func_array([$class, $method], $params);
        }

        self::$customMethods = false;

        return $showDefaultView;
    }

    /**
     * Renderization.
     *
     * @return bool
     */
    protected function render(): bool
    {
        self::$stack['mode'] = defined('HHVM_VERSION') ? 'HHVM' : 'PHP';

        if (self::$customMethods && ! $this->getCustomMethods()) {
            return false;
        }

        $this->getPreviewCode();

        if (! self::$styles) {
            self::$styles = true;
            self::$stack['css'] = '
            hr {
                width: 30%;
            }
            
            .jst-alert {
                padding: 15px 25px;
                border-radius: 2px;
                word-break: break-all;
                margin: 25px;
                line-height: 20px;
                max-width: 1000px;
                -webkit-box-shadow: 0 4px 5px 0 rgba(0, 0, 0, .14), 0 1px 10px 0 rgba(0, 0, 0, .12), 0 2px 4px -1px rgba(0, 0, 0, .2);
                box-shadow: 0 4px 5px 0 rgba(0, 0, 0, .14), 0 1px 10px 0 rgba(0, 0, 0, .12), 0 2px 4px -1px rgba(0, 0, 0, .2);
                color: #fff;
            }
            
            .jst-right {
                color: rgba(255, 255, 255, 0.31) !important;
                float: right;
            }
            
            .jst-head {
                font-size: 18px;
                color: rgba(255, 255, 255, 0.5);
                top: 18px;
                font-weight: 600;
            }
            
            .Default {
                background-color: #446CB3;
            }
            
            .Error {
                background-color: #D91E18;
            }
            
            .Warning {
                background-color: #F9690E;
            }
            
            .Notice {
                background-color: #674172;
            }
            
            .jst-mark {
                background: #D91E18;
                padding: 4px;
                margin-left: -4px;
                color: white;
            }
            
            .jst-preview {
                width: 85%;
                margin-left: auto;
                margin-right: auto;
                overflow-x: auto;
                white-space: nowrap;
                background: white;
                color: #333;
                padding: 2pc;
            }
            
            .so-link {
                text-decoration: none;
                color: rgba(255, 255, 255, 0.5);
                cursor: pointer;
                display: inline-block;
                font-weight: inherit;
                -ms-transform: rotate(-35deg);
                transform: rotate(-35deg);
                -webkit-transform: rotate(35deg);
                -moz-transform: rotate(-35deg);
                -o-transform: rotate(-35deg);
                font-size: 23px;
            }
            
            .jst-line {
                margin-left: -10px;
                background: rgba(0, 0, 0, .14);
                padding: 2px 2px;
                margin-top: -1px;
                margin-right: 14px;
                width: 44px;
                display: inline-table;
                text-align: center;
            }
            
            .jst-trace {
                font-weight: 300;
                padding: 0pc 2pc 0pc 2pc;
            }
            
            .jst-file {
                float: right;
                font-weight: 300;
                font-size: 14px;
                margin-right: -18px;
                word-break: break-all;
                color: #363838;
                margin-top: -24px;
            }
            
            .jst-preview code {
                margin-top: -16px;
                display: block;
            }
            
            @media (max-width: 600px) {
                .jst-preview {
                    width: auto;
                }
                .jst-file {
                    float: left;
                    margin-left: -9px;
                }
            }';
        }

        $stack = self::$stack;

        echo str_replace(
            ['[TYPE]', '[CODE]', '[MESSAGE]', '[MODE]', '[FILE]', '[PREVIEW]', '[TRACE]', '[MESSAGE_ENCODED]'],
            [
                $stack['type'],
                $stack['code'],
                $stack['message'],
                $stack['mode'],
                $stack['file'],
                $stack['preview'],
                nl2br($stack['trace']),
                urlencode($stack['message'] . ' KalipsoCMS')
            ],
            '
            <div class="jst-alert Default [TYPE]">
                <span class="jst-head">
                    [CODE]
                    [TYPE]
                    <a target="_blank" href="https://stackoverflow.com/search?q=[php][MESSAGE_ENCODED]" class="so-link">&#9906;</a>
                </span>
                <span class="jst-head jst-right">
                    [MODE]
                </span>
                <span class="jst-message"><br><br>
                    [MESSAGE]
                </span><br><br>
                <div class="jst-preview">
                    <span class="jst-file">
                        [FILE]
                    </span><br>
                    <code>
                        [PREVIEW]
                    </code>
                </div>
                <div class="jst-trace">
                    [TRACE]
                </div>
                <br>
            </div>');

        return true;
    }
}