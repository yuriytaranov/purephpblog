<?php

use app\Container;

class CLIApp{
    public function __construct(private Container $container){}

    /**
     * @throws Exception
     */
    private function create()
    {


        throw new \Exception("Command not found");
    }

    /**
     * @throws Exception
     */
    public function run(): void
    {
        $args = $_SERVER['argv'];
        if(!isset($args[1])) {
            throw new \Exception("Command not found");
        }

        $fullName = explode(":", $args[1]);
        $name = ucfirst($fullName[0]);

        $class = "app\\console\\{$name}Command";
        $command = $this->container->build($class);
        $command->setArgs($args);

        if(isset($fullName[1])) {
            $action = $fullName[1];
            $params = array_slice($args, 2);
            $command->$action(...$params);
        } else {
            $command->handle();
        }
    }
}