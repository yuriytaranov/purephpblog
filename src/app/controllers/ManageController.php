<?php

namespace app\controllers;

use app\db\models\Category;
use app\db\models\Post;
use app\db\repository\CategoryRepository;
use app\db\repository\PostRepository;
use app\ext\Smarty;
use app\http\Request;
use app\http\Response;
use app\helpers\Text;
use app\services\CategoryService;
use app\services\FileService;
use app\services\PostService;

class ManageController extends HttpController
{
    public function __construct(
        Request $request,
        Smarty $template,
        private CategoryService $categoryService,
        private PostService $postService,
    ){
        parent::__construct($request, $template);
    }

    public function newPost(): Response {
        if (!$this->_request->isPost) {
            $categories = $this->categoryService->list();
            return $this->view("manage/post-form", ['categories' => $categories]);
        }

        $post = $this->_request->post('post', null);
        $requestFile = $this->_request->file('post_image');

        $data = $this->postService->newPost($requestFile, $post);

        return $this->redirect("/post/{$data->slug}");
    }

    /**
     * @throws \Exception
     */
    public function newCategory(): Response {
        if (!$this->_request->isPost) {
            return $this->view("manage/category-form", []);
        }

        $category = $this->_request->post('category', null);

        $data = $this->categoryService->newCategory($category);

        return $this->redirect("/category/{$data->slug}");
    }
}