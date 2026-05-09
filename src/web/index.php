<?php

use app\Container;
use app\db\drivers\Mysql;
use app\ext\Smarty;
use app\http\Response;
use app\Router;
use app\services\FileSystem;

require "../bootstrap.php";
$container = new Container();
$container->set(Response::class, fn () => new Response());
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
$container->set(FileSystem::class, function () {
    static $fsl = null;
    if (is_null($fsl)) {
        $fsl = new FileSystem(realpath(__DIR__.'/..'));
    }

    return $fsl;
});
$container->set(Smarty::class, function () use($container) {
    return $container->build(Smarty::class, ['_theme' => confenv("THEME")]);
});

$router = new Router($container);
$router->scan();
$app = new WebApp($router);

$response = $app->handle();

echo $response->send();