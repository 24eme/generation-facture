<?php

use App\Compactor;

require __DIR__ . '/../src/app/bootstrap.php';

$files = [];
$save_dir = $config->get('save_dir');
$names = $climate->arguments->get('name');
$periode = $climate->arguments->get('periode');

if ($names === 'all') {
    $files = glob($save_dir . '*.csv');
} else {
    foreach (explode(',', $names) as $name) {
        $files[] = $save_dir . $name . '.csv';
    }
}

foreach ($files as $file) {
    $client_name = basename($file, '.csv');
    if (! $client = $clients->get(strtolower($client_name), false)) {
        $climate->to('error')->error('Configuration file for '.$client_name.' does not exists');
        continue;
    }

    $temps = Compactor::compact($file);
    $lignes_prestations = Compactor::buildPrestaLine($temps, $config->get('prices'));

    $template = file_get_contents(__DIR__ . '/../template/invoice.tex');
    $template = str_replace('##date##', 'À Paris, le '.date('d/m/Y'), $template);
    $template = str_replace('##date-long##', date('\L\e d F Y,'), $template);

    $invoice_title = App\Markdown::replace(
        __DIR__.'/../template/markdown/invoice_title.md',
        ['invoice_number' => $periode . str_pad('000000', 6, 0, STR_PAD_LEFT)]
    );
    $template = str_replace('##invoice##', $markdown->parseParagraph($invoice_title), $template);

    $client = App\Markdown::replace(
        __DIR__.'/../template/markdown/client.md',
        $client
    );
    $template = str_replace('##client##', $markdown->parseParagraph($client), $template);

    $company = App\Markdown::replace(
        __DIR__.'/../template/markdown/company.md',
        $config->get('company')
    );
    $template = str_replace('##company##', $markdown->parseParagraph($company), $template);

    $list_of_prestation = [];
    $prestations = App\Markdown::replace(
        __DIR__.'/../template/markdown/prestation.md',
        $list_of_prestation
    );
    $template = str_replace('##items##', $markdown->parseParagraph($prestations), $template);

    $extra = App\Markdown::replace(
        __DIR__.'/../template/markdown/extra.md',
        $config->get('extra')
    );
    $template = str_replace('##extra##', $markdown->parseParagraph($extra), $template);

    file_put_contents(__DIR__.'/../out/'.$client_name.'.tex', $template);
}
