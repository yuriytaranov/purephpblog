<?php

use app\Container;
use app\Router;
use app\http\Response;
use app\db\drivers\Mysql;
use app\ext\Template;

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
$container->set(Template::class, fn () => new Template(confenv("THEME"),confenv("LAYOUT"),));

$router = new Router($container);
$router->scan();
$app = new WebApp($router);

$response = $app->handle();

echo $response->send();