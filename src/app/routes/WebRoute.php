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
            '/^category\/([A-Za-z0-9_-]+)$/' => [IndexController::class => 'category'],
            '/^manage\/category\/new$/' => [ManageController::class => 'newCategory'],
            '/^manage\/post\/new$/' => [ManageController::class => 'newPost'],
        ];
    }
}