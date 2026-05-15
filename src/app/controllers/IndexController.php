<?php

namespace app\controllers;

use app\components\Orderer;
use app\components\Pager;
use app\db\repository\CategoryRepository;
use app\db\repository\PostRepository;
use app\ext\Smarty;
use app\http\Request;
use app\http\Response;

class IndexController extends HttpController
{
    public function __construct(
        Request                    $request,
        Smarty                     $template,
        private CategoryRepository $categoryRepository,
        private PostRepository     $postRepository,
    )
    {
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

    public function category(string $slug): Response
    {
        $page = max(1, $this->_request->get('page', 1));
        $limit = $this->_request->get('limit', 20);
        $orderBy = $this->_request->get('sort', []);

        $category = $this->categoryRepository->findBySlug($slug);
        if (is_null($category)) {
            return $this->view('error', ['error' => 'Категория не найдена']);
        }

        $posts = $this->postRepository->listByCategory($category->id, ($page - 1) * $limit, $limit, $orderBy);

        $order = new Orderer(
            $this->_request->uri,
            array_merge(['created_at' => Orderer::SORT_ASC, 'views' => Orderer::SORT_ASC], $orderBy)
        );
        $pager = new Pager($this->_request->uri, $posts->total, $limit, $page, $posts->data);

        return $this->view("home/category", [
            'category' => $category,
            'pager' => $pager,
            'order' => $order,
        ]);
    }
}