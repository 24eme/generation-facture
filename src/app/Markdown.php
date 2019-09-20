<?php

namespace App;

class Markdown
{
    public static function replace(string $template, array $values) : string
    {
        if (! file_exists($template)) {
            throw new Exception("File $template does not exists");
        }

        $template = file_get_contents($template);

        foreach ($values as $key => $value) {
            $template = str_replace("{{ $key }}", $value, $template);
        }

        return $template;
    }
}
