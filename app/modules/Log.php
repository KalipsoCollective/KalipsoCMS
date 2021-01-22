<?php

namespace app\modules;

use app\core\db;

class Log
{
    public function record ($action, $endPoint = null, $userId = 0, $externalDatas = null, $httpStatus = 200)
    {
        $class = explode('\\', __CLASS__);
        $class = array_pop($class);
        $method = __FUNCTION__;
        echo $class . '/' . $method;

        varFuck($_SERVER);

        (new db)->table('logs')
                ->insert([
                    'date'      => time(),
                    'action'    => $action,
                    'endpoint'  => $endPoint,
                    'ip'        => getIP(),
                    'header'    => getHeader(),


                ]);
    }
}