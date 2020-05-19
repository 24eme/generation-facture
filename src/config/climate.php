<?php

return [
    'climate' => [
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
    ]
];
