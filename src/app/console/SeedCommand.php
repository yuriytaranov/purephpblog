<?php

namespace app\console;

use app\Command;
use app\db\drivers\Mysql;
use app\services\CategoryService;
use app\services\FileService;
use app\services\FileSystem;
use app\services\PostService;
use Imagick;
use ImagickDraw;
use ImagickPixel;
use PDO;
use Random\RandomException;

class SeedCommand extends Command
{
    public function __construct(
        private PostService     $postService,
        private CategoryService $categoryService,
        private FileService     $fileService,
        private FileSystem      $fileSystem
    ) {}


    /**
     * @throws RandomException
     */
    public function handle(): void
    {
        $this->writeln("Создаем категории");
        $categories = [];
        foreach (['Новости', 'Спорт', 'Технологии', 'Музыка', 'Игры'] as $name) {
            $categories[] = $this->categoryService->newCategory([
                'name' => $name,
                'description' => "Описание категории {$name}",
            ]);
        }

        $this->writeln("Создаем посты");

        for ($i = 1; $i <= 60; $i++) {
            $title = "Пост номер {$i}";
            $description = "Описание поста номер {$i}";
            $text = "Текст поста номер {$i}. Здесь может быть большой контент.";

            $category = $categories[array_rand($categories)];

            $originalFilePath = "{$this->fileSystem->test()}/data/post-image.jpg";
            $tempFilePath = $this->fileSystem->tmp() . '/' . uniqid() . '.jpg';
            $img = file_get_contents($originalFilePath);
            $img .= random_bytes(1);
            file_put_contents($tempFilePath, $img);

            $image = $this->fileService->createByLocalPath($tempFilePath);

            $this->postService->createPost(
                [
                    'name' => $title,
                    'description' => $description,
                    'text' => $text,
                    'categories' => [$category->id],
                ],
                $image
            );
        }

        $this->writeln("Готово!");
    }
}