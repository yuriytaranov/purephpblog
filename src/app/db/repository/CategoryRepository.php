<?php

namespace app\db\repository;

use app\db\drivers\Mysql;
use app\db\models\Category;
use app\dto\ListWithPostsItem;
use app\dto\ListWithPostsPostItem;
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

    public function list(): array {
        $data = $this->db->query(
            'select `id`,`name`,`slug`,`description`,`created_at`,`updated_at` 
                from `categories` where `deleted_at` is null')->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach($data as $item) {
            $result[] = $this->modelFromDbResult($item);
        }

        return $result;
    }

    public function listWithPosts(int $postsInCategory = 3): array {
        $rows = $this->db->query(
            'select
                c.id as category_id,
                c.name as category_name,
                c.slug as category_slug,
                t.id as post_id,
                t.name as post_name,
                t.slug as post_slug,
                t.created_at as post_created_at
            from (
                select
                    pcr.category_id,
                    p.*,
                    row_number() over (
                        partition by pcr.category_id
                        order by p.created_at desc
                    ) as rn
                from post_category_rel pcr
                join posts p on p.id = pcr.post_id
                where p.deleted_at is null
            ) as t
            join categories c on c.id = t.category_id
            where t.rn <= :posts_in_category
            and c.deleted_at is null
            order by category_id, post_created_at desc;
            ',
            [
                ':posts_in_category' => $postsInCategory
            ]
        )->fetchAll(PDO::FETCH_ASSOC);

        $result = [];

        foreach ($rows as $row) {
            $catId = $row['category_id'];

            if (!isset($result[$catId])) {
                $result[$catId] = new ListWithPostsItem();
                $result[$catId]->category_id = $row['category_id'];
                $result[$catId]->category_name = $row['category_name'];
                $result[$catId]->category_slug = $row['category_slug'];
                $result[$catId]->posts = [];
            }

            $post = new ListWithPostsPostItem();
            $post->post_id = $row['post_id'];
            $post->post_name = $row['post_name'];
            $post->post_slug = $row['post_slug'];

            $result[$catId]->posts[] = $post;
        }

        return array_values($result);
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