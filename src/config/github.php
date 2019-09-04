<?php

return [
    'authentication' => getenv('GITHUB_AUTH'),

    'connections' => [
        'none' => [
            'method' => 'none'
        ],

        'token' => [
            'method' => 'token',
            'token' => getenv('PASSWORD')
        ]
    ]
];
