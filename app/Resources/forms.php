<?php

use KN\Helpers\Base;

return [

	'contact-form' => [
		'name' => 'base.contact',
		'description' => 'base.contact_message',
		'icon' => 'ti ti-file-phone', 
		'inputs' => [
			'name' => [
				'label' => 'base.first_name_last_name',
				'type' => 'input',
				'col' => 'col-12 col-md-6',
				'attributes' => [
					'required' => 'true'
				],
			],
			'email' => [
				'label' => 'base.email',
				'type' => 'email',
				'col' => 'col-12 col-md-6',
				'attributes' => [
					'required' => 'true'
				],
			],
			'phone' => [
				'label' => 'base.telephone_number',
				'col' => 'col-12 col-md-6',
				'type' => 'text',
			],
			'subject' => [
				'label' => 'base.subject',
				'type' => 'text',
				'col' => 'col-12 col-md-6',
				'attributes' => [
					'required' => 'true'
				],
			],
			'message' => [
				'label' => 'base.message',
				'type' => 'text',
				'col' => 'col-12',
				'attributes' => [
					'required' => 'true'
				],
			]
		],
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
	],
	'information-request-form' => [
		'name' => Base::lang('base.information_request_form'),
		'description' => Base::lang('base.information_request_form_message'),
		'icon' => 'ti ti-file-info', 
		'inputs' => [
			'name' => [
				'label' => 'base.first_name_last_name',
				'type' => 'input',
				'col' => 'col-12 col-md-6',
				'attributes' => [
					'required' => 'true'
				],
			],
			'email' => [
				'label' => 'base.email',
				'type' => 'email',
				'col' => 'col-12 col-md-6',
				'attributes' => [
					'required' => 'true'
				],
			],
			'phone' => [
				'label' => 'base.phone',
				'type' => 'text',
				'col' => 'col-12 col-md-4',
				'attributes' => [
					'required' => 'true'
				],
			],
			'last_studied_program' => [
				'label' => 'app.last_studied_program',
				'type' => 'text',
				'col' => 'col-12 col-md-8',
				'attributes' => [
					'required' => 'true'
				],
			],
			'widget' => [
				'service' => [
					'label' => 'app.related_service_to_be_informed',
					'type' => 'select',
					'source' => ['getModuleDatas', ['services']],
					'col' => 'col-12 col-md-6',
					'use_for_view' => 'title',
					'attributes' => ['required' => 'true'],
				],
				'country' => [
					'label' => 'app.interested_country',
					'type' => 'select',
					'source' => ['getModuleDatas', ['countries']],
					'col' => 'col-12 col-md-6',
					'use_for_view' => 'title',
					'attributes' => ['required' => 'true'],
				]
			],
			'note' => [
				'label' => 'base.note',
				'col' => 'col-12',
				'type' => 'textarea',
			]
		]
	]
];