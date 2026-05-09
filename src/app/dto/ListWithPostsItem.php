<?php

namespace app\dto;

class ListWithPostsItem {
    public int $category_id;
    public string $category_name;
    public string $category_slug;
    public array $posts;
}