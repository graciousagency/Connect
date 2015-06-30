<?php

use Dotenv\Dotenv;

require __DIR__ . '/vendor/autoload.php';

(new Dotenv(__DIR__))->load();
date_default_timezone_set("Europe/Amsterdam");
function env($key, $default = "")
{
    return (getenv($key) !== false) ? getenv($key) : $default;
}