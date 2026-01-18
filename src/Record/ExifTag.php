<?php

namespace OneToMany\ExifTools\Record;

use OneToMany\ExifTools\Contract\Record\ExifTagInterface;
use OneToMany\ExifTools\Contract\Record\ExifTagListInterface;
use OneToMany\ExifTools\Exception\InvalidArgumentException;

use function array_is_list;
use function is_int;
use function is_string;
use function strtolower;
use function trim;

final readonly class ExifTag implements ExifTagInterface
{
    /**
     * @var non-empty-string
     */
    public string $tag;

    /**
     * @var int|string|list<int|string>|ExifTagListInterface
     */
    public int|string|array|ExifTagListInterface $value;

    /**
     * @param int|string|list<int|string>|array<non-empty-string, int|string> $value
     */
    public function __construct(string $tag, int|string|array $value)
    {
        if (empty($tag = trim($tag))) {
            throw new InvalidArgumentException('The tag cannot be empty.');
        }

        $this->tag = $tag;

        if (is_int($value)) {
            $this->value = $value;
        } elseif (is_string($value)) {
            $this->value = trim($value);
        } else {
            if (array_is_list($value)) {
                $this->value = $value;
            } else {
                $this->value = new ExifTagList($value);
            }
        }
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
    public function getValue(): int|string|array|ExifTagListInterface
    {
        return $this->value;
    }
}
