<?php

namespace app\db\repository;

use app\db\drivers\Mysql;
use app\db\models\Post;
use PDO;

class PostRepository
{
    public function __construct(private Mysql $db){}

    public function create(string $image, string $name, string $slug, ?string $description, ?string $text): Post {
        $id = $this->db->insert(
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

        return $this->findById($id);
    }

    public function findById(int $id): Post {
        $data = $this->db->query(
            'select `id`,`name`,`image`,`slug`,`description`,`text`,`views`,`created_at`,`updated_at`
       from posts where id = :id',
            ['id' => $id]
        )->fetch(PDO::FETCH_ASSOC);

        return $this->modelFromDbResult($data);
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