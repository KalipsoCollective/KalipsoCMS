<?php

/*
 * Route Schema
 *
 */

return [

    '' => [
        'install' => [
            'file'      => 'install',
            'title'     => 'd',
            'auth'      => false,
            'menu'      => false,
        ],
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
            'title'     => 'd',
            'page_parts' => [
                'head',
                '_',
                'end'
            ],
            'auth'      => false,
            'menu'      => false,
        ],
        'index' => [
            'file'      => 'home',
            'title'     => '',
            'auth'      => true,
            'menu'      => true,
        ],
    ]

];