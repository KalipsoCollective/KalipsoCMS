<?php

return [

	'services' => [
		'name' => 'module.services',
		'description' => 'module.services_description',
		'icon' => 'ti ti-folders',
		'table' => [
			'id',
			'name',
			'updated_at',
			'updated_by',
			'created_at',
			'created_by'
		],
		'routes' => [
			'listing' => [
				'en' => '/services/:slug',
				'tr' => '/hizmetler/:slug'
			],
			'detail' => [
				'en' => '/services/:slug',
				'tr' => '/hizmetler/:slug'
			]
		],
		'inputs' => [
			'name',
			'slug',
			'ff' => 
			'description',
			'ss'
		]

	]

];