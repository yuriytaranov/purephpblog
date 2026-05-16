<?php

namespace app\db\repository;

use app\db\drivers\Mysql;
use app\db\models\Post;
use app\dto\Pager;
use app\dto\PostWithImage;
use PDO;

class PostRepository
{
    public function __construct(private Mysql $db){}

    public function create(?int $imageId, string $name, string $slug, ?string $description, ?string $text, array $categories): Post {
        return $this->db->transaction(function (Mysql $db) use ($imageId, $name, $slug, $description, $text, $categories) {
            $id = $db->insert(
                "insert into `posts`(`file_id`,`name`,`slug`,`description`,`text`) 
                    values(:image, :name, :slug, :description, :text)",
                [
                    ':image' => $imageId,
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

    public function findById(int $id): ?Post {
        $data = $this->db->query(
            'select p.`id`,p.`name`,f.`path` as `image`,`slug`,`description`,`text`,`views`,p.`created_at`,`updated_at`
       from posts p
            left join `files` f on f.`id` = `p`.`file_id`
       where p.id = :id',
            ['id' => $id]
        )->fetch(PDO::FETCH_ASSOC);

        if (false === $data) { return null; }

        return $this->modelFromDbResult($data);
    }

    public function findBySlugWithFile(string $slug): ?PostWithImage {
        $data = $this->db->query(
            'select 
                p.`id`,p.`name`,f.`path` as `file_path`,f.name as file_name, f.mime_type as file_mime_type,
                f.size as file_size, `slug`,`description`,`text`,`views`,p.`created_at`,`updated_at`
            from posts p
            left join `files` f on f.`id` = `p`.`file_id`
            where slug = :slug',['slug' => $slug]
        )->fetch(PDO::FETCH_ASSOC);

        if (false === $data) { return null; }

        return new PostWithImage(
            $this->modelFromDbResult($data),
            $data['file_path'],
            $data['file_name'],
            $data['file_mime_type'],
            $data['file_size'],
        );
    }

    public function findSimilarPostsById(int $id, int $limit = 3): array {
        $data = $this->db->query(
            'select p.`id`,p.`name`,`slug`,f.`path` as `image` 
                from posts p 
                join `post_category_rel` pcr on p.id = pcr.post_id
                join `files` f on f.id = p.`file_id`
                where pcr.category_id in (
                    select category_id 
                    from post_category_rel
                    where post_id = :id
                ) 
                and p.id <> :id
                limit :limit',
            ['limit' => [$limit, PDO::PARAM_INT], 'id' => [$id, PDO::PARAM_INT]]
        )->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($item) => $this->modelFromDbResult($item), $data);
    }

    public function updateViewsById(int $id, int $views): void {
        $this->db->update('update `posts` set views = :views where `id` = :id', ['views' => $views, 'id' => $id]);
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
            "select p.`id`,p.`name`,f.`path` as `image`,`slug`,`description`,`text`,`views`,p.`created_at`,`updated_at`
            from `posts` p
            join `post_category_rel` pcr on `post_id` = p.`id`
            join `files` f on p.`file_id` = f.`id`
            where pcr.`category_id` = :category_id
            {$orderByQuery}
            limit :limit offset :offset",
            [':category_id' => $categoryId, ':limit' => [$limit, PDO::PARAM_INT], ':offset' => [$offset, PDO::PARAM_INT]]
        )->fetchAll(PDO::FETCH_ASSOC);

        return new Pager($count, array_map(fn($post) => $this->modelFromDbResult($post), $data));
    }

    public function modelFromDbResult(array $data): Post {
        $post = new Post();
        $post->id = $data['id'];
        $post->image = $data['file_id'] ?? 0;
        $post->name = $data['name'];
        $post->slug = $data['slug'];
        $post->description = $data['description'] ?? '';
        $post->text = $data['text'] ?? '';
        $post->views = $data['views'] ?? 0;
        $post->created_at = strtotime($data['created_at'] ?? 0);
        $post->updated_at = strtotime($data['updated_at'] ?? 0);

        return $post;
    }
}