<?php

namespace app\db\repository;

use app\db\drivers\Mysql;
use app\db\models\Category;
use PDO;

class CategoryRepository
{
    public function __construct(private Mysql $db) {}

    public function create(string $name, string $slug, ?string $description): Category
    {
        $id = $this->db->insert(
            "insert into `categories` (name, slug, description) values(:name, :slug, :description)",
            [
                ':name' => $name,
                ':slug' => $slug,
                ':description' => $description,
            ]
        );

        return $this->findById($id);
    }

    public function findById(int $id): Category
    {
        $data = $this->db->query(
            "select `id`,`name`,`slug`,`description`,`created_at`,`updated_at`  
                from `categories` where `id` = :id",
            [':id' => $id]
        )->fetch(PDO::FETCH_ASSOC);

        return $this->modelFromDbResult($data);
    }

    public function findBySlug(string $slug): ?Category
    {
        $data = $this->db->query(
            "select `id`,`name`,`slug`,`description`,`created_at`,`updated_at` 
                    from `categories` where `slug` = :slug",
            [':slug' => $slug]
        )->fetch(PDO::FETCH_ASSOC);

        return $this->modelFromDbResult($data);
    }

    private function modelFromDbResult(array $data): Category
    {
        $model = new Category();
        $model->id = $data['id'];
        $model->name = $data['name'];
        $model->slug = $data['slug'];
        $model->description = $data['description'];
        $model->created_at = strtotime($data['created_at']);
        $model->updated_at = strtotime($data['updated_at']);

        return $model;
    }
}