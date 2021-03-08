<?php

return [
    'debug' => getenv('DEBUG'),
    'save_dir' => __DIR__ . '/../../out/',
    'output_file' => 'compacted.csv',
    'output_path' => getenv('OUTPUT_PATH')
];
