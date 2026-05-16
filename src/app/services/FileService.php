<?php

namespace app\services;

use app\db\repository\FileRepository;
use app\dto\DownloadFile;
use app\dto\PathInfo;
use app\http\Request;
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

    public function upload(Request $request, string $file): ?File {
        $requestFile = $request->file($file);
        if (is_null($requestFile)) { return null; }
        if (!file_exists($requestFile->tmp_name)) { return null; }

        $path = date('Y') . '/' . date('m') . '/' . date('d');
        $fullPath = "{$this->fsl->upload()}/{$path}";
        $this->fsl->mkdir($fullPath);

        $hash = $this->sha256($requestFile->tmp_name);
        $info = $this->pathinfo($requestFile->full_path);
        $mimeType = $this->mimeType($requestFile->tmp_name);
        $size = $requestFile->size;

        $destination = "{$fullPath}/{$hash}.{$info->extension}";

        if (!move_uploaded_file($requestFile->tmp_name, $destination)) {
            return null;
        }

        $fileName = time() . '_' . $info->basename;

        $data = $this->fileRepository->create($path, $fileName, $mimeType, $size,  $hash);

        if (is_null($data)) { return null; }

        return $data;
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