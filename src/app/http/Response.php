<?php

namespace app\http;

use app\ext\Json;

class Response {
    /**
     * @var string Stored result.
     */
    private $_result = '';
    /**
     * @var array Response headers.
     */
    private $_headers = [];
    /**
     * @var app\ext\Json|null If this var has a vaule the entire response sets to application/json.
     */
    private $_json = null;

    /**
     * Getter.
     */
    public function __get($name)
    {
        $name = "_{$name}";

        if("_json" === $name && null === $this->_json) {
            $this->header(["Content-Type" => "application/json"]);
            $this->_json = new Json();
        }

        return $this->$name;
    }

    /**
     * Set the response result.
     * @param $result
     * @return Response
     */
    public function set($result): Response
    {
        $this->_result = $result;
        return $this;
    }

    /**
     * If resposne have headers it need to be sended first.
     */
    private function sendHeaders()
    {
        foreach($this->_headers as $name => $value) {
            header("{$name}: {$value}");
        }
    }

    /**
     * Sends the result processed by application.
     * 
     * @return string Processed result.
     */
    public function send()
    {
        $this->sendHeaders();
        if(null === $this->_json) {
            return $this->_result;
        }
        return $this->_json->send();
    }

    /**
     * Adds header to further response.
     */
    public function header(array $header)
    {
        $name = key($header);
        $this->_headers[$name] = $header[$name];
    }
}