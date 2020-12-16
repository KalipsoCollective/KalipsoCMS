<?php

/**
 * Database Structure
 *
 * The schema here is used to add new tables or to add new columns to existing tables.
 * Column parameters is as follows.
 *
 * > type:          Type parameters -> (INT | VARCHAR | TEXT | DATE | ENUM | ...)
 * > limit:         Maximum length of column.
 * > nullable:      True if it is an empty field.
 * > auto_inc:      True if it is an auto increment field.
 * > attr:          Attribute parameters -> (BINARY | UNSIGNED | UNSIGNED ZEROFILL | ON UPDATE CURRENT_TIMESTAMP)
 * > type_values:   ENUM -> ['on', 'off'] | INT, VARCHAR -> 255
 * > default:       Default value
 */

return [
    'tables' => [

        /* Users Table */

        'users' => [
            'cols' => [
                'id' => [
                    'type'          => 'int',
                    'nullable'      => false, // NOT NULL
                    'auto_inc'      => true,
                    'default'       => null,
                    'attr'          => 'unsigned',
                    'type_values'   => 11,
                ]
            ],

        ],
        'primary_key' => 'id'
    ],
    'table_values' => [
        'charset'   => 'utf8mb4', // You can use 'utf8' if the structure is causing problems.
        'collate'   => 'utf8mb4_unicode_520_ci', // You can use 'utf8_general_ci' if the structure is causing problems.
        'engine'    => 'InnoDB'
    ]
];