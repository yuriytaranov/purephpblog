<?php

namespace app\db\repository;

use app\db\drivers\Mysql;
use app\db\models\File;
use PDO;

class FileRepository
{
    public function __construct(
        public Mysql $db
    ) {}

    public function create(string $path, string $name, string $mimeType, int $size, string $hash): ?File {
        $id = $this->db->insert(
            'insert into `files` (`path`, `name`, `mime_type`, `size`, `hash`) 
            values (:path, :name, :mime_type, :size, :hash)',
            ['path' => $path, 'name' => $name, 'mime_type' => $mimeType, 'size' => $size, 'hash' => $hash]
        );

        $data = $this->db->query(
            'select `id`, `path`, `name`,`mime_type`,`size`, `hash`,`created_at`,`deleted_at` from `files` where `id` = :id',
            ['id' => $id]
        )->fetch(PDO::FETCH_ASSOC);

        if (false === $data) { return null; }

        return $this->modelFromDbResult($data);
    }

    public function findByPathAndName(string $path, string $name): ?File {
        $data = $this->db->query(
            'select `id`,`path`,`name`,`mime_type`,`size`,`hash`,`created_at`,`deleted_at`
            from `files` 
            where `path` = :path 
                and `name` = :name 
                and `deleted_at` is null',
            ['path' => $path, 'name' => $name]
        )->fetch(PDO::FETCH_ASSOC);

        if (false === $data) { return null; }

        return $this->modelFromDbResult($data);
    }

    public function modelFromDbResult(array $data): File {
        $file = new File();
        $file->id = $data['id'];
        $file->path = $data['path'];
        $file->name = $data['name'];
        $file->mime_type = $data['mime_type'];
        $file->size = $data['size'];
        $file->hash = $data['hash'];
        $file->created_at = strtotime($data['created_at']);
        $file->deleted_at = is_null($data['deleted_at']) ? null : strtotime($data['deleted_at']);

        return $file;
    }
}