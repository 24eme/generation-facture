<?php

return [
    'climate' => [
        'generate' => [
            'periode' => [
                'required' => true,
                'description' => 'La période à générer'
            ],
            'names' => [
                'longPrefix' => 'names',
                'description' => 'Le nom des fichiers à générer, séparés par une virgule'
            ],
            'file' => [
                'required' => true,
                'description' => 'Le fichier des temps'
            ]
        ],
        'download' => [
            'periode' => [
                'required' => true,
                'description' => 'La période à télécharger'
            ],
            'names' => [
                'longPrefix' => 'names',
                'description' => 'Le nom des fichiers à générer, séparés par une virgule'
            ]
        ],
        'compact' => [
            'file' => [
                'required' => true,
                'description' => 'Le fichier à compacter'
            ],
            'periode' => [
                'required' => true,
                'description' => 'La période à générer'
            ]
        ]
    ]
];
