<?php

namespace app\ext;

use app\utils\FSL;
use Smarty\Exception;

class Smarty {
    private array $_data = [];
    private string $_themePath = "";
    private \Smarty\Smarty $_engine;

    /**
     * @param FSL $_fsl
     * @param string $_theme
     */
    public function __construct(private FSL $_fsl, private string $_theme) {
        $this->_themePath = "{$this->_fsl->themes()}/{$this->_theme}";
    }

    private function _new(): \Smarty\Smarty {
        $smarty = new \Smarty\Smarty();
        $smarty->setTemplateDir($this->_themePath);
        $smarty->setCompileDir($this->_themePath."/compiled");
        $smarty->setCacheDir($this->_themePath."/cache");
        $smarty->setConfigDir(__DIR__.'/../../config/smarty');

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