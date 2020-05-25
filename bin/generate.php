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
    $pdf = new FactureLatex($facture, $twig);

    $client = mb_strtolower($facture["client"]);
    $pdf->setInfosClient($clients->get($client));
    $pdf->setInfosCompany($config->get("company"));
    $pdf->setInfosExtra($config->get("extra"));

    $pdfContent = $pdf->getPDFFileContents();
    $pdfName = $pdf->getPublicFileName();
    file_put_contents(__DIR__.'/../out/'.$pdfName.'.tex', $pdfContent);
}
