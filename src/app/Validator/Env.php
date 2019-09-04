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
        $env->required([
            'USERNAME',
            'REPO',
            'ARCHIVE_DIR',
            'GITHUB_AUTH'
        ]);

        $env->required('DEBUG')->isBoolean();

        $env->required('GITHUB_AUTH')
            ->allowedValues(['none', 'token']);
    }
}
