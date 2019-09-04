<?php

namespace App\Validator;

use Dotenv\Dotenv;

class Env
{
    /**
     * Rules to check for env variables
     *
     * @param Dotenv $env The Dotenv object holding variables
     * @throws RuntimeException If a rule doesn't match
     */
    public static function check(Dotenv $env)
    {
        $dotenv->required([
            'USERNAME',
            'REPO',
            'ARCHIVE_DIR',
            'GITHUB_AUTH'
        ]);

        $dotenv->required('DEBUG')->isBoolean();

        $dotenv->required('GITHUB_AUTH')
               ->allowedValues(['none', 'token']);
    }
}
