<?php

return [
    'climate' => [
        'generate' => [
            'names' => [
                'longPrefix' => 'names',
                'description' => 'Le nom des fichiers à générer, séparés par une virgule'
            ],
            'file' => [
                'required' => true,
                'description' => 'Le fichier des temps'
            ],
            'output_path' => [
                'required' => false,
                'description' => 'Dossier de sortie'
            ],
            'output_mailpath' => [
                'required' => false,
                'description' => 'Dossier de sortie pour les mails'
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
            'periode' => [
                'required' => true,
                'description' => 'La période à générer'
            ],
            'start' => [
                'required' => true,
                'description' => 'Le numéro de facture sans la partie de période',
                'defaultValue' => '000'
            ],
            'file' => [
                'required' => true,
                'description' => 'Le fichier à compacter'
            ]
        ]
    ]
];
