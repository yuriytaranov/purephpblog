<?php

namespace app\ext;

use app\Container;
use app\services\FileSystem;

class SmartyFactory
{
    public function __construct(
        private FileSystem $fsl,
        private Container $container,
    ) {}

    public function create(string $theme): Smarty {
        $themePath = "{$this->fsl->themes()}/{$theme}";

        $extensions = [];
        $resgistryPath = "{$themePath}/extensions/registry.php";

        if (file_exists($resgistryPath)) {
            $factory = require $resgistryPath;

            $extension = $factory($this->container);
        }

        return new Smarty($this->fsl, $theme, $extension);
    }
}