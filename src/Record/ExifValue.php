<?php

namespace OneToMany\ExifTools\Record;

use OneToMany\ExifTools\Contract\Record\ExifListInterface;
use OneToMany\ExifTools\Contract\Record\ExifMapInterface;
use OneToMany\ExifTools\Contract\Record\ExifValueInterface;

use function array_is_list;
use function count;
use function ctype_digit;
use function is_int;
use function is_string;
use function ord;
use function str_contains;
use function strlen;
use function trim;

final readonly class ExifValue implements ExifValueInterface
{
    public int|string|ExifListInterface|ExifMapInterface $value;

    /**
     * @param int|string|list<int|string>|array<non-empty-string, int|string> $value
     */
    public function __construct(int|string|array $value)
    {
        $this->value = $this->clean($value);
    }

    public function getValue(): int|string|ExifListInterface|ExifMapInterface
    {
        return $this->value;
    }

    public function isInt(): bool
    {
        return is_int($this->value);
    }

    public function isString(): bool
    {
        return is_string($this->value);
    }

    public function isList(): bool
    {
        return $this->value instanceof ExifListInterface;
    }

    public function isMap(): bool
    {
        return $this->value instanceof ExifMapInterface;
    }

    /**
     * @param int|string|list<int|string>|array<non-empty-string, int|string> $value
     */
    private function clean(int|string|array $value): int|string|ExifListInterface|ExifMapInterface
    {
        if (is_int($value)) {
            return $value;
        }

        if (is_string($value)) {
            // Convert integer strings
            if (ctype_digit($value)) {
                return (int) $value;
            }

            // Attempt to conver NUL bytes
            if (str_contains($value, "\x00")) {
                $nulByteList = [];

                for ($i = 0; $i < strlen($value); ++$i) {
                    $nulByteList[] = ord($value[$i]);
                }

                if (1 === count($nulByteList)) {
                    return $nulByteList[0];
                }

                return new ExifList($nulByteList);
            }

            return trim($value);
        }

        if (array_is_list($value)) {
            return new ExifList($value);
        }

        return new ExifMap($value); // @phpstan-ignore-line
    }
}
