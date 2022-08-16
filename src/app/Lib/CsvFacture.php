<?php

namespace App\Lib;

use League\Csv\Writer;
use SplTempFileObject;

class CsvFacture
{
    private $csv;

    public function __construct()
    {
        $this->csv = Writer::createFromFileObject(new SplTempFileObject());
        $this->csv->setDelimiter(';');
    }

    public function addFacture(int $numero, array $facture)
    {
        $type = ($facture['total_ttc'] >= 0) ? 'FACTURE' : 'AVOIR';

        $this->csv->insertOne([
            $type,
            $facture['date']->format('Y-m-d'),
            $numero,
            $facture['client'],
            implode(', ', array_keys($facture['presta'])),
            $facture['total_ht'],
            $facture['total_tva'],
            $facture['total_ttc'],
            'FALSE',
            ''
        ]);
    }

    public function export()
    {
        return $this->csv->getContent();
    }
}
