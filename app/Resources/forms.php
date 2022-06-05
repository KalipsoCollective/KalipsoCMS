<?php

use KN\Helpers\Base;

return [

	'contact_dorm' => [
		'name' => Base::lang('base.contact'),
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
	'information_request_form' => [
		'name' => Base::lang('base.information_request_form'),
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