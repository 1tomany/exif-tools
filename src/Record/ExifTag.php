<?php

namespace OneToMany\ExifTools\Record;

use OneToMany\ExifTools\Contract\Record\ExifTagInterface;
use OneToMany\ExifTools\Exception\InvalidArgumentException;

use function strtolower;
use function trim;

final readonly class ExifTag implements ExifTagInterface
{
    /**
     * @var non-empty-string
     */
    public string $tag;

    /**
     * @var int|string|array<int|string, int|string>
     */
    public int|string|array $value;

    public function __construct(string $tag, mixed $value)
    {
        if (empty($tag = trim($tag))) {
            throw new InvalidArgumentException('The tag cannot be empty.');
        }

        $this->tag = $tag;
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
