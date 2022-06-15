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
				'type' => 'tel',
			],
			'subject' => [
				'label' => 'base.subject',
				'type' => 'input',
				'col' => 'col-12 col-md-6',
				'attributes' => [
					'required' => 'true'
				],
			],
			'message' => [
				'label' => 'base.message',
				'type' => 'textarea',
				'col' => 'col-12',
				'attributes' => [
					'required' => 'true'
				],
			]
		],
		'from' => '(SELECT 
						x.id,
						IFNULL(JSON_UNQUOTE(JSON_EXTRACT(x.input, \'$.name\')), "-") AS name,
						IFNULL(JSON_UNQUOTE(JSON_EXTRACT(x.input, \'$.email\')), "-") AS email,
						IFNULL(JSON_UNQUOTE(JSON_EXTRACT(x.input, \'$.phone\')), "-") AS phone,
						IFNULL(JSON_UNQUOTE(JSON_EXTRACT(x.input, \'$.subject\')), "-") AS subject,
						IFNULL(JSON_UNQUOTE(JSON_EXTRACT(x.input, \'$.message\')), "-") AS message,
						FROM_UNIXTIME(x.created_at, "%Y.%m.%d %H:%i") AS created,
						IFNULL(FROM_UNIXTIME(x.updated_at, "%Y.%m.%d"), "-") AS updated
					FROM `forms` x WHERE x.form = "contact-form" AND x.status != "deleted") AS raw',
		'table' => [
			'id' => [
				'primary' => true,
			],
			'name' => [],
			'email' => [],
			'phone' => [],
			'subject' => [],
			'message' => [
				'formatter' => function($row) {

					$message = Base::stringShortener($row->message, 100);
					return $message == '' ? '-' : $message;
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
				"title" => Base::lang('base.name'),
				"key" => "name"
			],
			[
				"searchable" => [
					"type" => "text",
					"maxlength" => 50
				],
				"orderable" => true,
				"title" => Base::lang('base.email'),
				"key" => "email"
			],
			[
				"searchable" => [
					"type" => "text",
					"maxlength" => 50
				],
				"orderable" => true,
				"title" => Base::lang('base.phone'),
				"key" => "phone"
			],
			[
				"searchable" => [
					"type" => "text",
					"maxlength" => 50
				],
				"orderable" => true,
				"title" => Base::lang('base.subject'),
				"key" => "subject"
			],
			[
				"searchable" => [
					"type" => "text",
					"maxlength" => 50
				],
				"orderable" => true,
				"title" => Base::lang('base.message'),
				"key" => "message"
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
				'en' => ['GET,POST', '/contact', 'FormController@formPage', []],
				'tr' => ['GET,POST', '/iletisim', 'FormController@formPage', []]
			],
			'view' => [
				'detail' => 'forms.contact',
			],
			'description' => [
				'detail' => 'base.contact_detail',
			]
		],
	],
	/*
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
	] */
];