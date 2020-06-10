<?php

namespace App\Reader;

use App\Transformer\CsvTransformer;
use League\Csv\Reader;
use League\Csv\Statement;
use League\Csv\Writer;

class Csv
{
    private $prices = null;
    private $clients = [];
    private $excluded_clients = [];
    private $periode = '20200131';
    private $start = '0';

    const FORFAIT_TERM = "Forfait";

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
            if(in_array($record["Nom client"],$this->excluded_clients)){
              continue;
            }
            $numero_facture = $record["numero_facture"];
            if (array_key_exists($numero_facture, $array) === false) {
                $array[$numero_facture] = [
                    'client' => $record["Nom client"],
                    'presta' => [],
                    'total_ht' => 0.0,
                    'forfait' => true
                ];
            }
            $array[$numero_facture]['presta'][$record["Intitule ligne"]] = [
                'qte' => $record['Nombre jours'],
                'prix' => $record['Prix unitaire'],
                'total' => $record['Total HT']
                ];
            if($record['Nombre jours']){
              $array[$numero_facture]['forfait'] = false;
            }
            $array[$numero_facture]['total_ht']+=$record['Total HT'];
        }

        foreach ($array as $numero_facture => $facture) {
          $facture['total_tva'] = 0.2 * $facture['total_ht'];
          $facture['total_ttc'] = $facture['total_ht']+$facture['total_tva'];
          $array[$numero_facture] = $facture;
        }
        return $array;
    }

    public function setClients(?string $clients): void
    {
        $this->clients = (empty($clients)) ? [] : explode(',', $clients);
    }

    public function setPrices(?array $prices): void
    {
        $this->prices = (empty($prices)) ? [] : $prices;
    }

    public function setPeriode(string $periode): void
    {
        $this->periode = $periode;
    }

    public function setExcludedClients(?array $excluded_clients): void
    {
        $this->excluded_clients = (empty($excluded_clients)) ? [] : $excluded_clients;
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
        CsvTransformer::write($to, $with, $this->periode, $this->start,';', $this->prices);
    }
}
