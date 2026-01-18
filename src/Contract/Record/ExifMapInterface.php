<?php

namespace OneToMany\ExifTools\Contract\Record;

interface ExifMapInterface
{
    /**
     * @return array<non-empty-string, ExifValueInterface>
     */
    public function all(): array;

    /**
     * @param non-empty-string $tag
     */
    public function has(string $tag): bool;

    /**
     * @param non-empty-string $tag
     */
    public function get(string $tag): ?ExifValueInterface;
}
