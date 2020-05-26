<?php

use App\Compactor;
use App\Reader\Csv;
use App\Lib\FactureLatex;

require __DIR__ . '/../src/app/bootstrap.php';


$filters = [];

$save_dir = $config->get('save_dir');
$periode = $climate->arguments->get('periode');
$file = $climate->arguments->get('file');
$names = ($climate->arguments->defined('names')) ? $climate->arguments->get('names') : null;

try {
    $csv = new Csv();
    $csv->setClients($names);
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
    $facturePdf->setDate($periode);
    $path = $facturePdf->getPDFFile();
    $climate->info('Nouvelle facture dans : ' . realpath($path));
}
