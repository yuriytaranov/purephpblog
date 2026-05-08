<?php

namespace app\ext;

class Json {
    /**
     * Stored result.
     */
    private $_result = null;
    /**
     * Returns a json representation of a value.
     * @param mixed $value A string to represent.
     * 
     * @return string Representation.
     */
    public function encode($value)
    {
        return json_encode($value);
    }

    /**
     * Decodes a json string.
     * @param mixed $value A string to decode.
     * 
     * @retrun mixded 
     */
    public function decode($value)
    {
        return json_decode($value);
    }

    /**
     * Returns a json error.
     * 
     * @param int $status Status of an error.
     * @param string $message A message that should be displayed.
     * 
     * @return app\ext\Json self. 
     */
    public function error($status, $message)
    {
        $this->_result = ['result' => 'error', 'status' => $status, 'message' => $message];
        return $this;
    }

    /**
     * Returns a json success answer.
     * @param mixed $value Value to encode.
     * 
     * @return app\ext\Json self.
     */
    public function success($value)
    {
        $this->_result = ['reslut' => 'success', 'data' => $value];
        return $this;
    }

    /**
     * Returs a strored result.
     * @return mixed Stored result.
     */
    public function result()
    {
        return $this->_result;
    }

    /**
     * Send a encoded json result.
     */
    public function send()
    {
        return $this->encode($this->_result);
    }
}