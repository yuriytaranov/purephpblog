<?php

namespace app;
use app\http\Request;
use app\http\Response;

interface IRouter {
    public function map();
    public function handle(Request $request): ?Response;
}