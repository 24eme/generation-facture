<?php

use App\Validator\Env;
use Dotenv\Dotenv;
use League\CLImate\CLImate;
use Github\Client;
use Noodlehaus\Config;

require __DIR__ . '/../../vendor/autoload.php';

$dotenv = Dotenv::create(__DIR__ . '/../../');
$dotenv->load();

$climate = new CLImate;
$github = (new Client())->api('repo')
                        ->contents();

try {
    Env::check($dotenv);
} catch (RuntimeException $e) {
    $climate->to('error')->error($e->getMessage());
    exit;
}

$config_files = glob(__DIR__ . '/../config/*.php');
$config = new Config($config_files);

if (empty($config->get('prices', []))) {
    $climate->to('error')->error(
        "Whoops ! It looks like you don't have any prices. Please check ".__DIR__."/../config/prices.php"
    );
    exit;
}

$clients = new Config(__DIR__ . '/../config/clients/');

$climate->arguments->add($config->get('climate.'.basename(realpath($argv[0]), '.php')));

try {
    $climate->arguments->parse();
} catch (Exception $e) {
    $climate->to('error')->error($e->getMessage());
    $climate->usage();
    exit;
}
