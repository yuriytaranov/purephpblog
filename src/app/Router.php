<?php
namespace app;
use app\http\Request;
use app\http\Response;
use WebApp;

class Router {
    /**
     * @var $_routers []IRouter
     */
    private $_routers = [];

    function __construct(public Container $container) {}

    /**
     * Scans the route dir for the routers.
     * @throws \Exception
     */
    public function scan(): void
    {
        $routerFiles = glob(__DIR__ . "/routes/*Route.php");
        array_walk($routerFiles, function($item) {
            $className = str_replace('.php', '', basename($item));
            $routeClass = "app\\routes\\{$className}";
            $this->_routers[] = new $routeClass($this->container);
        });
    }

    /**
     * Router handler
     *
     * @return Response The answer.
     * @throws \Exception
     */
    public function handle(): Response
    {
        $request = $this->container->get(Request::class);
        foreach($this->_routers as $route) {
            if(($result = $route->handle($request)) !== null) {
                return $result;
            }
        }

        //TODO: The router must not create a new response in case of errors.
        $response = new Response();
        
        $response->set("Ошибка: страница не найдена");
        return $response;
    }
}