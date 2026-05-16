<?php

require_once "vendor/autoload.php";

/**
 * Register application autoloader.
 */
spl_autoload_register(function($name){
    $appPath = __DIR__;
    $classPath = str_replace('\\', '/', $name);
    $fullPath = "{$appPath}/{$classPath}.php";
    require_once($fullPath);
});

/**
 * Forces read config from the _ENV array.
 * @param string $name
 * @param $default
 * @return bool|mixed
 */
function confenv(string $name, $default = null) {
    return getenv($name);
}
