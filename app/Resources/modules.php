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
			'title' => [
				'multilanguage' => true,
				'label' => 'base.title',
				'type' => 'input',
				'attributes' => [
					'required' => 'true',
					'data-kn-change' => 'slug-check',
				],
			],
			'slug' => [
				'multilanguage' => true,
				'label' => 'base.slug',
				'type' => 'input',
				'attributes' => ['required' => 'true'],
			],
			'description' => [
				'multilanguage' => true,
				'label' => 'base.description',
				'type' => 'input',
				'attributes' => ['required' => 'true'],
			],
			'content' => [
				'multilanguage' => true,
				'label' => 'base.description',
				'type' => 'editor',
				'attributes' => ['required' => 'true'],
			],
			'header_image' => [
				'label' => 'base.header_image',
				'type' => 'file',
				'col' => 'col-12',
				'attributes' => [
					'accept' => 'image/*',
				],
				'external_parameters' => [
					'size' => [
						'original' => [1920, 400]
					],
				]
			],
			'icon' => [
				'label' => 'base.icon',
				'type' => 'input',
				'attributes' => ['required' => 'true'],
			],
			'widget' => [
				'countries' => [
					'label' => 'base.countries',
					'type' => 'select',
					'source' => ['getModuleDatas', ['countries']],
					'use_for_view' => 'title',
					'attributes' => ['required' => 'true'],
				]
			],
		]

	],
	'other-services' => [
		'name' => 'base.other_services',
		'description' => 'base.other_services_message',
		'icon' => 'ti ti-folder',
		'table' => [
			'id',
			'name',
			'updated_at',
			'updated_by',
			'created_at',
			'created_by'
		],
		'routes' => [
			'listing' => false,
			'detail' => false
		],
		'inputs' => [
			'title' => [
				'multilanguage' => true,
				'label' => 'base.title',
				'type' => 'input',
				'attributes' => [
					'required' => 'true',
				],
			],
			'content' => [
				'multilanguage' => true,
				'label' => 'base.description',
				'type' => 'textarea',
				'attributes' => ['required' => 'true'],
			],
			'icon' => [
				'label' => 'base.icon',
				'col' => 'col-12',
				'type' => 'input',
				'attributes' => ['required' => 'true'],
			],
		]
	],
	'countries' => [
		'name' => 'base.countries',
		'description' => 'base.countries_message',
		'icon' => 'ti ti-globe',
		'table' => [
			'id',
			'name',
			'updated_at',
			'updated_by',
			'created_at',
			'created_by'
		],
		'routes' => [
			'listing' => false,
			'detail' => false
		],
		'inputs' => [
			'title' => [
				'multilanguage' => true,
				'label' => 'base.title',
				'type' => 'input',
				'attributes' => [
					'required' => 'true',
				],
			],
			'content' => [
				'multilanguage' => true,
				'label' => 'base.description',
				'type' => 'editor',
				'attributes' => ['required' => 'true'],
			],
			'flag' => [
				'label' => 'base.flag',
				'type' => 'file',
				'attributes' => [
					'accept' => 'image/*',
					'required' => 'true',
				],
				'external_parameters' => [
					'size' => [
						'original' => [200, 200]
					],
				]
			],
			'campus_image' => [
				'label' => 'base.image',
				'type' => 'file',
				'attributes' => [
					'accept' => 'image/*',
					'required' => 'true',
				],
				'external_parameters' => [
					'size' => [
						'original' => [300, 300]
					],
					// 'max_size' => 1024, // 1KB
					// 'convert' => 'png',
				]
			],
		]

	],

];