<?php

require __DIR__ . '/../src/app/bootstrap.php';

$periode = $climate->arguments->get('periode');
$github_username = getenv('USERNAME');
$github_repo = getenv('REPO');
$github_path = (getenv('ARCHIVE_DIR')) ?: '/';

$github_path = $github_path . '/' . $periode;

$climate->info('Checking existing path : ' . $github_path);
if (! $github->exists($github_username, $github_repo, $github_path)) {
    $climate->to('error')->error('The path does not exists. Did you type the right period ?');
    exit;
}

$save_dir = $config->get('save_dir');
if (! file_exists($save_dir)) {
    mkdir($save_dir);
}

$climate->info('Entering ' . $github_path);
$files = $github->show($github_username, $github_repo, $github_path);
$climate->info(count($files) . ' files');

$climate->info('Downloading...');
$progress = $climate->progress()->total(count($files));

foreach ($files as $key => $file) {
    $progress->current($key + 1, $file['name']);

    $source = fopen($file['download_url'], 'r');
    $dest = fopen(implode(DIRECTORY_SEPARATOR, [$save_dir, $file['name']]), 'w+');

    if ($source === false || $dest === false) {
        fclose($source);
        fclose($dest);
        $climate->to('error')->error('Unable to open ' . $file['html_url']);
        continue;
    }

    if (! stream_copy_to_stream($source, $dest)) {
        $climate->to('error')->error('Failed to save ' . $file['name']);
    }

}
