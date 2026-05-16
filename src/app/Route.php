<?php
namespace app;

use app\http\Request;
use app\http\Response;

/**
 * Use this class to handle requests with a controller
 */
abstract class Route {
    abstract public function map();
    public function __construct(private Container $container) {}

    /**
     * @throws \Exception
     */
    public function handle(Request $request): ?Response
    {
        foreach($this->map() as $path => $callbackName) {
            $actionArguments = [];
            $routeProcess = preg_match($path, $request->path, $actionArguments);
            array_shift($actionArguments);
            if(1 === $routeProcess) {
                $controllerClass = key($callbackName);
                $action = $callbackName[$controllerClass];
                $controller = $this->container->build($controllerClass);
                return $controller->$action(...$actionArguments);
            }
        }

        return null;
    }
}