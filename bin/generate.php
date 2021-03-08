<?php

use App\Compactor;
use App\Reader\Csv;
use App\Lib\FactureLatex;

require __DIR__ . '/../src/app/bootstrap.php';


$filters = [];

$save_dir = $config->get('save_dir');
$file = $climate->arguments->get('file');
$output_path = $climate->arguments->get('output_path');
$jeancloude_path = $config->get('jeancloude_path');
$excluded_clients = $config->get('excluded_clients');
$names = ($climate->arguments->defined('names')) ? $climate->arguments->get('names') : null;

if(!$output_path) {
	$output_path = $jeancloude_path;
}

try {
    $csv = new Csv();
    $csv->setClients($names);
    $csv->setExcludedClients($excluded_clients);
    $factures = $csv->createArrayFrom($file);
} catch (Exception $e) {
    $climate->to('error')->error($e->getMessage());
    exit;
}

foreach ($factures as $idfacture => $facture) {
    $facturePdf = new FactureLatex($idfacture, $facture, $twig);

    $client = mb_strtolower($facture["client"]);

    $facturePdf->setInfosClient($clients->get($client));
    $facturePdf->setInfosCompany($config->get("company"));
    $facturePdf->setInfosExtra($config->get("extra"));
    $facturePdf->setJeancloudePath($output_path);

    $path = $facturePdf->getPDFFile();
    $climate->info('Nouvelle facture dans : ' . str_replace(" ","\ ",$path));
}
