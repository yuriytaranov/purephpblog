<?php

namespace app\db\models;

class Post
{
    public string $table = 'posts';
    public int $id;
    public ?string $image;
    public string $name;
    public string $slug;
    public ?string $description;
    public ?string $text;
    public int $views;
    public int $created_at;
    public int $updated_at;
    public ?int $deleted_at;
}