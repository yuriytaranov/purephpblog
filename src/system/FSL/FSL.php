<?php

namespace system\FSL;

/**
 * Class FSL is a file system layer.
 */
class FSL {
    public function __construct(private string $_root) {}

    public function root(): string {
        return $this->_root;
    }

    public function themes(): string {
        return $this->_root."/app/themes";
    }

    public function var(): string {
        return "{$this->_root}/var";
    }

    public function etc(): string {
        return "{$this->_root}/conf";
    }
}