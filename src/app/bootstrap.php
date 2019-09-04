<?php

use App\Validator\Env;
use Dotenv\Dotenv;
use League\CLImate\CLImate;
use Noodlehaus\Config;

require __DIR__ . '/../../vendor/autoload.php';

$climate = new CLImate;

$dotenv = Dotenv::create(__DIR__ . '/../../');
$dotenv->load();

try {
    Env::check($dotenv);
} catch (RuntimeException $e) {
    $climate->to('error')->error($e->getMessage);
    exit;
}

$config = new Config(__DIR__ . '/../config');
