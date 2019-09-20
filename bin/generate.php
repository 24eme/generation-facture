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

$template = file_get_contents(__DIR__ . '/../template/invoice.tex');
$template = str_replace('##date##', 'À Paris, le '.date('d/m/Y'), $template);
$template = str_replace('##date-long##', date('\L\e d F Y,'), $template);

$company = App\Markdown::replace(
    __DIR__.'/../template/markdown/company.md',
    $config->get('company')
);
$template = str_replace('##company##', $markdown->parse($company), $template);

$extra = App\Markdown::replace(
    __DIR__.'/../template/markdown/extra.md',
    $config->get('extra')
);
$template = str_replace('##extra##', $markdown->parse($extra), $template);

echo $template;
