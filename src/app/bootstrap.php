<?php

use App\Validator\Env;
use Dotenv\Dotenv;
use League\CLImate\CLImate;
use Github\Client;
use Noodlehaus\Config;
use cebe\markdown\latex\GithubMarkdown;

require __DIR__ . '/../../vendor/autoload.php';

$dotenv = Dotenv::create(__DIR__ . '/../../');
$dotenv->load();

$climate = new CLImate;
$github = (new Client())->api('repo')
                        ->contents();
$markdown = new GithubMarkdown();

try {
    Env::check($dotenv);
} catch (RuntimeException $e) {
    $climate->to('error')->error($e->getMessage);
    exit;
}

$config_files = glob(__DIR__ . '/../config/*.php');
$config = new Config($config_files);

$clients = new Config(__DIR__ . '/../config/clients/');

$climate->arguments->add($config->get('climate'));

try {
    $climate->arguments->parse();
} catch (Exception $e) {
    $climate->to('error')->error($e->getMessage());
    $climate->usage();
    exit;
}
