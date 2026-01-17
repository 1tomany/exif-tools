<?php

namespace OneToMany\ExifTools\Record;

use OneToMany\ExifTools\Contract\Record\ExifTagInterface;
use OneToMany\ExifTools\Exception\InvalidArgumentException;

use function ctype_digit;
use function is_int;
use function is_string;
use function ord;
use function strlen;
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
    public bool|int|float|string|array|null $value;

    public function __construct(string $tag, mixed $value)
    {
        if (empty($tag = trim($tag))) {
            throw new InvalidArgumentException('The tag cannot be empty.');
        }

        $this->tag = $tag;

        // Convert NUL bytes to ASCII bytes
        $this->value = $this->cleanValue($value);
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
    public function getValue(): int|float|string|array|null
    {
        return $this->value;
    }

    /**
     * @return ExifTagValue
     */
    private function cleanValue(mixed $value): int|float|string|array|null
    {
        if (is_int($value)) {
            return $value;
        }

        if (is_string($value)) {
            // Convert integer strings
            if (ctype_digit($value)) {
                return (int) $value;
            }

            // Convert NUL-bytes to scalars
            if (str_contains($value, "\x00")) {
                $length = strlen($value);

                if (1 === $length) {
                    return ord($value[0]);
                }

                $bytes = [];

                for ($i = 0; $i < $length; ++$i) {
                    $bytes[] = ord($value[$i]);
                }

                return $bytes;
            }

            return trim($value);
        }

        foreach ($value as $k => $v) {
            $value[$k] = $this->cleanValue($v);
        }

        return $value;
    }
}
