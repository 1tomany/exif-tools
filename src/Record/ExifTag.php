<?php

namespace OneToMany\ExifTools\Record;

use OneToMany\ExifTools\Contract\Record\ExifTagInterface;
use OneToMany\ExifTools\Exception\InvalidArgumentException;

use function strtolower;
use function trim;

/**
 * @phpstan-import-type ExifTagValue from ExifTagInterface
 */
final readonly class ExifTag implements ExifTagInterface
{
    /**
     * @var non-empty-string
     */
    public string $tag;

    /**
     * @var ExifTagValue
     */
    public int|string|array $value;

    /**
     * @param ExifTagValue $value
     */
    public function __construct(string $tag, int|string|array $value)
    {
        if (empty($tag = trim($tag))) {
            throw new InvalidArgumentException('The tag cannot be empty.');
        }

        $this->tag = $tag;
        $this->value = $value;
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
        return strtolower(trim($tag)) === strtolower($this->tag);
    }

    /**
     * @see OneToMany\ExifTools\Contract\Record\ExifTagInterface
     */
    public function getValue(): int|string|array
    {
        return $this->value;
    }
}
