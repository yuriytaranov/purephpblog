<?php

namespace app\controllers;

use app\components\Orderer;
use app\components\Pager;
use app\db\repository\CategoryRepository;
use app\db\repository\PostRepository;
use app\ext\Smarty;
use app\http\Request;
use app\http\Response;
use app\services\FileService;

class FileController
{
    public function __construct(
        public Request                    $request,
        public FileService                $fileService,
    ) {}

    /**
     * @throws \Exception
     */
    public function index(string $path, string $name): Response
    {
        $file = $this->fileService->getDownloadFileByPathAndName($path, $name);

        $response = new Response();
        $response->header(["Content-Type" => $file->mimeType]);
        $response->header(["Content-Length" => $file->size]);
        $response->header(["Content-Disposition" => "attachment; filename=\"{$name}\""]);

        return $response->set(file_get_contents($file->path));
    }
}