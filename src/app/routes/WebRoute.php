<?php

namespace app\routes;

use app\controllers\ManageController;
use app\Route;
use app\IRouter;
use app\controllers\IndexController;

class WebRoute extends Route implements IRouter {

    public function map(): array
    {
        return [
            '/^$/' => [IndexController::class => 'index'],
            '/^manage\/category\/new$/' => [ManageController::class => 'newCategory'],
            '/^manage\/category\/save/' => [ManageController::class => 'saveCategory'],
            '/^manage\/post\/new$/' => [ManageController::class => 'newPost'],
            '/^manage\/post\/save/' => [ManageController::class => 'savePost'],
        ];
    }
}