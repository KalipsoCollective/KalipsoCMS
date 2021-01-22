<?php

namespace app\modules;

class User
{
    public function __construct($authMiddleware = true)
    {


    }

    public function login (): array
    {
        $class = explode('\\', __CLASS__);
        $class = array_pop($class);
        $method = __FUNCTION__;
        echo $class . '/' . $method;

        varFuck($_SERVER);

        return [

        ];
    }
}