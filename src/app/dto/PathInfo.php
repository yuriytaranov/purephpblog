<?php

namespace app\dto;

class PathInfo
{
    public function __construct(
        public string $dirname,
        public string $basename,
        public string $extension,
        public string $filename,
    ) {}
}