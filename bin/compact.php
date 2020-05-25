<?php

use App\Reader\Csv;

require __DIR__ . '/../src/app/bootstrap.php';

$save_dir = $config->get('save_dir');
$output_file = $config->get('output_file');
$periode = $climate->arguments->get('periode');
$file = $climate->arguments->get('file');
$start = $climate->arguments->get('start');

try {
    $output = $save_dir.$output_file.'.'.uniqid();

    $csv = new Csv();
    $csv->setPeriode($periode);
    $csv->setStartingAt($start);
    $csv->transform($file, $output);
} catch (\Exception $e) {
    $climate->to('error')->error($e->getMessage());
}

$climate->info('File : ' . realpath($output));
