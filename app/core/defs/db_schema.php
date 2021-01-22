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
                    'index'         => 'UNIQUE'
                ],
                'password' => [
                    'type'          => 'varchar',
                    'type_values'   => 120,
                ],
                'token' => [
                    'type'          => 'varchar',
                    'type_values'   => 48,
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
                'view_points' => [
                    'type'          => 'text',
                    'nullable'      => true
                ],
                'action_points' => [
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
                    'default'       => 'active',
                    'index'         => 'INDEX'
                ],
            ],
        ],

        /* Sessions Table */
        'sessions' => [
            'cols' => [
                'id' => [
                    'type'          => 'int',
                    'auto_inc'      => true,
                    'attr'          => 'unsigned',
                    'type_values'   => 11,
                    'index'         => 'PRIMARY'
                ],
                'auth_code' => [
                    'type'          => 'varchar',
                    'type_values'   => 50,
                    'index'         => 'INDEX',
                ],
                'user_id' => [
                    'type'          => 'int',
                    'index'         => 'INDEX',
                ],
                'header' => [
                    'type'          => 'varchar',
                    'type_values'   => 250,
                ],
                'ip' => [
                    'type'          => 'varchar',
                    'type_values'   => 250,
                ],
                'auth_group_id' => [
                    'type'          => 'int',
                    'index'         => 'INDEX',
                ],
                'update_session' => [
                    'type'          => 'enum',
                    'type_values'   => ['true', 'false'],
                    'default'       => 'false',
                    'index'         => 'INDEX'
                ],
                'last_action_date' => [
                    'type'          => 'varchar',
                    'type_values'   => 80,
                ],
                'last_action_point' => [
                    'type'          => 'varchar',
                    'type_values'   => 250,
                ]
            ]
        ],

        /* Contents Table */
        'contents' => [
            'cols' => [
                'id' => [
                    'type'          => 'int',
                    'auto_inc'      => true,
                    'attr'          => 'unsigned',
                    'type_values'   => 11,
                    'index'         => 'PRIMARY'
                ],
                'type' => [
                    'type'          => 'enum',
                    'type_values'   => ['page', 'blog', 'part'],
                    'default'       => 'blog',
                    'index'         => 'INDEX'
                ],
                'content' => [
                    'type'          => 'text',
                    'index'         => 'FULLTEXT',
                ],
                'title' => [
                    'type'          => 'varchar',
                    'type_values'   => 250,
                    'index'         => 'INDEX',
                ],
                'category_id' => [
                    'type'          => 'int',
                    'index'         => 'INDEX',
                    'default'       => '0'
                ],
                'slug' => [
                    'type'          => 'varchar',
                    'type_values'   => 250,
                    'index'         => 'UNIQUE',
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
                    'type_values'   => ['active', 'draft', 'deleted'],
                    'default'       => 'draft',
                    'index'         => 'INDEX'
                ],
            ]
        ],
        /* Contents Table */
        'logs' => [
            'cols' => [
                'id' => [
                    'type'          => 'int',
                    'auto_inc'      => true,
                    'attr'          => 'unsigned',
                    'type_values'   => 11,
                    'index'         => 'PRIMARY'
                ],
                'date' => [
                    'type'          => 'varchar',
                    'type_values'   => 80,
                ],
                'action' => [
                    'type'          => 'varchar',
                    'type_values'   => 250,
                ],
                'endpoint' => [
                    'type'          => 'text',
                ],
                'http_status' => [
                    'type'          => 'int',
                ],
                'auth_code' => [
                    'type'          => 'varchar',
                    'type_values'   => 80,
                ],
                'user_id' => [
                    'type'          => 'int',
                    'nullable'      => true,
                    'default'       => 0
                ],
                'ip' => [
                    'type'          => 'varchar',
                    'type_values'   => 80,
                ],
                'external_data' => [
                    'type'          => 'text',
                    'nullable'      => true,
                    'default'       => 'NULL'
                ]
            ]
        ]
    ],
    'table_values' => [
        'charset'   => 'utf8mb4', // You can use 'utf8' if the structure is causing problems.
        'collate'   => 'utf8mb4_unicode_520_ci', // You can use 'utf8_general_ci' if the structure is causing problems.
        'engine'    => 'InnoDB',
        'specific'  => [ // You can give specific value.
            'sessions' => [
                'engine'    => 'MEMORY'
            ]
        ]
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
                'view_points'           => 'admin,login',
                'action_points'         => 'Content/addContent,Content/editContent',
                'created_at'            => 1611231432,
                'created_by'            => 1,
                'status'                => 'active'
            ]
        ]
    ],
];