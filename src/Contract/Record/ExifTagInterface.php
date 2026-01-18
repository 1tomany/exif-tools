<?php

namespace OneToMany\ExifTools\Contract\Record;

/**
 * @phpstan-type ExifTagValue scalar|list<scalar|null>|array<non-empty-string, scalar|null>|null
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
    public function getValue(): bool|int|float|string|array|null;
}
