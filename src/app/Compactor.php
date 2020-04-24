<?php

namespace App;

use League\Csv\Reader;

class Compactor
{
    const CSV_HEADER_TEMPS="Temps (en heure)";
    const CSV_HEADER_TACHE = "Tache";

    public static function compact($pathClient){
        $csv_temps = Reader::createFromPath($pathClient, 'r');
        $csv_temps->setDelimiter(';');
        $csv_temps->setHeaderOffset(0);
        $liste_temps = array();
        foreach ($csv_temps as $temps) {
            if(!empty($temps)){
                if(!array_key_exists($temps[self::CSV_HEADER_TACHE],$liste_temps))
                    $liste_temps[$temps[self::CSV_HEADER_TACHE]] = 0;
                $liste_temps[$temps[self::CSV_HEADER_TACHE]] += str_replace(",", ".", $temps[self::CSV_HEADER_TEMPS]);
            }
        }
        return $liste_temps;
    }

    public static function buildPrestaLine(array $prestas, array $prices)
    {
        $lines = [];
        foreach ($prestas as $presta_name => $temps) {
            $line = [
                $prices[$presta_name]['label'],
                $temps,
                $prices[$presta_name]['price'],
                $temps * $prices[$presta_name]['price']
            ];
            $lines[] = $line;
        }

        return $lines;
    }
}
