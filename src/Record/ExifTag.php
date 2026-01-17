<?php

namespace OneToMany\ExifTools\Record;

use OneToMany\ExifTools\Contract\Record\ExifTagInterface;

use function strtolower;
use function trim;

/**
 * @phpstan-import-type ExifTagValue from ExifTagInterface
 */
final readonly class ExifTag implements ExifTagInterface
{
    /**
     * @param non-empty-string $tag
     * @param ExifTagValue $value
     */
    public function __construct(
        public string $tag,
        public int|string|array $value,
    ) {
    }

    /**
     * @see OneToMany\ExifTools\Contract\Record\ExifTagInterface
     */
    public function getTag(): string
    {
        return $this->tag;
    }

    /**
     * @see OneToMany\ExifTools\Contract\Record\ExifTagInterface
     */
    public function isTag(string $tag): bool
    {
        return strtolower(trim($tag)) === $this->tag;
    }

    /**
     * @see OneToMany\ExifTools\Contract\Record\ExifTagInterface
     */
    public function getValue(): int|string|array
    {
        throw new \Exception('Not implemented');
    }
}
