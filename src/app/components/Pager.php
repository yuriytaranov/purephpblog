<?php

namespace app\components;

class Pager
{
    public function __construct(
        public string $baseUrl,
        public int    $total,
        public int    $limit,
        public int    $current,
        public array  $data,
    ) {}

    public function pages(): array {
        $parsed = parse_url($this->baseUrl);
        $path = $parsed['path'] ?? '';
        $params = [];
        if (isset($parsed['query'])) {
            parse_str($parsed['query'], $params);
        }

        $result = [];
        for ($i = 1; $i <= (int)ceil($this->total / $this->limit); $i++) {
            $params['page'] = $i;
            $result[$i] = $path . '?' . http_build_query($params);
        }

        return $result;
    }
}