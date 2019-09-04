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
    $climate->to('error')->error($e->getMessage);
    exit;
}

$config = new Config(__DIR__ . '/../config');
$climate->arguments->add($config->get('climate'));
