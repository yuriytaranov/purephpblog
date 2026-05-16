<?php

namespace app\db\models;

class File
{
    public int $id;
    public string $path;
    public string $name;
    public string $mime_type;
    public int $size;
    public string $hash;
    public int $created_at;
    public ?int $deleted_at;
}