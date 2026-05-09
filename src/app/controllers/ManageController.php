<?php

namespace app\controllers;

use app\db\models\Category;
use app\db\models\Post;
use app\db\repository\CategoryRepository;
use app\db\repository\PostRepository;
use app\ext\Smarty;
use app\http\Request;
use app\http\Response;
use app\utils\Text;

class ManageController extends HttpController
{
    public function __construct(
        Request $request,
        Smarty $template,
        public CategoryRepository $categoryRepository,
        public PostRepository $postRepository,
    ){
        parent::__construct($request, $template);

    }

    public function newPost(): Response {
        return $this->view("manage/post-form", ['post' => new Post()]);
    }

    public function savePost(): Response {
        if (!$this->_request->isPost) {
            return $this->view('error', ['error' => 'Страница не найдена']);
        }

        $post = $this->_request->post('post', null);

        $image = $post['image'];
        $name = $post['name'];
        $slug = $post['slug'];
        if (!$slug) $slug = Text::slugify($name);
        $description = $post['description'];
        $text = $post['text'];

        $this->postRepository->create($image, $name, $slug, $description, $text);

        return $this->redirect('/');
    }

    public function newCategory(): Response {
        return $this->view("manage/category-form", ['category' => new Category()]);
    }

    public function saveCategory(): Response {
        if (!$this->_request->isPost) {
            return $this->view('error', ['error' => 'Страница не найдена']);
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