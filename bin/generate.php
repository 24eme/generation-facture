<?php

require __DIR__ . '/../src/app/bootstrap.php';

$files = [];
$save_dir = $config->get('save_dir');
$names = $climate->arguments->get('name');

if ($names === 'all') {
    $files = glob($save_dir . '*.csv');
} else {
    foreach (explode(',', $names) as $name) {
        $files[] = $save_dir . $name . '.csv';
    }
}

foreach ($files as $file) {
    $client = basename($file, '.csv');
    if (! $client = $clients->get(strtolower($client), false)) {
        $climate->to('error')->error('Configuration file for '.basename($file, '.csv').' does not exists');
        continue;
    }

    $template = file_get_contents(__DIR__ . '/../template/invoice.tex');
    $template = str_replace('##date##', 'À Paris, le '.date('d/m/Y'), $template);
    $template = str_replace('##date-long##', date('\L\e d F Y,'), $template);

    $client = App\Markdown::replace(
        __DIR__.'/../template/markdown/client.md',
        $client
    );
    $template = str_replace('##client##', $markdown->parse($client), $template);

    $company = App\Markdown::replace(
        __DIR__.'/../template/markdown/company.md',
        $config->get('company')
    );
    $template = str_replace('##company##', $markdown->parse($company), $template);

    $list_of_prestation = [];
    $prestations = App\Markdown::replace(
        __DIR__.'/../template/markdown/prestation.md',
        $list_of_prestation
    );
    $template = str_replace('##items##', $markdown->parse($prestations), $template);

    $extra = App\Markdown::replace(
        __DIR__.'/../template/markdown/extra.md',
        $config->get('extra')
    );
    $template = str_replace('##extra##', $markdown->parse($extra), $template);
}