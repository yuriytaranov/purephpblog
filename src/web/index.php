<?php

use app\Container;
use app\db\drivers\Mysql;
use app\ext\Smarty;
use app\ext\SmartyFactory;
use app\http\Response;
use app\Router;
use app\services\FileSystem;

require "../bootstrap.php";

$container = new Container();
$container->set(Response::class, fn () => new Response());
$container->set(Mysql::class, fn () => new Mysql(
    confenv("DATABASE_URL"),
    confenv('DATABASE_USER'),
    confenv('DATABASE_PASSWORD')
));
$container->set(FileSystem::class, fn () => new FileSystem(realpath(__DIR__.'/..')));
$container->set(SmartyFactory::class, fn ($c) => new SmartyFactory($c->get(FileSystem::class), $c));
$container->set(Smarty::class, function ($c) {
    /** @var SmartyFactory $factory */
    $factory = $c->get(SmartyFactory::class);
    return $factory->create(confenv('THEME'));
});

$router = new Router($container);
$router->scan();
$app = new WebApp($router);

$response = $app->handle();

echo $response->send();