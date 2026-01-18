<?php

namespace OneToMany\ExifTools\Record;

use OneToMany\ExifTools\Contract\Record\ExifListInterface;
use OneToMany\ExifTools\Contract\Record\ExifMapInterface;
use OneToMany\ExifTools\Contract\Record\ExifValueInterface;

use function ctype_digit;
use function is_int;
use function is_string;
use function str_contains;
use function strlen;

final readonly class ExifValue implements ExifValueInterface
{
    public int|string|ExifListInterface|ExifMapInterface $value;

    public function __construct(int|string|ExifListInterface|ExifMapInterface $value)
    {
        if (is_string($value)) {
            // Convert integer strings
            if (ctype_digit($value)) {
                $value = (int) $value;
            }

            // Convert NUL bytes to a scalar or list
            if (str_contains($value, "\x00")) {
                if (1 === strlen($value)) {
                    $value = ord($value[0]);
                }

                $byteList = [];

                for ($i = 0; $i < strlen($value); ++$i) {
                    $byteList[] = ord($value[$i]);
                }

                // $value =
                // return $byteList;
            }

            return trim($value);
        }
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
}
