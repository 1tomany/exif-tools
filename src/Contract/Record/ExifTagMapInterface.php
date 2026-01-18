<?php

namespace OneToMany\ExifTools\Contract\Record;

interface ExifTagMapInterface
{
    /**
     * @return array<non-empty-string, ExifTagInterface>
     */
    public function all(): array;

    /**
     * @param non-empty-string $tag
     */
    public function has(string $tag): bool;

    /**
     * @param non-empty-string $tag
     */
    public function get(string $tag): ?ExifTagInterface;
}
