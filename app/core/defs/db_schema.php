<?php

/**
 * Database Structure
 *
 * The schema here is used to add new tables or to add new columns to existing tables.
 * Column parameters is as follows.
 *
 * > type:          Type parameters(required) -> (INT | VARCHAR | TEXT | DATE | ENUM | ...)
 * > limit:         Maximum length of column.
 * > nullable:      True if it is an empty field.
 * > auto_inc:      True if it is an auto increment field.
 * > attr:          Attribute parameters -> (BINARY | UNSIGNED | UNSIGNED ZEROFILL | ON UPDATE CURRENT_TIMESTAMP)
 * > type_values:   ENUM -> ['on', 'off'] | INT, VARCHAR -> 255
 * > default:       Default value -> NULL, 'string' or CURRENT_TIMESTAMP
 * > index:         Index type -> (INDEX | PRIMARY | UNIQUE | FULLTEXT)
 */

return [
    'tables' => [

        /* Users Table */
        'users' => [
            'cols' => [
                'id' => [
                    'type'          => 'int',
                    'auto_inc'      => true,
                    'attr'          => 'UNSIGNED',
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
                    'type_values'   => 80,
                    'index'         => 'INDEX'
                ],
                'l_name' => [
                    'type'          => 'varchar',
                    'type_values'   => 80,
                    'index'         => 'INDEX'
                ],
                'email' => [
                    'type'          => 'varchar',
                    'type_values'   => 80,
                    'index'         => 'INDEX'
                ],
                'password' => [
                    'type'          => 'varchar',
                    'type_values'   => 120,
                    'index'         => 'INDEX'
                ],
                'token' => [
                    'type'          => 'varchar',
                    'type_values'   => 48,
                    'index'         => 'INDEX'
                ],
                'auth_group_id' => [
                    'type'          => 'int',
                    'type_values'   => 10,
                    'index'         => 'INDEX'
                ],
                'created_at' => [
                    'type'          => 'varchar',
                    'type_values'   => 80,
                    'index'         => 'INDEX'
                ],
                'created_by' => [
                    'type'          => 'int',
                    'type_values'   => 10,
                    'index'         => 'INDEX'
                ],
                'updated_at' => [
                    'type'          => 'varchar',
                    'type_values'   => 80,
                    'nullable'      => true,
                    'default'       => 'NULL',
                    'index'         => 'INDEX'
                ],
                'updated_by' => [
                    'type'          => 'int',
                    'type_values'   => 10,
                    'nullable'      => true,
                    'default'       => 'NULL',
                    'index'         => 'INDEX'
                ],
                'status' => [
                    'type'          => 'enum',
                    'type_values'   => ['active', 'deleted'],
                    'default'       => 'active',
                    'index'         => 'INDEX'
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
                    'index'         => 'INDEX',
                ],
                'view_checkpoints' => [
                    'type'          => 'text',
                    'nullable'      => true
                ],
                'action_checkpoints' => [
                    'type'          => 'text',
                    'nullable'      => true
                ],
                'created_at' => [
                    'type'          => 'varchar',
                    'type_values'   => 80,
                    'index'         => 'INDEX'
                ],
                'created_by' => [
                    'type'          => 'int',
                    'type_values'   => 10,
                    'index'         => 'INDEX'
                ],
                'updated_at' => [
                    'type'          => 'varchar',
                    'type_values'   => 80,
                    'nullable'      => true,
                    'default'       => 'NULL',
                    'index'         => 'INDEX'
                ],
                'updated_by' => [
                    'type'          => 'int',
                    'type_values'   => 10,
                    'nullable'      => true,
                    'default'       => 'NULL',
                    'index'         => 'INDEX'
                ],
                'status' => [
                    'type'          => 'enum',
                    'type_values'   => ['active', 'deleted'],
                    'default'       => 'active',
                    'index'         => 'INDEX'
                ],
            ],
        ],
    ],
    'table_values' => [
        'charset'   => 'utf8mb4', // You can use 'utf8' if the structure is causing problems.
        'collate'   => 'utf8mb4_unicode_520_ci', // You can use 'utf8_general_ci' if the structure is causing problems.
        'engine'    => 'InnoDB'
    ],
    'data'  => [
        'users' => [
            [
                'u_name'                => 'root',
                'f_name'                => 'Website',
                'l_name'                => 'Admin',
                'email'                 => 'hello@koalapix.com',
                'password'              => '$2y$10$1i5w0tYbExemlpAAsospSOZ.n06NELYooYa5UJhdytvBEn85U8lly', // 1234
                'token'                 => 'Hl7kojH2fLdsbMUO8T0lZdTcMwCjvOGIbBk8cndJSsh2IcpN',
                'auth_group_id'         => '1',
                'created_at'            => 1611231432,
                'created_by'            => 1,
                'status'                => 'active'
            ]
        ],
        'auth_groups' => [
            [
                'name'                  => 'admin',
                'view_checkpoints'      => 'admin,login',
                'action_checkpoints'    => 'Content/addContent,Content/editContent',
                'created_at'            => 1611231432,
                'created_by'            => 1,
                'status'                => 'active'
            ]
        ]
    ],
];