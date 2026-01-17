<?php

namespace OneToMany\ExifTools\Contract\Record;

/**
 * @phpstan-type ExifTagValue bool|int|float|string|list<int|string>|array<non-empty-string, int|string>|null
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
