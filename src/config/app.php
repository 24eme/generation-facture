<?php

return [
    'debug' => getenv('DEBUG'),
    'save_dir' => __DIR__ . '/../../out/',
    'output_file' => 'compacted.csv',
    'jeancloude_path' => getenv('JEANCLOUDE_PATH')
];
