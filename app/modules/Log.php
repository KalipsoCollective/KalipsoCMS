<?php

namespace app\modules;

use app\core\db;

class Log
{

    public function record (array $params)
    {
        /*
        $class = explode('\\', __CLASS__);
        $class = array_pop($class);
        $method = __FUNCTION__;
        echo $class . '/' . $method;
        */

        (new db)->table('logs')
                ->insert([
                    'date'              => time(),
                    'action'            => $params['action'],
                    'route'          => isset( $params['route'] ) !== false ? $params['route'] : '',
                    'endpoint'          => isset( $params['endpoint'] ) !== false ? $params['endpoint'] :
                        $_SERVER['REQUEST_URI'],
                    'http_status'       => isset( $params['http_status'] ) !== false ? $params['http_status'] : 200,
                    'auth_code'         => (new User)->getAuthCode(),
                    'user_id'           => (new User)->getUserId(),
                    'ip'                => getIP(),
                    'header'            => getHeader(),
                    'external_data'    => isset( $params['external'] ) !== false ? $params['external'] : NULL
                ]);
    }
}