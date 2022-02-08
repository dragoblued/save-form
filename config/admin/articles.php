<?php

return [
    'form' => [
        'active' => [
            'type'      => 'select',
            'items'     => [
                'Нет',
                'Да',
            ],
            'signature' => 'Активен',
            'required'  => true,
			'class'     => 2,
        ],
        'link' => [
            'type'      => 'text',
            'signature' => 'Link',
            'required'  => true,
			'class'     => 4,
        ],
        'source' => [
            'type'      => 'text',
            'signature' => 'Source',
            'required'  => true,
			'class'     => 4,
        ],
        'announce' => [
            'type'      => 'text',
            'signature' => 'Announce',
            'required'  => true,
			'class'     => 4,
        ],
        'date' => [
            'type'      => 'text',
            'signature' => 'Date',
            'required'  => true,
			'class'     => 4,
        ],
        'image' => [
            'type'      => 'files',
            'signature' => 'Изображение',
            'required'  => true,
            'multiple'  => false,
            'sizers'    => [ 'preview' => [ null, 200, 80 ] ]
        ],
    ],
    'rules' => [
        'active'    => "required|boolean",
        'link'      => "required",
        'source'      => "required",
        'announce'      => "required",
        'date'      => "required",
        'image'     => 'required|mimetypes:image/*',
    ],
];