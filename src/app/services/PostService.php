<?php

namespace app\services;

use app\db\models\Post;
use app\db\repository\PostRepository;
use app\helpers\Text;
use app\http\request\File;

class PostService
{
    public function __construct(
        private PostRepository $postRepository,
        private FileService $fileService,
    ) {}

    public function getPostBySlugWithSimilar(string $slug): ?array {
        $data = $this->postRepository->findBySlugWithFile($slug);

        if (is_null($data)) {
            return null;
        }

        $data->post->views += 1;
        $this->postRepository->updateViewsById($data->post->id, $data->post->views);
        $similarPosts = $this->postRepository->findSimilarPostsById($data->post->id);

        return [
            'post' => $data->post,
            'similar' => $similarPosts,
            'imageUrl' => "{$data->file_path}/{$data->file_name}",
        ];
    }

    public function newPost(File $requestFile, array $post): ?Post {
        $image = $this->fileService->upload($requestFile, 'post_image');
        $name = $post['name'];
        $slug = $post['slug'];
        if (!$slug) $slug = Text::slugify($name);
        $description = $post['description'];
        $text = $post['text'];
        $categories = $post['categories'];

        return $this->postRepository->create($image->id ?? null, $name, $slug, $description, $text, $categories);
    }
}