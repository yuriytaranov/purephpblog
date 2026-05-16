<?php

namespace app\services;

use app\components\Orderer;
use app\components\Pager;
use app\db\models\Category;
use app\db\repository\CategoryRepository;
use app\db\repository\PostRepository;
use app\helpers\Text;

class CategoryService
{
    public function __construct(
        private CategoryRepository $categoryRepository,
        private PostRepository $postRepository,
    ) {}

    public function listWithPosts(): array {
        return $this->categoryRepository->listWithPosts();
    }

    public function list(): array {
        return $this->categoryRepository->list();
    }

    public function newCategory(array $category): Category {
        $name = $category['name'];
        $slug = $category['slug'] ?? null;
        if (!$slug) $slug = Text::slugify($name);
        $description = $category['description'];

        return $this->categoryRepository->create($name, $slug, $description);
    }

    public function categoryWithOrderAndPaging(string $slug, string $requestUri, int $page, int $limit, array $orderBy): ?array {
        $category = $this->categoryRepository->findBySlug($slug);
        if (is_null($category)) {
            return null;
        }

        $posts = $this->postRepository->listByCategory($category->id, ($page - 1) * $limit, $limit, $orderBy);

        $order = new Orderer(
            $requestUri,
            array_merge(['created_at' => Orderer::SORT_ASC, 'views' => Orderer::SORT_ASC], $orderBy)
        );
        $pager = new Pager($requestUri, $posts->total, $limit, $page, $posts->data);

        return [
            'category' => $category,
            'pager' => $pager,
            'order' => $order,
        ];
    }
}