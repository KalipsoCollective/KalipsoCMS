<?php

return [

	'services' => [
		'name' => 'base.services',
		'description' => 'base.services_message',
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
			'name' => [
				'multilanguage' => true,
				'label' => 'base.name',
				'type' => 'input',
				'attributes' => [
					'required' => 'true',
					'data-change' => ''
				],
				'data-onchange' => [

				]
			],
			'slug' => [
				'multilanguage' => true,
				'label' => 'base.slug',
				'type' => 'input',
				'attributes' => ['required' => 'true'],
				'auto_fill'
			],
			'description',
			'icon',
			'content',
			'widget'
		]

	]

];