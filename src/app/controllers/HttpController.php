<?php

namespace app\controllers;

use app\Controller;
use app\ext\Smarty;
use app\ext\Template;
use app\http\Request;
use app\http\Response;
use WebApp;

abstract class HttpController extends Controller {
    /** @var WebApp $app */
    protected $app = null;

    public function __construct(Request $request, public Smarty $template) {
        parent::__construct($request);
    }

    /**
     * Render view and return response.
     * @param string $view
     * @param array $data
     * @return Response
     * @throws \Exception
     */
    public function view(string $view, array $data): Response {
        $content = $this->template->render($view, $data);
        $response = new Response();
        $response->set($content);
        return $response;
    }

    public function redirect(string $url): Response {
        $response = new Response();
        $response->header(["Location" => $url]);
        return $response;
    }
}