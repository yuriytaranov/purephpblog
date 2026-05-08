<?php

namespace app\routes;

use app\Route;
use app\IRouter;
use app\controllers\IndexController;

class WebRoute extends Route implements IRouter {

    public function map(): array
    {
        return [
            '/^$/' => [IndexController::class => 'index'],
        ];
    }
}