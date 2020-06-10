<?php

namespace App\Transformer;

use League\Csv\Reader;
use League\Csv\Writer;

class CsvTransformer
{
    public static function read(string $from, string $delimiter = ';'): array
    {
        $in = Reader::createFromPath($from, 'r');
        $in->setDelimiter($delimiter);
        $in->setHeaderOffset(0);

        $rows = [];
        $records = $in->getRecords(['date', 'project', 'desc', 'time', 'type']);

        foreach ($records as $record) {
            if (array_key_exists($record['project'], $rows) === false) {
                $rows[$record['project']] = [];
            }

            if (array_key_exists($record['type'], $rows[$record['project']]) === false) {
                $rows[$record['project']][$record['type']] = 0.0;
            }

            // TODO: use StreamFilter to transform time to float
            $rows[$record['project']][$record['type']] += (float) str_replace(',', '.', $record['time']);
        }

        return $rows;
    }

    public static function write(string $to, array $with, string $periode, string $start, string $delimiter = ';', $prices = array()): void
    {
        $out = Writer::createFromPath($to, 'w');
        $out->setDelimiter($delimiter);
        $trim = function (array $row): array {
            return array_map('trim', $row);
        };
        $out->addFormatter($trim);

        $out->insertOne(['numero_facture', 'Date facture', 'Nom client', 'Intitule ligne', 'Nombre jours', 'Prix unitaire', 'Total HT']);

        $invoice_number = (int) $periode . str_pad($start, 5, '0', STR_PAD_LEFT);
        foreach ($with as $client => $prestations) {
            echo "Client: $client - Invoice: $invoice_number - Presta: ".count($prestations).PHP_EOL;
            foreach ($prestations as $name => $nbhours_or_price) {
              $nbjours_or_price = round($nbhours_or_price / 7.0,1);
                if(array_key_exists($name,$prices)){
                  $label = $prices[$name]["label"];
                  $price = $prices[$name]["price"];
                  $total = $price * $nbjours_or_price;
                  $out->insertOne([$invoice_number, $periode, $client, $label, $nbjours_or_price, $price,$total]);
                }else{
                  $out->insertOne([$invoice_number, $periode, $client, $name, "" , "" ,$nbjours_or_price]);
                }
            }
            $invoice_number++;
        }
    }
}
