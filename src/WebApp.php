<?php

use app\Container;
use app\Router;
use app\http\Response;

/**
 * Main class for entire web application.
 */
class WebApp{
    /**
     * Application initialization.
     */
    public function __construct(private Router $_router)
    {
        session_start();
    }

    /**
     * Tells to the router to handle a request.
     */
    public function handle(): Response
    {
        return $this->_router->handle();
    }
}