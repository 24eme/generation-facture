<?php

namespace App\Reader;

use App\Transformer\CsvTransformer;
use League\Csv\Reader;
use League\Csv\Statement;
use League\Csv\Writer;

class Csv
{
    private $clients = [];
    private $periode = '20200131';
    private $start = '0';

    public function createArrayFrom(string $file): array
    {
        if (is_file($file) === false) {
            throw new \Exception($file . ' is not a valid file');
        }

        $array = [];

        $reader = Reader::createFromPath($file, 'r');
        $reader->setDelimiter(';');
        $reader->setHeaderOffset(0);
        $stmt = (new Statement())->where(function (array $record) {
                return empty($this->clients) || in_array($record["Nom client"], $this->clients);
            });

        $records = $stmt->process($reader);

        foreach ($records as $record) {
            $numero_facture = $record["numero_facture"];
            if (array_key_exists($numero_facture, $array) === false) {
                $array[$numero_facture] = [
                    'client' => $record["Nom client"],
                    'presta' => []
                ];
            }

            $array[$numero_facture]['presta'][$record["Intitule ligne"]] = [
                'prix' => $record['Prix unitaire'],
                'qte' => $record['Nombre jours'],
                'total' => $record['Total HT']
            ];
        }

        return $array;
    }

    public function setClients(?string $clients): void
    {
        $this->clients = (empty($clients)) ? [] : explode(',', $clients);
    }

    public function setPeriode(string $periode): void
    {
        $this->periode = $periode;
    }

    public function setStartingAt(string $start): void
    {
        $this->start = $start;
    }

    public function transform(string $from, string $to): void
    {
        if (is_file($from) === false) {
            throw new \Exception($file . ' is not a valid file');
        }

        if (is_writable(dirname($to)) === false || is_file($to)) {
            //throw new \Exception($to . ' is not writable');
        }

        $with = CsvTransformer::read($from);
        CsvTransformer::write($to, $with, $this->periode, $this->start);
    }
}
