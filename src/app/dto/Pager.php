<?php

namespace app\dto;

class Pager
{
    public function __construct(
        public int   $total,
        public array $data,
    ){}
}