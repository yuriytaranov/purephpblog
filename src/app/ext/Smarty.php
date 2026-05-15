<?php

namespace app\ext;

use app\services\FileSystem;
use Smarty\Exception;

class Smarty {
    private string $_themePath = "";

    /**
     * @param FileSystem $_fsl
     * @param string $_theme
     */
    public function __construct(
        private FileSystem $_fsl,
        private string $_theme,
        private array $extensions
    ) {
        $this->_themePath = "{$this->_fsl->themes()}/{$this->_theme}";
    }

    private function _new(): \Smarty\Smarty {
        $smarty = new \Smarty\Smarty();
        $smarty->setTemplateDir($this->_themePath);
        $smarty->setCompileDir($this->_themePath."/compiled");
        $smarty->setCacheDir($this->_themePath."/cache");
        $smarty->setConfigDir($this->_fsl->etc().'/smarty');
        if (count($this->extensions) > 0) {
            $smarty->setExtensions($this->extensions);
        }

        return $smarty;
    }

    /**
     * @param string $view
     * @param array $data
     * @return string
     * @throws Exception
     */
    public function render(string $view, array $data): string {
        $tpl = $this->_new();
        foreach ($data as $key => $value) {
            $tpl->assign($key, $value);
        }

        return $tpl->fetch("{$view}.tpl");
    }
}