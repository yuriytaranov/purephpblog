<?php

namespace app\controllers;

use app\components\Orderer;
use app\components\Pager;
use app\db\repository\CategoryRepository;
use app\db\repository\PostRepository;
use app\ext\Smarty;
use app\http\Request;
use app\http\Response;
use app\services\CategoryService;
use app\services\FileService;
use app\services\PostService;

class IndexController extends HttpController
{
    public function __construct(
        Request                    $request,
        Smarty                     $template,
        private CategoryService    $categoryService,
        private PostService        $postService,
    )
    {
        parent::__construct($request, $template);
    }

    /**
     * @throws \Exception
     */
    public function index(): Response
    {
        $data = $this->categoryService->listWithPosts();
        return $this->view("home/index", ['data' => $data]);
    }

    /**
     * @throws \Exception
     */
    public function category(string $slug): Response
    {
        $page = max(1, $this->_request->get('page', 1));
        $limit = $this->_request->get('limit', 3);
        $orderBy = $this->_request->get('sort', []);

        $data = $this->categoryService->categoryWithOrderAndPaging($slug, $this->_request->uri, $page, $limit, $orderBy);

        if (is_null($data)) {
            return $this->view('error', ['error' => 'Категория не найдена']);
        }

        return $this->view("home/category", $data);
    }

    /**
     * @throws \Exception
     */
    public function post(string $slug): Response {
        $data = $this->postService->getPostBySlugWithSimilar($slug);

        if (is_null($data)) {
            return $this->view('error', ['error' => 'Пост не найден']);
        }

        return $this->view("home/post", $data);
    }
}