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

    public static function write(string $to, array $with, string $delimiter = ';'): int
    {
        $out = Writer::createFromPath($to, 'w');
        $out->setDelimiter($delimiter);
        $trim = function (array $row): array {
            return array_map('trim', $row);
        };
        $out->addFormatter($trim);

        $out->insertOne(['numero_facture', 'Date facture', 'Nom client', 'Intitule ligne', 'Nombre jours', 'Prix unitaire', 'Total HT']);

        $i = 0;
        foreach ($with as $client => $prestations) {
            foreach ($prestations as $name => $price) {
                $out->insertOne([
                    'facture',
                    'periode',
                    $client,
                    $name,
                    $price
                ]);
            }
        }

        return 0;
    }
}
