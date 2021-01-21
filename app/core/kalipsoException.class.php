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
     * @var bool
     */

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
     * @param object|null $e
     *                  string $e->getMessage()       → exception message
     *                  int    $e->getCode()          → exception code
     *                  string $e->getFile()          → file
     *                  int    $e->getLine()          → line
     *                  string $e->getTraceAsString() → trace as string
     *                  int    $e->statusCode         → HTTP response status code
     * @return bool
     */
    public function exception(object $e = null): ?bool
    {
        if (! is_null($e)) {
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

        } else {

            return null;

        }
    }

    /**
     * Handle error catch.
     *
     * @param int $code → error code
     * @param int|string|null $msg → error message
     * @param string|null $file → error file
     * @param int $line → error line
     *
     * @return bool
     */
    public function error(int $code, ?string $msg, ?string $file, int $line): bool
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
                return self::$stack['type'] = 'warning'; // 2
            case E_PARSE:
                return self::$stack['type'] = 'parse'; // 4
            case E_NOTICE:
                return self::$stack['type'] = 'notice'; // 8
            case E_CORE_ERROR:
                return self::$stack['type'] = 'core-error'; // 16
            case E_CORE_WARNING:
                return self::$stack['type'] = 'core warning'; // 32
            case E_COMPILE_ERROR:
                return self::$stack['type'] = 'compile error'; // 64
            case E_COMPILE_WARNING:
                return self::$stack['type'] = 'compile warning'; // 128
            case E_USER_ERROR:
                return self::$stack['type'] = 'user error'; // 256
            case E_USER_WARNING:
                return self::$stack['type'] = 'user warning'; // 512
            case E_USER_NOTICE:
                return self::$stack['type'] = 'user notice'; // 1024
            case E_STRICT:
                return self::$stack['type'] = 'strict'; // 2048
            case E_RECOVERABLE_ERROR:
                return self::$stack['type'] = 'recoverable error'; // 4096
            case E_DEPRECATED:
                return self::$stack['type'] = 'deprecated'; // 8192
            case E_USER_DEPRECATED:
                return self::$stack['type'] = 'user deprecated'; // 16384
            default:
                return self::$stack['type'] = 'error';
        }
    }

    /**
     * Handle error catch.
     *
     * @param string $type
     * @param int $code → exception/error code
     * @param int|string|null $msg → exception/error message
     * @param string|null $file → exception/error file
     * @param int $line → exception/error line
     * @param ?string $trace → exception/error trace
     * @param ?int $http → HTTP response status code
     *
     * @return array → stack
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
                    "<span class='kpx-line'>" . ($i + 1) . '</span>' .
                    "<span class='kpx-mark text'>" . $text . '</span><br>';
                continue;
            }

            self::$stack['preview'] .=
                "<span class='kpx-line'>" . ($i + 1) . '</span>' .
                "<span class='text'>" . $text . '</span><br>';
        }
    }

    /**
     * Renderization.
     *
     * @return bool
     */
    protected function render(): bool
    {
        self::$stack['mode'] = defined('HHVM_VERSION') ? 'HHVM' : 'PHP';

        $this->getPreviewCode();

        if (! self::$styles) {
            self::$styles = true;
            self::$stack['css'] = '
            ::-webkit-scrollbar {
                width: 5px;
                height: 8px;
                background-color: #aaa;
            }
            
            ::-webkit-scrollbar-thumb {
                background: #000;
            }
            
            body {
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
                background: #222222
            }
            
            hr {
                width: 30%;
                border-color: rgb(255 255 255 / 20%);
            }
            
            .kpx-alert {
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
            
            .kpx-right {
                color: rgba(255, 255, 255, 0.4) !important;
                float: right;
            }
            
            .kpx-head {
                font-size: 18px;
                top: 18px;
                font-weight: 600;
            }
            
            .default {
                background-color: #9a3b3b;
            }
            
            .error {
                background-color: #c3251d;
            }
            
            .warning {
                background-color: #d2701d;
            }
            
            .notice {
                background-color: #4f7241;
            }
            
            .kpx-mark {
                background: #464646;
                padding: 4px;
                margin-left: -4px;
                color: #fff;
            }
            
            .kpx-preview {
                width: 85%;
                margin-left: auto;
                margin-right: auto;
                overflow-x: auto;
                white-space: nowrap;
                background: #222222;
                color: #6b6b6b;
                padding: 2pc;
                border-radius: 20px 20px 0px 0px;
            }
            
            .so-link {
                text-decoration: none;
                opacity: 0.5;
                cursor: pointer;
                display: inline-block;
                font-weight: inherit;
                -ms-transform: rotate(-35deg);
                transform: rotate(-35deg);
                -webkit-transform: rotate(35deg);
                -moz-transform: rotate(-35deg);
                -o-transform: rotate(-35deg);
                font-size: 23px;
                -webkit-transition: 0.2s ease-out;
                -o-transition: 0.2s ease-out;
                transition: 0.2s ease-out
            }
            
            .so-link:hover {
                opacity: 1;
            }
            
            .kpx-line {
                margin-left: -10px;
                background: rgba(0, 0, 0, .20);
                padding: 2px 2px;
                margin-bottom: 2px;
                margin-right: 14px;
                width: 44px;
                display: inline-table;
                text-align: center;
            }
            
            .kpx-trace {
                font-weight: 300;
                padding: 0pc 2pc 0pc 2pc;
            }
            
            .kpx-file {
                float: right;
                font-weight: 300;
                font-size: 14px;
                margin-right: -18px;
                word-break: break-all;
                color: #737373;
                margin-top: -24px;
            }
            
            .kpx-preview code {
                font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
                margin-top: -16px;
                display: block;
            }
            
            @media (max-width: 600px) {
                .kpx-preview {
                    width: auto;
                }
                .kpx-file {
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
            <!doctype html>
            </html lang="en">
                <head>
                    <title>Kalipso Exception</title>
                    <meta charset="UTF-8">
                    <style>
                        '.$stack['css'].'
                    </style>
                </head>
                <body>
                    <div class="kpx-alert default [TYPE]">
                        <span class="kpx-head">
                            [CODE]
                            [TYPE]
                            <a target="_blank" href="https://stackoverflow.com/search?q=[php][MESSAGE_ENCODED]" 
                            class="so-link">🔗</a>
                        </span>
                        <span class="kpx-head kpx-right">
                            [MODE]
                        </span>
                        <span class="kpx-message"><br><br>
                            [MESSAGE]
                        </span><br><br>
                        <div class="kpx-preview">
                            <span class="kpx-file">
                                [FILE]
                            </span><br>
                            <code>
                                [PREVIEW]
                            </code>
                        </div>
                        <div class="kpx-trace">
                            [TRACE]
                        </div>
                        <br>
                    </div>
                </body>
            </html>');

        return true;
    }
}