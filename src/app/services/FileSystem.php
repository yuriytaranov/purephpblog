<?php

namespace app\services;

/**
 * Class FileSystem is a file system layer.
 */
class FileSystem {
    public function __construct(
        private string $_root,
        private ?string $_var = null,
    ) {}

    public function root(): string {
        return $this->_root;
    }

    public function migrations(): string {
        return $this->_root.'/app/db/migrations';
    }

    public function themes(): string {
        return $this->_root.'/app/themes';
    }

    public function var(): string {
        return is_null($this->_var) ? $this->_root.'/var' : $this->_var;
    }

    public function etc(): string {
        return $this->_root.'/conf';
    }

    public function upload(): string {
        return $this->var().'/upload';
    }

    public function mkdir(string $path): bool {
        if (file_exists($path)) {
            return true;
        }

        return mkdir($path, 0777, true);
    }
}