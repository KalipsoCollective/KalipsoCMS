<?php

namespace app\modules;

use app\core\db;

class Content
{

    public function __construct()
    {
        global $authorityPoints;

        $authorityPoints['action']['User/login'] = [
            'name'      => 'login',
            'default'   => true,
            'view'      => false,
        ];
    }

}