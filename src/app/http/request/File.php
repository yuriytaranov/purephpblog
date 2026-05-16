<?php

namespace app\http\request;

class File
{
    public function __construct(
        public string $name,
        public string $type,
        public int $size,
        public string $tmp_name,
        public string $error,
        public string $full_path,
    ) {}
}