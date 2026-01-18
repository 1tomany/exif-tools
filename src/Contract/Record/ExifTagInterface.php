<?php

namespace OneToMany\ExifTools\Contract\Record;

interface ExifTagInterface
{
    /**
     * @return non-empty-string
     */
    public function getTag(): string;

    /**
     * @param non-empty-string $tag
     */
    public function isTag(string $tag): bool;

    /**
     * @return int|string|array<non-negative-int|non-empty-string, int|string>
     */
    public function getValue(): int|string|array;
}
