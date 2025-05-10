<?php

declare(strict_types=1);

use Tracy\Dumper;

if (!function_exists('dd')) {
    function dd(...$vars): never
    {
        foreach ($vars as $var) {
            Dumper::dump($var);
        }
        exit;
    }
}