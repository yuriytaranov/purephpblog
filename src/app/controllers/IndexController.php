<?php

namespace app\controllers;

use app\http\Response;

class IndexController extends HttpController
{
    /**
     * @throws \Exception
     */
    public function index(): Response
    {
        return $this->view("home/index", ['hello' => 'world']);
    }
}