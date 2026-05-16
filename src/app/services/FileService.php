<?php

namespace app\services;

use app\db\repository\FileRepository;
use app\dto\DownloadFile;
use app\dto\PathInfo;
use app\http\request\File as RequestFile;
use app\db\models\File;

class FileService
{
    public function __construct(
        public FileSystem $fsl,
        public FileRepository $fileRepository,
    ) {}

    public function sha256(string $path): string {
        return hash('sha256', $path);
    }

    public function pathinfo(string $path): PathInfo {
        $info = pathinfo($path);
        return new PathInfo(
            $info["dirname"],
            $info["basename"],
            $info["extension"],
            $info["filename"],
        );
    }

    public function mimeType(string $path): string {
        return finfo_file(finfo_open(FILEINFO_MIME_TYPE), $path);
    }

    private function create(string $source, string $name, int $size, callable $callback): ?File {
        $path = date('Y') . '/' . date('m') . '/' . date('d');
        $fullPath = "{$this->fsl->upload()}/{$path}";
        $this->fsl->mkdir($fullPath);

        $hash = $this->sha256($source);
        $info = $this->pathinfo($name);
        $mimeType = $this->mimeType($source);

        $destination = "{$fullPath}/{$hash}.{$info->extension}";

        if (!$callback($source, $destination)) {
            return null;
        }

        $fileName = time() . '_' . $info->basename;

        return $this->fileRepository->create($path, $fileName, $mimeType, $size,  $hash);
    }

    public function createByLocalPath(string $filePath): ?File {
        $info = $this->pathinfo($filePath);
        $size = filesize($filePath);
        return $this->create(
            $filePath,
            $info->basename,
            $size,
            function (string $source, string $destination) {
                return copy($source, $destination);
            }
        );
    }

    public function upload(RequestFile $requestFile, string $file): ?File {
        if (!file_exists($requestFile->tmp_name)) { return null; }

        return $this->create(
            $requestFile->tmp_name,
            $requestFile->name,
            $requestFile->size,
            function (string $source, string $destination): bool {
                return move_uploaded_file($source, $destination);
            },
        );
    }

    public function getDownloadFileByPathAndName(string $path, string $name): ?DownloadFile {
        $data = $this->fileRepository->findByPathAndName($path, $name);

        if (is_null($data)) { return null; }

        $info = $this->pathinfo($data->name);

        return new DownloadFile(
            "{$this->fsl->upload()}/{$data->path}/{$data->hash}.{$info->extension}",
            $data->mime_type,
            $data->size,
        );
    }
}