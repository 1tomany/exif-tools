<?php

namespace OneToMany\ExifTools\Record;

use OneToMany\ExifTools\Exception\LogicException;

use function array_is_list;
use function count;
use function ctype_digit;
use function explode;
use function is_int;
use function is_numeric;
use function is_string;
use function ord;
use function str_contains;
use function strlen;
use function substr_count;
use function trim;

/**
 * @phpstan-type ExifValueList list<int|string>
 * @phpstan-type ExifValueMap array<string, int|string>
 */
final readonly class ExifValue implements \Stringable
{
    private int|string|ExifList|ExifMap $value;

    /**
     * @param int|string|ExifValueList|ExifValueMap $value
     */
    public function __construct(int|string|array $value)
    {
        $this->value = $this->clean($value);
    }

    public function __toString(): string
    {
        if ($this->isInt() || $this->isString()) {
            return (string) $this->value;
        }

        return $this->value->__toString();
    }

    public function get(): int|string|ExifList|ExifMap
    {
        return $this->value;
    }

    /**
     * @return int|string|ExifValueList|ExifValueMap
     */
    public function value(): int|string|array
    {
        if ($this->isList() || $this->isMap()) {
            return $this->value->toArray(); // @phpstan-ignore-line
        }

        return $this->value;
    }

    /**
     * @phpstan-assert-if-true int $this->get()
     * @phpstan-assert-if-true int $this->value()
     * @phpstan-assert-if-true int $this->value
     */
    public function isInt(): bool
    {
        return is_int($this->value);
    }

    /**
     * @phpstan-assert-if-true string $this->get()
     * @phpstan-assert-if-true string $this->value()
     * @phpstan-assert-if-true string $this->value
     */
    public function isString(): bool
    {
        return is_string($this->value);
    }

    /**
     * @phpstan-assert-if-true ExifList $this->get()
     * @phpstan-assert-if-true ExifValueList $this->value()
     * @phpstan-assert-if-true ExifList $this->value
     */
    public function isList(): bool
    {
        return $this->value instanceof ExifList;
    }

    /**
     * @phpstan-assert-if-true ExifMap $this->get()
     * @phpstan-assert-if-true ExifValueMap $this->value()
     * @phpstan-assert-if-true ExifMap $this->value
     */
    public function isMap(): bool
    {
        return $this->value instanceof ExifMap;
    }

    /**
     * This attempts to convert integers, numeric strings, and fractional strings to
     * a floating point number. EXIF encodes decimals as a fraction (ex: "3930/100"),
     * so the fractional components are extracted, divded, and returned as a float.
     *
     * @throws LogicException if the value is not an integer or string
     */
    public function toFloat(): ?float
    {
        if ($this->isList() || $this->isMap()) {
            throw new LogicException('Lists and maps cannot be converted to floating point numbers.');
        }

        if ($this->isInt()) {
            return (float) $this->value;
        }

        if (empty($this->value)) {
            return null;
        }

        // EXIF encodes floats as a rational number
        if (1 === substr_count($this->value, '/')) {
            [$num, $den] = explode('/', $this->value, 2);

            if (!(float) $den) {
                return null;
            }

            return (float) $num / (float) $den;
        }

        return is_numeric($this->value) ? (float) $this->value : null;
    }

    /**
     * @param int|string|ExifValueList|ExifValueMap $value
     */
    private function clean(int|string|array $value): int|string|ExifList|ExifMap
    {
        if (is_int($value)) {
            return $value;
        }

        if (is_string($value)) {
            // Convert integer strings
            if (ctype_digit($value)) {
                return (int) $value;
            }

            // Attempt to convert NUL bytes
            if (str_contains($value, "\x00")) {
                $nulByteList = [];

                for ($i = 0; $i < strlen($value); ++$i) {
                    $nulByteList[] = ord($value[$i]);
                }

                // Likely an integer or enum value
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

        return new ExifMap($value);
    }
}
