<?php

namespace app;

use app\http\Request;
/**
 * All controllers that should return app\Response must extend this class.
 */
abstract class Controller {
    protected Request|null $_request = null;

    public function __construct(Request $request)
    {
        $this->_request = $request;
    }
}