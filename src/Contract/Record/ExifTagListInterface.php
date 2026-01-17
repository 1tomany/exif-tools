<?php

namespace OneToMany\ExifTools\Contract\Record;

interface ExifTagListInterface
{
    /**
     * @return list<ExifTagInterface>
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
