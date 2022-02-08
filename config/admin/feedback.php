<?php

return [
	'form' => [
		'name' => [
			'type'      => 'text',
			'signature' => 'Имя',
			'class'     => 4,
		],
		'phone' => [
			'type'      => 'text',
			'signature' => 'Телефон',
			'class'     => 4,
		],
		'email' => [
			'type'      => 'text',
			'signature' => 'E-mail',
			'class'     => 4,
		],
		'message' => [
			'type'      => 'text',
			'signature' => 'Message',
			'class'     => 4,
		],
		'site' => [
			'type'      => 'text',
			'signature' => 'Site',
			'class'     => 4,
		],
	],
	'rules' => [
		'name'  => 'nullable',
		'phone' => 'required|max:191',
		'email' => 'required|email|max:191',
		'message' => 'nullable',
		'site' => 'nullable'
	],
];