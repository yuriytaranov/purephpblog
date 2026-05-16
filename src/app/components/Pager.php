<?php

namespace app\components;

class Pager
{
    const FIRST_PAGE = 1;
    private array $params;
    private string $path;
    private int $totalPages;
    public function __construct(
        public string $baseUrl,
        public int    $total,
        public int    $limit,
        public int    $current,
        public array  $data,
    ) {
        $parsed = parse_url($this->baseUrl);
        $this->path = $parsed['path'] ?? '';
        $params = [];
        if (isset($parsed['query'])) {
            parse_str($parsed['query'], $params);
        }

        $this->params = $params;
        $this->totalPages = (int)ceil($this->total / $this->limit);
    }

    public function pages(int $count = 10): array {
        $chunk = intval($count / 2);

        $first = max(self::FIRST_PAGE, $this->current - $chunk);
        $last = min($this->totalPages, $this->current + $chunk);

        if (self::FIRST_PAGE === $first) {
            $last = min($this->totalPages, $first + $count - 1);
        }

        if ($last === $this->totalPages) {
            $first = max(self::FIRST_PAGE, $last - $count + 1);
        }

        $result = [];
        for ($i = $first; $i <= $last; $i++) {
            $result[$i] = $this->linkByIndex($i);
        }

        return $result;
    }

    public function first(): string {
        return $this->linkByIndex(self::FIRST_PAGE);
    }

    public function last(): string {
        return $this->linkByIndex($this->totalPages);
    }

    private function linkByIndex(int $index): string {
        $params = $this->params;
        $params['page'] = $index;
        return $this->path . '?' . http_build_query($params);
    }

    public function isFirst(): bool {
        return $this->current === self::FIRST_PAGE;
    }

    public function isLast(): bool {
        return $this->current === $this->totalPages || 0 === $this->totalPages;
    }
}