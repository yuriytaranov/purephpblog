#!/usr/bin/env php
<?php

use app\Container;
use app\db\drivers\Mysql;

require_once("bootstrap.php");

$container = new Container();
$container->set(Mysql::class, function () {
    static $db = null;
    if (is_null($db)) {
        $db = new Mysql(
                confenv("DATABASE_URL"),
                confenv('DATABASE_USER'),
                confenv('DATABASE_PASSWORD')
        );
    }

    return $db;
});

$app = new CLIApp($container);
$app->run();