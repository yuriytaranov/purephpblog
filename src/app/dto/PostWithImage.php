<?php

namespace app\dto;

use app\db\models\File;
use app\db\models\Post;

class PostWithImage
{
    public function __construct(
        public Post $post,
        public ?string $file_path,
        public ?string $file_name,
        public ?string $file_mime_type,
        public ?string $file_size,
    ) {}
}