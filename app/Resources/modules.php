<?php

use KN\Helpers\Base;

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
		'columns' => [
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
            'id' => [
                'primary' => true,
            ],
            'u_name' => [],
            'name' => [
                'exclude' => true,
                'formatter' => function($row) {

                    $name = trim($row->f_name . ' ' . $row->l_name);
                    return $name == '' ? '-' : $name;
                }
            ],
            'email' => [],
            'birth_date' => [],
            'role' => [],
            'created' => [],
            'updated' => [],
            'status' => [
                'formatter' => function ($row) {

                    switch ($row->status) {
                        case 'deleted':
                            $status = 'text-danger';
                            break;

                        case 'passive':
                            $status = 'text-warning';
                            break;
                            
                        default:
                            $status = 'text-success';
                            break;
                    }

                    return '<span class="' . $status . '">' . Base::lang('base.' . $row->status) . '</span>';

                }
            ]
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
		'from' => '(SELECT 
                        x.id, 
                        IFNULL(JSON_UNQUOTE(JSON_EXTRACT(x.input, \'$.title.'.Base::lang('lang.code').'\')), "-") AS title,
                        IFNULL(JSON_UNQUOTE(JSON_EXTRACT(x.input, \'$.content.'.Base::lang('lang.code').'\')), "-") AS content, 
                        IFNULL(JSON_UNQUOTE(JSON_EXTRACT(x.input, \'$.flag\')), 0) AS flag,
                        IFNULL(JSON_UNQUOTE(JSON_EXTRACT(x.input, \'$.campus_image\')), 0) AS campus_image,
                        (SELECT files FROM files WHERE id = flag) AS flag_src,
                        (SELECT files FROM files WHERE id = campus_image) AS image_src,
                        FROM_UNIXTIME(x.created_at, "%Y.%m.%d %H:%i") AS created,
                        IFNULL(FROM_UNIXTIME(x.updated_at, "%Y.%m.%d"), "-") AS updated
                    FROM `contents` x WHERE x.module = "countries") AS raw',
		'table' => [
			'id' => [
                'primary' => true,
            ],
            'title' => [],
            'content' => [
                'formatter' => function($row) {

                    $content = Base::stringShortener(trim(strip_tags(htmlspecialchars_decode($row->content))), 100);
                    return $content == '' ? '-' : $content;
                }
            ],
            'flag_src' => [
                'formatter' => function($row) {
                	$return = '';
                	if ($row->flag_src AND $src = @json_decode($row->flag_src)) {
                		$src = Base::base('upload/' . (isset($src->sm) !== false ? $src->sm : $src->original));
                		$return = '<img class="table-image" src="' . $src . '" />';
                	} else {
                		$return = '-';
                	}
                    return $return;
                }
            ],
            'image_src' => [
                'formatter' => function($row) {
                	$return = '';
                	if ($row->image_src AND $src = @json_decode($row->image_src)) {
                		$src = Base::base('upload/' . (isset($src->sm) !== false ? $src->sm : $src->original));
                		$return = '<img class="table-image" src="' . $src . '" />';
                	} else {
                		$return = '-';
                	}
                    return $return;
                }
            ],
            'email' => [],
            'birth_date' => [],
            'role' => [],
            'created' => [],
            'updated' => [],
		],
		'columns' => [
			[
				"searchable" => [
					"type" => "number",
					"min" => 1,
					"max" => 999
				],
				"orderable"=> true,
				"title" => "#",
				"key" => "id"
			],
			[
				"searchable" => [
					"type" => "text",
					"maxlength" => 50
				],
				"orderable" => true,
				"title" => Base::lang('base.title'),
				"key" => "title"
			],
			[
				"searchable" => [
					"type" => "text",
					"maxlength" => 50
				],
				"orderable" => true,
				"title" => Base::lang('base.content'),
				"key" => "content"
			],
			[
				"searchable" => false,
				"orderable" => false,
				"title" => Base::lang('base.flag'),
				"key" => "flag_src"
			],
			[
				"searchable" => false,
				"orderable" => false,
				"title" => Base::lang('base.image'),
				"key" => "image_src"
			],
			[
				"searchable" => [
					"type" => "date",
					"maxlength" => 50
				],
				"orderable" => true,
				"title" => Base::lang('base.created_at'),
				"key" => "created"
			],
			[
				"searchable" => [
					"type" => "date",
					"maxlength" => 50
				],
				"orderable" => true,
				"title" => Base::lang('base.updated_at'),
				"key" => "updated"
			],
			[
				"searchable" => false,
				"orderable" => false,
				"title" => Base::lang('base.action'),
				"key" => "action"
			]
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