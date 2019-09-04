<?php

return [
    'climate' => [
        'periode' => [
            'required' => true,
            'description' => 'La période à générer'
        ],
        'name' => [
            'longPrefix' => 'name',
            'defaultValue' => 'all',
            'description' => 'Le nom des fichiers à générer, séparer par une virgule'
        ]
    ]
];
