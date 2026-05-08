<?php

namespace app\http;

class Request {
    /**
     * @var array Session parameters.
     */
    private $_session = null;
    /**
     * @var string Server hostname.
     */
    private $_host = null;
    /**
     * @var int Port number.
     */
    private $_port = null;
    /**
     * @var string Url path.
     */
    private $_path = null;
    /**
     * @var array GET parameters.
     */
    private $_get = [];
    /**
     * @var array POST parameters.
     */
    private $_post = [];
    /**
     * @var string Protocol.
     */
    private $_scheme = null;
    /**
     * @var array Url parsed arguments.
     */
    private $_args = [];
    /**
     * @var bool If request is post then true.
     */
    public $isPost = false;

    /**
     * Fill the args property with a path values.
     */
    private function parseArguments($path)
    {
        if(null !== $path) {
            
            $this->_args = explode('/', $path);
        }
    }

    /**
     * Clean url path from unneeded symbols.
     * 
     * @var string $path url path.
     * 
     * @return string cleaned path.
     */
    private function cleanPath($path)
    {
        $questionMarkIndex = strpos($path, '?');
        if(false !== $questionMarkIndex) {
            $path = strstr($path, '?', true);
        }
        
        $path = trim($path, '/');
        return $path;
    }

    /**
     * Fill properties with a values.
     */
    public function __construct()
    {
        $this->_port = $_SERVER['SERVER_PORT'];
        $this->_host = $_SERVER['HTTP_HOST'];
        $this->_path = $this->cleanPath($_SERVER['REQUEST_URI']);
        $this->_get = $_GET;
        $this->_post = $_POST;
        $this->_session = $_SESSION;
        $this->parseArguments($this->_path);
        if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
            $this->_scheme = 'https';
        }
        else {
            $this->_scheme = 'http';
        }
        $this->isPost = $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Getter.
     * 
     * @return mixed A property.
     */
    public function __get($name)
    {
        $name = "_{$name}";
        return $this->$name;
    }

    /**
     * Returns the url argument by its index or if its none the default value.
     * 
     * @param int $index Argument index.
     * @param mixed $default Value if there is none.
     * 
     * @return mixed A value.
     */
    public function arg(int $index, $default = null)
    {
        return $this->_args[$index] ?? $default;
    }

    /**
     * Gets the post argument.
     * @param $name
     * @param $default
     * @return mixed|null
     */
    public function post($name, $default) {
        return $this->_post[$name] ?? $default;
    }

    /**
     * Gets the session argument
     * @param $name
     * @param $default
     * @return mixed
     */
    public function session($name, $default) {
        return $this->_session[$name] ?? $default;
    }

    /**
     * Sets session value
     * @param $name
     * @param $value
     * @return void
     */
    public function sessionSet($name, $value) {
        $this->_session[$name] = $value;
        $_SESSION[$name] = $value;
    }
}