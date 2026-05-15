<?php

namespace app\components;

class Orderer
{
    const SORT_ASC = 0;
    const SORT_DESC = 1;
    public function __construct(
        public string $baseUrl,
        public array $order
    ) {}

    public function link(string $name): string {
        $parsed = parse_url($this->baseUrl);
        $path = $parsed['path'] ?? '';
        $params = [];
        if (isset($parsed['query'])) {
            parse_str($parsed['query'], $params);
        }

        if (!isset($params['sort'])) {
            $params['sort'] = [];
        }

        $currentOrder = $params['sort'][$name] ?? self::SORT_ASC;
        $newOrder = (self::SORT_DESC == $currentOrder) ? self::SORT_ASC : self::SORT_DESC;
        $params['sort'] = [$name => $newOrder];

        return $path . '?' . http_build_query($params);
    }
}