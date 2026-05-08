<?php


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

/**
 * Read configuration file. Gives config value.
 * @param string $section Configuration section. If section name is ENV then it gets an _ENV element.
 * @param string $name Parameter name.
 * @param mixed $default Default value is it isn't in config file.
 * 
 * @return mixed Configuration parameter.
 */
function conf(string $section, string $name, $default = null)
{
    static $conf = [];
    if (empty($conf)) {
        $configFiles = glob(__DIR__ . "/conf/*.ini");
        array_walk($configFiles, function ($file) use (&$conf) {
            $pieceOfConf = parse_ini_file($file, true, INI_SCANNER_TYPED);
            $conf = array_merge($conf, $pieceOfConf);
        });
    }
    return $conf[$section][$name] ?? $default;
}

/**
 * Application access to the database.
 * 
 * @return \app\db\drivers\Mysql|null Configured database connection.
 */
function Database()
{
    static $db = null;
    if(is_null($db)) {
        $db = new \app\db\drivers\Mysql();
    }
    return $db;
}

/**
 * Application handler.
 * @param string|null $name Class to create application.
 *
 * @return mixed Application handler.
 * @throws Exception
 */
function app(?string $name = null)
{
    static $h = null;
    if(is_null($h)){
        if(is_null($name)) {
            throw new Exception("Application name required on first call");
        }
        $h = new $name();
    }
    return $h;
}

$vendor = conf("Main", "vendor_autoload", "vendor/autoload.php");

if(file_exists($vendor)) {
    require_once($vendor);
}