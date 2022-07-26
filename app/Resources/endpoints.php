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
	'management/sessions/:id/delete' => [
		'default' => false,
		'name' => 'auth.management_sessions_delete',
	],
	'management/icon-picker' => [
		'default' => true,
		'name' => 'auth.management_icon_picker',
	],
	'management/:module' => [
		'default' => false,
		'name' => 'auth.management_contents',
	],
	'management/:module/list' => [
		'default' => false,
		'name' => 'auth.management_contents_list',
	],
	'management/:module/add' => [
		'default' => false,
		'name' => 'auth.management_contents_add',
	],
	'management/:module/:id' => [
		'default' => false,
		'name' => 'auth.management_contents_detail',
	],
	'management/:module/:id/update' => [
		'default' => false,
		'name' => 'auth.management_contents_update',
	],
	'management/:module/:id/delete' => [
		'default' => false,
		'name' => 'auth.management_contents_delete',
	],
	'management/:module/slug' => [
		'default' => true,
		'name' => 'auth.management_contents_slug',
	],
	'management/:module/autocomplete' => [
		'default' => true,
		'name' => 'auth.management_contents_autocomplete',
	],
	'management/content/:module/upload-file' => [
		'default' => false,
		'name' => 'auth.management_content_upload_file',
	],
	'management/media' => [
		'default' => false,
		'name' => 'auth.management_media',
	],
	'management/media/list' => [
		'default' => false,
		'name' => 'auth.management_media_list',
	],
	'management/media/add' => [
		'default' => false,
		'name' => 'auth.management_media_add',
	],
	'management/media/:id' => [
		'default' => false,
		'name' => 'auth.management_media_detail',
	],
	'management/media/:id/update' => [
		'default' => false,
		'name' => 'auth.management_media_update',
	],
	'management/media/:id/delete' => [
		'default' => false,
		'name' => 'auth.management_media_delete',
	],
	'management/menus' => [
		'default' => false,
		'name' => 'auth.management_menu',
	],
	'management/menus/list' => [
		'default' => false,
		'name' => 'auth.management_menu_list',
	],
	'management/menus/add' => [
		'default' => false,
		'name' => 'auth.management_menu_add',
	],
	'management/menus/:id' => [
		'default' => false,
		'name' => 'auth.management_menu_detail',
	],
	'management/menus/:id/update' => [
		'default' => false,
		'name' => 'auth.management_menu_update',
	],
	'management/menus/:id/delete' => [
		'default' => false,
		'name' => 'auth.management_menu_delete',
	],
	'management/menus/get-menu-params' => [
		'default' => false,
		'name' => 'auth.management_menu_get_params',
	],
	'management/forms/:form' => [
		'default' => false,
		'name' => 'auth.management_forms',
	],
	'management/forms/:form/list' => [
		'default' => false,
		'name' => 'auth.management_forms_list',
	],
	'management/forms/:form/:id' => [
		'default' => false,
		'name' => 'auth.management_forms_detail',
	],
	'management/forms/:form/:id/update' => [
		'default' => false,
		'name' => 'auth.management_forms_update',
	],
	'management/forms/:form/:id/delete' => [
		'default' => false,
		'name' => 'auth.management_forms_delete',
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