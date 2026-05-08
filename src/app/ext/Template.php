<?php

namespace app\ext;

class Template {
    /**
     * @var string _theme
     */
    private $_theme = "";
    /**
     * @var string _layout
     */
    private $_layout = "";
    /**
     * @var array _data
     */
    private $_data = [];
    private $_themePath = "";

    /**
     * @param string $theme
     * @param string $layout
     */
    public function __construct(string $theme, string $layout) {
        $this->_theme = $theme;
        $this->_layout = $layout;
        $this->_themePath = __DIR__."/../themes/{$this->_theme}";
    }

    /**
     * @param string $view
     * @return string
     */
    public function content(string $view): string {
        ob_start();
        extract($this->_data);
        $path = "{$this->_themePath}/{$view}.php";
        include($path);
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

    /**
     * @param string $view
     * @param array $data
     * @return string
     */
    public function render(string $view, array $data): string {
        ob_start();
        $this->_data = $data;
        $content = $this->content($view);
        $path = "{$this->_themePath}/{$this->_layout}.php";
        include($path);
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
}