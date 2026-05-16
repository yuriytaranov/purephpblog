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
use app\services\FileService;

class ManageController extends HttpController
{
    public function __construct(
        Request $request,
        Smarty $template,
        public CategoryRepository $categoryRepository,
        public PostRepository $postRepository,
        public FileService $fileService,
    ){
        parent::__construct($request, $template);
    }

    public function newPost(): Response {
        if (!$this->_request->isPost) {
            $categories = $this->categoryRepository->list();
            return $this->view("manage/post-form", ['categories' => $categories]);
        }

        $post = $this->_request->post('post', null);

        $image = $this->fileService->upload($this->_request, 'post_image');
        $name = $post['name'];
        $slug = $post['slug'];
        if (!$slug) $slug = Text::slugify($name);
        $description = $post['description'];
        $text = $post['text'];
        $categories = $post['categories'];

        $this->postRepository->create($image->id ?? null, $name, $slug, $description, $text, $categories);

        return $this->redirect('/');
    }

    public function newCategory(): Response {
        if (!$this->_request->isPost) {
            return $this->view("manage/category-form", []);
        }

        $category = $this->_request->post('category', null);

        $name = $category['name'];
        $slug = $category['slug'];
        if (!$slug) $slug = Text::slugify($name);
        $description = $category['description'];

        $this->categoryRepository->create($name, $slug, $description);

        return $this->redirect('/');
    }
}