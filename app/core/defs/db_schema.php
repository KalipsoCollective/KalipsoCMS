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
                    'auto_inc'      => true,
                    'attr'          => 'unsigned',
                    'type_values'   => 11,
                    'index'         => 'PRIMARY'
                ],
                'u_name' => [
                    'type'          => 'varchar',
                    'type_values'   => 80,
                    'index'         => 'UNIQUE'
                ],
                'f_name' => [
                    'type'          => 'varchar',
                    'type_values'   => 80
                ],
                'l_name' => [
                    'type'          => 'varchar',
                    'type_values'   => 80
                ],
                'email' => [
                    'type'          => 'varchar',
                    'type_values'   => 80
                ],
                'password' => [
                    'type'          => 'varchar',
                    'type_values'   => 120
                ],
                'token' => [
                    'type'          => 'varchar',
                    'type_values'   => 48
                ],
                'auth_group_id' => [
                    'type'          => 'int',
                    'type_values'   => 10
                ],
                'created_at' => [
                    'type'          => 'varchar',
                    'type_values'   => 80
                ],
                'created_by' => [
                    'type'          => 'int',
                    'type_values'   => 10
                ],
                'updated_at' => [
                    'type'          => 'varchar',
                    'type_values'   => 80,
                    'nullable'      => true,
                    'default'       => 'NULL'
                ],
                'updated_by' => [
                    'type'          => 'int',
                    'type_values'   => 10,
                    'nullable'      => true,
                    'default'       => 'NULL'
                ],
                'status' => [
                    'type'          => 'enum',
                    'type_values'   => ['active', 'deleted'],
                    'default'       => 'active'
                ],
            ],
        ],

        /* Auth Groups Table */
        'auth_groups' => [
            'cols' => [
                'id' => [
                    'type'          => 'int',
                    'auto_inc'      => true,
                    'attr'          => 'unsigned',
                    'type_values'   => 11,
                    'index'         => 'PRIMARY'
                ],
                'name' => [
                    'type'          => 'varchar',
                    'type_values'   => 80,
                ],
                'view_checkpoints' => [
                    'type'          => 'text',
                    'nullable'      => true
                ],
                'action_checkpoints' => [
                    'type'          => 'text',
                    'nullable'      => true
                ],
                'created_by' => [
                    'type'          => 'int',
                    'type_values'   => 10
                ],
                'updated_at' => [
                    'type'          => 'varchar',
                    'type_values'   => 80,
                    'nullable'      => true,
                    'default'       => 'NULL',
                ],
                'updated_by' => [
                    'type'          => 'int',
                    'type_values'   => 10,
                    'nullable'      => true,
                    'default'       => 'NULL',
                ],
                'status' => [
                    'type'          => 'enum',
                    'type_values'   => ['active', 'deleted'],
                    'default'       => 'active'
                ],
            ],
        ],
    ],
    'table_values' => [
        'charset'   => 'utf8mb4', // You can use 'utf8' if the structure is causing problems.
        'collate'   => 'utf8mb4_unicode_520_ci', // You can use 'utf8_general_ci' if the structure is causing problems.
        'engine'    => 'InnoDB'
    ]
];