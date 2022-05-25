<?php

/**
 * We use this file to specify authorization points and language definitions for display.
 * ex: [endpoint] => ['default' => (checked status), 'name' => (language definition for display)]
 */

return [
	// Basic Auth
	'auth' => [
		'default' => true,
		'name' => 'auth.auth',
	],
	'auth/:action' => [
		'default' => true,
		'name' => 'auth.auth_action',
	],
	'auth/logout' => [
		'default' => true,
		'name' => 'auth.auth_logout',
	],

	// Management
	'management' => [
		'default' => false,
		'name' => 'auth.management',
	],
	'management/users' => [
		'default' => false,
		'name' => 'auth.management_users',
	],
	'management/users/list' => [
		'default' => false,
		'name' => 'auth.management_users_list',
	],
	'management/users/add' => [
		'default' => false,
		'name' => 'auth.management_users_add',
	],
	'management/users/:id' => [
		'default' => false,
		'name' => 'auth.management_users_detail',
	],
	'management/users/:id/update' => [
		'default' => false,
		'name' => 'auth.management_users_update',
	],
	'management/users/:id/delete' => [
		'default' => false,
		'name' => 'auth.management_users_delete',
	],
	'management/roles' => [
		'default' => false,
		'name' => 'auth.management_roles',
	],
	'management/roles/list' => [
		'default' => false,
		'name' => 'auth.management_roles_list',
	],
	'management/roles/add' => [
		'default' => false,
		'name' => 'auth.management_roles_add',
	],
	'management/roles/:id' => [
		'default' => false,
		'name' => 'auth.management_roles_detail',
	],
	'management/roles/:id/delete' => [
		'default' => false,
		'name' => 'auth.management_roles_delete',
	],
	'management/roles/:id/update' => [
		'default' => false,
		'name' => 'auth.management_roles_update',
	],
	'management/sessions' => [
		'default' => false,
		'name' => 'auth.management_sessions',
	],
	'management/sessions/list' => [
		'default' => false,
		'name' => 'auth.management_sessions_list',
	],
	'management/schemas' => [
		'default' => false,
		'name' => 'auth.management_schemas',
	],
	'management/schemas/list' => [
		'default' => false,
		'name' => 'auth.management_schemas_list',
	],
	'management/schemas/add' => [
		'default' => false,
		'name' => 'auth.management_schemas_add',
	],
	'management/schemas/:id' => [
		'default' => false,
		'name' => 'auth.management_schemas_detail',
	],
	'management/schemas/:id/update' => [
		'default' => false,
		'name' => 'auth.management_schemas_update',
	],
	'management/schemas/:id/delete' => [
		'default' => false,
		'name' => 'auth.management_schemas_delete',
	],

	'management/contents' => [
		'default' => false,
		'name' => 'auth.management_contents',
	],
	'management/contents/list' => [
		'default' => false,
		'name' => 'auth.management_contents_list',
	],
	'management/contents/add' => [
		'default' => false,
		'name' => 'auth.management_contents_add',
	],
	'management/contents/:id' => [
		'default' => false,
		'name' => 'auth.management_contents_detail',
	],
	'management/contents/:id/update' => [
		'default' => false,
		'name' => 'auth.management_contents_update',
	],
	'management/contents/:id/delete' => [
		'default' => false,
		'name' => 'auth.management_contents_delete',
	],


	'management/logs' => [
		'default' => false,
		'name' => 'auth.management_logs',
	],
	'management/logs/list' => [
		'default' => false,
		'name' => 'auth.management_logs_list',
	],
	'management/logs/:ip/block' => [
		'default' => false,
		'name' => 'auth.management_logs_ip_block',
	],
	'management/settings' => [
		'default' => false,
		'name' => 'auth.management_settings',
	],
	'management/settings/update' => [
		'default' => false,
		'name' => 'auth.management_settings_update',
	],
];