<?php

/*
 * Route Schema
 *
 */

return [

    '' => [
        'login' => [
            'file'      => 'login',
            'title'     => 'd',
            'auth'      => false,
            'menu'      => false,
        ],
        'lost_pass' => [
            'file'      => 'lost_pass',
            'title'     => 'd',
            'auth'      => false,
            'menu'      => false,
        ],
        'register' => [
            'file'      => 'register',
            'title'     => 'd',
            'auth'      => false,
            'menu'      => false,
        ]
    ],
    'admin' => [
        'login' => [
            'file'      => 'login',
            'title'     => 'login',
            'page_parts' => [
                'head',
                '_',
                'end'
            ],
            'auth'      => false,
            'menu'      => false,
        ],
        'index' => [
            'title'     => 'dashboard',
            'auth'      => true,
            'menu'      => true,
        ],
    ]

];