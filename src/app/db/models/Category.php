<?php

namespace app\db\models;

class Category
{
    public int $id;
    public string $name;
    public string $slug;
    public ?string $description;
    public string $created_at;
    public string $updated_at;
    public ?string $deleted_at;
}