<?php

use KN\Helpers\Base;

return [
	'pages' => [
		'name' => 'base.pages',
		'description' => 'base.pages_message',
		'icon' => 'ti ti-align-justified',
		'from' => '(SELECT 
						x.id,
						IFNULL(JSON_UNQUOTE(JSON_EXTRACT(x.input, \'$.title.'.Base::lang('lang.code').'\')), "-") AS title,
						IFNULL(JSON_UNQUOTE(JSON_EXTRACT(x.input, \'$.slug.'.Base::lang('lang.code').'\')), "-") AS slug,
						IFNULL(JSON_UNQUOTE(JSON_EXTRACT(x.input, \'$.description.'.Base::lang('lang.code').'\')), "-") AS description,
						IFNULL(JSON_UNQUOTE(JSON_EXTRACT(x.input, \'$.content.'.Base::lang('lang.code').'\')), "-") AS content,
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
					FROM `contents` x WHERE x.module = "pages") AS raw',
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
			'listing' => false,
			'detail' => [
				'en' => ['GET,POST', '/pages/:slug', 'ContentController@contentDetailPage', []],
				'tr' => ['GET,POST', '/sayfalar/:slug', 'ContentController@contentDetailPage', []]
			],
			'view' => [
				'detail' => 'contents.page_detail',
			],
			'description' => [
				'detail' => 'base.page_detail',
			]
		],
		'inputs' => [
			'title' => [
				'col' => 'col-12 col-md-4',
				'multilanguage' => true,
				'label' => 'base.title',
				'type' => 'input',
				'attributes' => [
					'required' => 'true',
					'data-kn-change' => Base::base('/management/blog/slug')
				],
			],
			'slug' => [
				'col' => 'col-12 col-md-4',
				'multilanguage' => true,
				'label' => 'base.slug',
				'type' => 'input',
				'attributes' => [
					'required' => 'true',
					'data-kn-change' => Base::base('/management/blog/slug')
				],
			],
			'description' => [
				'col' => 'col-12 col-md-4',
				'multilanguage' => true,
				'label' => 'base.description',
				'type' => 'input',
				'attributes' => ['required' => 'true'],
			],
			'content' => [
				'col' => 'col-12 col-lg-10',
				'multilanguage' => true,
				'label' => 'base.description',
				'type' => 'editor',
				'attributes' => ['required' => 'true'],
			],
			'widget' => [
				'category' => [
					'label' => 'base.categories',
					'type' => 'select',
					'source' => ['getModuleDatas', ['categories']],
					'col' => 'col-12 col-lg-2 pt-4',
					'use_for_view' => 'title',
					'attributes' => ['required' => 'true'],
				]
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
			
		]
	],
	'blog' => [
		'name' => 'base.blog',
		'description' => 'base.blog_message',
		'icon' => 'ti ti-blockquote',
		'from' => '(SELECT 
						x.id,
						IFNULL(JSON_UNQUOTE(JSON_EXTRACT(x.input, \'$.title.'.Base::lang('lang.code').'\')), "-") AS title,
						IFNULL(JSON_UNQUOTE(JSON_EXTRACT(x.input, \'$.slug.'.Base::lang('lang.code').'\')), "-") AS slug,
						IFNULL(JSON_UNQUOTE(JSON_EXTRACT(x.input, \'$.description.'.Base::lang('lang.code').'\')), "-") AS description,
						IFNULL(JSON_UNQUOTE(JSON_EXTRACT(x.input, \'$.content.'.Base::lang('lang.code').'\')), "-") AS content,
						IFNULL(JSON_UNQUOTE(JSON_EXTRACT(x.input, \'$.category\')), "-") AS category,
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
						(SELECT IFNULL(JSON_UNQUOTE(JSON_EXTRACT(x.input, \'$.title.'.Base::lang('lang.code').'\')), "-") FROM contents WHERE id = category) AS category_name,
						FROM_UNIXTIME(x.created_at, "%Y.%m.%d %H:%i") AS created,
						IFNULL(FROM_UNIXTIME(x.updated_at, "%Y.%m.%d"), "-") AS updated
					FROM `contents` x WHERE x.module = "blog") AS raw',
		'table' => [
			'id' => [
				'primary' => true,
			],
			'title' => [],
			'category_name' => [],
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
				"title" => Base::lang('base.category'),
				"key" => "category_name"
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
				'en' => ['GET,POST', '/blog', 'ContentController@contentListPage', []],
				'tr' => ['GET,POST', '/blog', 'ContentController@contentListPage', []]
			],
			'detail' => [
				'en' => ['GET,POST', '/blog/:slug', 'ContentController@contentDetailPage', []],
				'tr' => ['GET,POST', '/blog/:slug', 'ContentController@contentDetailPage', []]
			],
			'view' => [
				'listing' => 'contents.blog_list',
				'detail' => 'contents.blog_detail',
			],
			'description' => [
				'listing' => 'base.blog_list',
				'detail' => 'base.blog_detail',
			]
		],
		'inputs' => [
			'title' => [
				'multilanguage' => true,
				'label' => 'base.title',
				'type' => 'input',
				'col' => 'col-12 col-md-4',
				'attributes' => [
					'required' => 'true',
					'data-kn-change' => Base::base('/management/blog/slug')
				],
			],
			'slug' => [
				'multilanguage' => true,
				'label' => 'base.slug',
				'type' => 'input',
				'col' => 'col-12 col-md-4',
				'attributes' => [
					'required' => 'true',
					'data-kn-change' => Base::base('/management/blog/slug')
				],
			],
			'description' => [
				'multilanguage' => true,
				'label' => 'base.description',
				'type' => 'input',
				'col' => 'col-12 col-md-4',
				'attributes' => ['required' => 'true'],
			],
			'content' => [
				'multilanguage' => true,
				'label' => 'base.description',
				'type' => 'editor',
				'col' => 'col-12 col-md-10',
				'attributes' => ['required' => 'true'],
			],
			'widget' => [
				'category' => [
					'label' => 'base.categories',
					'type' => 'select',
					'source' => ['getModuleDatas', ['categories']],
					'col' => 'col-12 col-md-2 pt-3',
					'use_for_view' => 'title',
					'attributes' => ['required' => 'true'],
				]
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
		]
	],
	'categories' => [
		'name' => 'base.categories',
		'description' => 'base.categories_message',
		'icon' => 'ti ti-quote',
		'from' => '(SELECT 
						x.id, 
						IFNULL(JSON_UNQUOTE(JSON_EXTRACT(x.input, \'$.title.'.Base::lang('lang.code').'\')), "-") AS title,
						IFNULL(JSON_UNQUOTE(JSON_EXTRACT(x.input, \'$.description.'.Base::lang('lang.code').'\')), "-") AS description, 
						IFNULL(JSON_UNQUOTE(JSON_EXTRACT(x.input, \'$.color\')), "-") AS color,
						FROM_UNIXTIME(x.created_at, "%Y.%m.%d %H:%i") AS created,
						IFNULL(FROM_UNIXTIME(x.updated_at, "%Y.%m.%d"), "-") AS updated
					FROM `contents` x WHERE x.module = "categories") AS raw',
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
			'color' => [
				'formatter' => function($row) {
					return '
					<strong class="badge text-light" data-bs-toggle="tooltip" title="' . $row->color . '" style="background: ' . $row->color . '; width: 3rem; height: 1.5rem;">
						
					</strong>';
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
				"searchable" => false,
				"orderable" => false,
				"title" => Base::lang('base.color'),
				"key" => "color"
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
			'detail' => [
				'en' => ['GET,POST', '/categories/:slug', 'ContentController@contentDetailPage', []],
				'tr' => ['GET,POST', '/kategoriler/:slug', 'ContentController@contentDetailPage', []]
			],
			'view' => [
				'detail' => 'contents.category_detail',
			],
			'description' => [
				'detail' => 'base.blog_detail',
			]
		],
		'inputs' => [
			'title' => [
				'multilanguage' => true,
				'label' => 'base.title',
				'type' => 'input',
				'col' => 'col-12 col-md-6',
				'attributes' => [
					'required' => 'true',
				],
			],
			'color' => [
				'label' => 'base.color',
				'type' => 'color',
				'col' => 'col-12 col-md-6 pt-4',
				'attributes' => [
					'required' => 'true',
				]
			],
			'description' => [
				'multilanguage' => true,
				'label' => 'base.description',
				'type' => 'textarea',
				'col' => 'col-12',
				'attributes' => ['required' => 'true'],
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
				'col' => 'col-12 col-md-4',
				'attributes' => [
					'required' => 'true',
				],
			],
			'content' => [
				'multilanguage' => true,
				'label' => 'base.description',
				'type' => 'textarea',
				'col' => 'col-12 col-md-8',
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
	'services' => [
		'name' => 'base.services',
		'description' => 'base.services_message',
		'icon' => 'ti ti-folders',
		'from' => '(SELECT 
						x.id, 
						IFNULL(JSON_UNQUOTE(JSON_EXTRACT(x.input, \'$.title.'.Base::lang('lang.code').'\')), "-") AS title,
						IFNULL(JSON_UNQUOTE(JSON_EXTRACT(x.input, \'$.slug.'.Base::lang('lang.code').'\')), "-") AS slug,
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
				'col' => 'col-12 col-md-6',
				'attributes' => [
					'required' => 'true',
					'data-kn-change' => Base::base('/management/services/slug')
				],
			],
			'slug' => [
				'multilanguage' => true,
				'label' => 'base.slug',
				'type' => 'input',
				'col' => 'col-12 col-md-6',
				'attributes' => [
					'required' => 'true',
					'data-kn-change' => Base::base('/management/services/slug')
				],
			],
			'description' => [
				'multilanguage' => true,
				'label' => 'base.description',
				'type' => 'input',
				'col' => 'col-12 col-md-8',
				'attributes' => ['required' => 'true'],
			],
			'icon' => [
				'label' => 'base.icon',
				'type' => 'input',
				'attributes' => ['required' => 'true'],
				'col' => 'col-12 col-md-2 pt-4',
			],
			'widget' => [
				'countries' => [
					'label' => 'base.countries',
					'type' => 'select',
					'source' => ['getModuleDatas', ['countries']],
					'col' => 'col-12 col-md-2 pt-4',
					'use_for_view' => 'title',
					'attributes' => ['required' => 'true'],
				]
			],
			'content' => [
				'multilanguage' => true,
				'label' => 'base.description',
				'type' => 'editor',
				'col' => 'col-12',
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
				'col' => 'col-12 col-md-10',
				'attributes' => [
					'required' => 'true',
				],
			],
			'icon' => [
				'label' => 'base.icon',
				'type' => 'input',
				'col' => 'col-12 col-md-2 pt-4',
				'attributes' => [
					'required' => 'true',
				]
			],
			'content' => [
				'multilanguage' => true,
				'label' => 'base.description',
				'type' => 'editor',
				'col' => 'col-12',
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
				"key" => "campus_image_src"
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