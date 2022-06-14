<?php

use KN\Helpers\Base;

return [

	'contact-form' => [
		'name' => Base::lang('base.contact'),
		'description' => Base::lang('base.contact_message'),
		'icon' => 'ti ti-file-phone', 
		'inputs' => [
			'name' => [
				'label' => 'base.name',
				'type' => 'input',
				'attributes' => [
					'required' => 'true'
				],
			],
			'email' => [
				'label' => 'base.email',
				'type' => 'email',
				'attributes' => [
					'required' => 'true'
				],
			],
			'phone' => [
				'label' => 'base.phone',
				'type' => 'text',
				'attributes' => [
					'required' => 'true'
				],
			],
			'subject' => [
				'label' => 'base.subject',
				'type' => 'text',
				'attributes' => [
					'required' => 'true'
				],
			],
			'message' => [
				'label' => 'base.message',
				'type' => 'text',
				'attributes' => [
					'required' => 'true'
				],
			]
		]
	],
	'information-request-form' => [
		'name' => Base::lang('base.information_request_form'),
		'description' => Base::lang('base.information_request_form_message'),
		'icon' => 'ti ti-file-info', 
		'inputs' => [
			'name' => [
				'label' => 'base.name',
				'type' => 'input',
				'attributes' => [
					'required' => 'true'
				],
			],
			'email' => [
				'label' => 'base.email',
				'type' => 'email',
				'attributes' => [
					'required' => 'true'
				],
			],
			'phone' => [
				'label' => 'base.phone',
				'type' => 'text',
				'attributes' => [
					'required' => 'true'
				],
			],
			'last_studied_program' => [
				'label' => 'base.last_studied_program',
				'type' => 'text',
				'attributes' => [
					'required' => 'true'
				],
			],
			'which_service' => [
				'label' => 'base.which_service',
				'type' => 'text',
				'attributes' => [
					'required' => 'true'
				],
			]
		]
	]
];