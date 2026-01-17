<?php

namespace OneToMany\ExifTools\Record;

use OneToMany\ExifTools\Contract\Record\ExifTagInterface;

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
    public function getValue(): int|string|array
    {
        throw new \Exception('Not implemented');
    }
}
