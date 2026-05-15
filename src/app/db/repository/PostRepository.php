<?php

namespace app\db\repository;

use app\db\drivers\Mysql;
use app\db\models\Post;
use app\dto\Pager;
use PDO;

class PostRepository
{
    public function __construct(private Mysql $db){}

    public function create(string $image, string $name, string $slug, ?string $description, ?string $text, array $categories): Post {
        return $this->db->transaction(function (Mysql $db) use ($image, $name, $slug, $description, $text, $categories) {
            $id = $db->insert(
                "insert into `posts`(`image`,`name`,`slug`,`description`,`text`) 
                    values(:image, :name, :slug, :description, :text)",
                [
                    ':image' => $image,
                    ':name' => $name,
                    ':slug' => $slug,
                    ':description' => $description,
                    ':text' => $text,
                ]
            );
            foreach ($categories as $category) {
                $db->insert(
                    "insert into `post_category_rel`(`post_id`, `category_id`) values(:id, :category_id)",
                    [
                        ':id' => $id,
                        ':category_id' => $category
                    ]
                );
            }

            return $this->findById($id);
        });
    }

    public function findById(int $id): Post {
        $data = $this->db->query(
            'select `id`,`name`,`image`,`slug`,`description`,`text`,`views`,`created_at`,`updated_at`
       from posts where id = :id',
            ['id' => $id]
        )->fetch(PDO::FETCH_ASSOC);

        return $this->modelFromDbResult($data);
    }

    public function listByCategory(int $categoryId, int $offset = 0, int $limit = 20, array $orderBy = []): Pager {
        $orderByQuery = '';
        if (!empty($orderBy)) {
            $orderByArr = [];
            if (isset($orderBy['created_at'])) {
                $q = (1 == $orderBy['created_at']) ? 'desc' : 'asc';
                $orderByArr[] = " `created_at` {$q}";
            }
            if (isset($orderBy['views'])) {
                $q = (1 == $orderBy['views']) ? 'desc' : 'asc';
                $orderByArr[] = " `views` {$q}";
            }
            if (count($orderByArr) > 0) {
                $orderByQuery = ' order by ' . implode(', ', $orderByArr);
            }
        }

        $count = $this->db->query(
            'select count(p.`id`) as `count` 
            from posts p
            join `post_category_rel` pcr on `post_id` = p.`id`
            where pcr.`category_id` = :category_id',
            ['category_id' => $categoryId]
        )->fetchColumn();

        if ($count == 0) { return new Pager(0, []); }

        $data = $this->db->query(
            "select p.`id`,`name`,`image`,`slug`,`description`,`text`,`views`,`created_at`,`updated_at`
            from `posts` p
            join `post_category_rel` pcr on `post_id` = p.`id`
            where pcr.`category_id` = :category_id
            {$orderByQuery}
            limit :limit offset :offset",
            [':category_id' => $categoryId, ':limit' => [$limit, PDO::PARAM_INT], ':offset' => [$offset, PDO::PARAM_INT]]
        )->fetchAll(PDO::FETCH_ASSOC);

        if (false === $data) {
            return new Pager($count, []);
        }

        return new Pager($count, array_map(fn($post) => $this->modelFromDbResult($post), $data));
    }

    public function modelFromDbResult(array $data): Post {
        $post = new Post();
        $post->id = $data['id'];
        $post->image = $data['image'];
        $post->name = $data['name'];
        $post->slug = $data['slug'];
        $post->description = $data['description'];
        $post->text = $data['text'];
        $post->views = $data['views'];
        $post->created_at = strtotime($data['created_at']);
        $post->updated_at = strtotime($data['updated_at']);

        return $post;
    }
}