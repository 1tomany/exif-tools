<?php

namespace OneToMany\ExifTools\Contract\Record;

/**
 * @phpstan-type ExifTagValue int|string|list<int|string>|array<non-empty-string, int|string>
 */
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
     * @return ExifTagValue
     */
    public function getValue(): int|string|array;
}
