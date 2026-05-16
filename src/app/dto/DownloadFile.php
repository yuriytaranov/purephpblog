<?php

namespace app\dto;

class DownloadFile
{
    public function __construct(
        public string $path,
        public string $mimeType,
        public int $size,
    ) {}
}