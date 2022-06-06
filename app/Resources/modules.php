<?php

use KN\Helpers\Base;

return [

	'services' => [
		'name' => 'base.services',
		'description' => 'base.services_message',
		'icon' => 'ti ti-folders',
		'from' => '(SELECT 
						x.id, 
						IFNULL(JSON_UNQUOTE(JSON_EXTRACT(x.input, \'$.title.'.Base::lang('lang.code').'\')), "-") AS title,
						IFNULL(JSON_UNQUOTE(JSON_EXTRACT(x.input, \'$.content.'.Base::lang('lang.code').'\')), "-") AS slug,
						IFNULL(JSON_UNQUOTE(JSON_EXTRACT(x.input, \'$.description.'.Base::lang('lang.code').'\')), "-") AS description,
						IFNULL(JSON_UNQUOTE(JSON_EXTRACT(x.input, \'$.content.'.Base::lang('lang.code').'\')), "-") AS content, 
						IFNULL(JSON_UNQUOTE(JSON_EXTRACT(x.input, \'$.icon\')), 0) AS icon,
						REPLACE(
							REPLACE(
								REPLACE(
									REPLACE(
										IFNULL(JSON_EXTRACT(x.input, \'$.header_image\'), ""),
										" ",
										""
									),
									"\"",
									""
								),
								"]",
								""
							),
							"[",
							""
						) AS header_image,
						(SELECT JSON_ARRAYAGG(files) AS files FROM files WHERE FIND_IN_SET(id, header_image)) AS header_image_src,
						FROM_UNIXTIME(x.created_at, "%Y.%m.%d %H:%i") AS created,
						IFNULL(FROM_UNIXTIME(x.updated_at, "%Y.%m.%d"), "-") AS updated
					FROM `contents` x WHERE x.module = "services") AS raw',
		'table' => [
			'id' => [
				'primary' => true,
			],
			'title' => [],
			'description' => [
				'formatter' => function($row) {

					$description = Base::stringShortener($row->description, 100);
					return $description == '' ? '-' : $description;
				}
			],
			'content' => [
				'formatter' => function($row) {

					$content = Base::stringShortener(trim(strip_tags(htmlspecialchars_decode($row->content))), 100);
					return $content == '' ? '-' : $content;
				}
			],
			'icon' => [
				'formatter' => function($row) {
					return '<i class="' . $row->icon . '"></i>';
				}
			],
			'header_image_src' => [
				'formatter' => function($row) {
					$return = '';
					if ($row->header_image_src AND $srcset = @json_decode($row->header_image_src)) {
						$return = '<div class="image-group">';
						foreach ($srcset as $src) {
							$href = Base::base('upload/' . $src->original);
							$src = Base::base('upload/' . (isset($src->sm) !== false ? $src->sm : $src->original));
							$return .= '<a href="' . $href . '" target="_blank"><img class="table-image" src="' . $src . '" /></a>';
						}
						$return .= '</div>';
					} else {
						$return = '-';
					}
					return $return;
				}
			],
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
				"title" => Base::lang('base.description'),
				"key" => "description"
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
				"title" => Base::lang('base.icon'),
				"key" => "icon"
			],
			[
				"searchable" => false,
				"orderable" => false,
				"title" => Base::lang('base.header_image'),
				"key" => "header_image_src"
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
		'routes' => [ // method - route - controller@method - middlewares as array (like route definition in index.php)
			'listing' => [
				'en' => ['GET,POST', '/services', 'ContentController@contentListPage', []],
				'tr' => ['GET,POST', '/hizmetler', 'ContentController@contentListPage', []]
			],
			'detail' => [
				'en' => ['GET,POST', '/services/:slug', 'ContentController@contentDetailPage', []],
				'tr' => ['GET,POST', '/hizmetler/:slug', 'ContentController@contentDetailPage', []]
			],
			'view' => [
				'listing' => 'contents.service_list',
				'detail' => 'contents.service_detail',
			],
			'description' => [
				'listing' => 'base.service_list',
				'detail' => 'base.service_detail',
			]
		],
		'inputs' => [
			'title' => [
				'multilanguage' => true,
				'label' => 'base.title',
				'type' => 'input',
				'attributes' => [
					'required' => 'true',
					'data-kn-change' => Base::base('/management/services/slug')
				],
			],
			'slug' => [
				'multilanguage' => true,
				'label' => 'base.slug',
				'type' => 'input',
				'attributes' => [
					'required' => 'true',
					'data-kn-change' => Base::base('/management/services/slug')
				],
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
				'col' => 'col-12 col-md-4',
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
				'col' => 'col-12 col-md-4',
			],
			'widget' => [
				'countries' => [
					'label' => 'base.countries',
					'type' => 'select',
					'source' => ['getModuleDatas', ['countries']],
					'col' => 'col-12 col-md-4',
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
		'from' => '(SELECT 
						x.id, 
						IFNULL(JSON_UNQUOTE(JSON_EXTRACT(x.input, \'$.title.'.Base::lang('lang.code').'\')), "-") AS title,
						IFNULL(JSON_UNQUOTE(JSON_EXTRACT(x.input, \'$.content.'.Base::lang('lang.code').'\')), "-") AS content, 
						IFNULL(JSON_UNQUOTE(JSON_EXTRACT(x.input, \'$.icon\')), 0) AS icon,
						FROM_UNIXTIME(x.created_at, "%Y.%m.%d %H:%i") AS created,
						IFNULL(FROM_UNIXTIME(x.updated_at, "%Y.%m.%d"), "-") AS updated
					FROM `contents` x WHERE x.module = "other-services") AS raw',
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
			'icon' => [
				'formatter' => function($row) {
					return '<i class="' . $row->icon . '"></i>';
				}
			],
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
				"title" => Base::lang('base.icon'),
				"key" => "icon"
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
			'icon' => [
				'label' => 'base.icon',
				'type' => 'input',
				'col' => 'col-12 d-flex align-items-center justify-content-center',
				'attributes' => [
					'required' => 'true',
				]
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
						REPLACE(
							REPLACE(
								REPLACE(
									REPLACE(
										IFNULL(JSON_EXTRACT(x.input, \'$.flag\'), ""),
										" ",
										""
									),
									"\"",
									""
								),
								"]",
								""
							),
							"[",
							""
						) AS flag,
						(SELECT JSON_ARRAYAGG(files) AS files FROM files WHERE FIND_IN_SET(id, flag)) AS flag_src,
						REPLACE(
							REPLACE(
								REPLACE(
									REPLACE(
										IFNULL(JSON_EXTRACT(x.input, \'$.campus_image\'), ""),
										" ",
										""
									),
									"\"",
									""
								),
								"]",
								""
							),
							"[",
							""
						) AS campus_image,
						(SELECT JSON_ARRAYAGG(files) AS files FROM files WHERE FIND_IN_SET(id, campus_image)) AS campus_image_src,
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
					if ($row->flag_src AND $srcset = @json_decode($row->flag_src)) {
						$return = '<div class="image-group">';
						foreach ($srcset as $src) {

							$href = Base::base('upload/' . $src->original);
							$src = Base::base('upload/' . (isset($src->sm) !== false ? $src->sm : $src->original));
							$return .= '<a href="' . $href . '" target="_blank"><img class="table-image" src="' . $src . '" /></a>';
						}
						$return .= '</div>';
					} else {
						$return = '-';
					}
					return $return;
				}
			],
			'campus_image_src' => [
				'formatter' => function($row) {
					$return = '';
					if ($row->campus_image_src AND $srcset = @json_decode($row->campus_image_src)) {
						$return = '<div class="image-group">';
						foreach ($srcset as $src) {
							$href = Base::base('upload/' . $src->original);
							$src = Base::base('upload/' . (isset($src->sm) !== false ? $src->sm : $src->original));
							$return .= '<a href="' . $href . '" target="_blank"><img class="table-image" src="' . $src . '" /></a>';
						}
						$return .= '</div>';
					} else {
						$return = '-';
					}
					return $return;
				}
			],
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
	'gallery' => [
		'name' => 'base.gallery',
		'description' => 'base.gallery_message',
		'icon' => 'ti ti-panorama-horizontal',
		'from' => '(SELECT 
						x.id, 
						IFNULL(JSON_UNQUOTE(JSON_EXTRACT(x.input, \'$.title.'.Base::lang('lang.code').'\')), "-") AS title,
						IFNULL(JSON_UNQUOTE(JSON_EXTRACT(x.input, \'$.content.'.Base::lang('lang.code').'\')), "-") AS content,
						REPLACE(
							REPLACE(
								REPLACE(
									REPLACE(
										IFNULL(JSON_EXTRACT(x.input, \'$.images\'), ""),
										" ",
										""
									),
									"\"",
									""
								),
								"]",
								""
							),
							"[",
							""
						) AS images,
						(SELECT JSON_ARRAYAGG(files) AS files FROM files WHERE FIND_IN_SET(id, images)) AS images_src,
						FROM_UNIXTIME(x.created_at, "%Y.%m.%d %H:%i") AS created,
						IFNULL(FROM_UNIXTIME(x.updated_at, "%Y.%m.%d"), "-") AS updated
					FROM `contents` x WHERE x.module = "gallery") AS raw',
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
			'images_src' => [
				'formatter' => function($row) {
					$return = '';
					if ($row->images_src AND $srcset = @json_decode($row->images_src)) {
						$return = '<div class="image-group">';
						foreach ($srcset as $src) {
							$href = Base::base('upload/' . $src->original);
							$src = Base::base('upload/' . (isset($src->sm) !== false ? $src->sm : $src->original));
							$return .= '<a href="' . $href . '" target="_blank"><img class="table-image" src="' . $src . '" /></a>';
						}
						$return .= '</div>';
					} else {
						$return = '-';
					}
					return $return;
				}
			],
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
				"title" => Base::lang('base.images'),
				"key" => "images_src"
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
				'type' => 'textarea',
				'attributes' => ['required' => 'true'],
			],
			'images' => [
				'label' => 'base.image',
				'type' => 'file',
				'col' => 'col-12',
				'attributes' => [
					'accept' => 'image/*',
					'required' => 'true',
					'multiple' => true,
				],
				'external_parameters' => [
					'size' => [
						'original' => [1920, 600],
						'sm' => [250, 250]
					],
					// 'max_size' => 1024, // 1KB
					// 'convert' => 'png',
				]
			],
		]
	],
];