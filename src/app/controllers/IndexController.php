<?php

namespace app\controllers;

use app\db\repository\CategoryRepository;
use app\ext\Smarty;
use app\http\Request;
use app\http\Response;

class IndexController extends HttpController
{
    public function __construct(
        Request $request,
        public Smarty $template,
        private CategoryRepository $categoryRepository,
    ) {
        parent::__construct($request, $template);
    }
    /**
     * @throws \Exception
     */
    public function index(): Response
    {
        $data = $this->categoryRepository->listWithPosts();
        return $this->view("home/index", ['data' => $data]);
    }
}