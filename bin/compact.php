<?php

use App\Reader\Csv;

require __DIR__ . '/../src/app/bootstrap.php';

$save_dir = $config->get('save_dir');
$output_file = $config->get('output_file');
$periode = $climate->arguments->get('periode');
$file = $climate->arguments->get('file');

$csv = new Csv();
echo $csv->transform($file, $save_dir.$output_file);
