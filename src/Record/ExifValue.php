<?php

namespace OneToMany\ExifTools\Record;

use OneToMany\ExifTools\Exception\LogicException;

use function array_is_list;
use function explode;
use function is_float;
use function is_int;
use function is_numeric;
use function is_string;
use function ord;
use function strlen;
use function substr_count;
use function trim;

/**
 * @phpstan-type ExifValueList list<int|float|string>
 * @phpstan-type ExifValueMap array<string, int|float|string>
 */
final readonly class ExifValue implements \Stringable
{
    private int|float|string|ExifList|ExifMap $value;

    /**
     * @param int|float|string|ExifValueList|ExifValueMap $value
     */
    public function __construct(int|float|string|array $value)
    {
        $this->value = $this->clean($value);
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }

    public function get(): int|float|string|ExifList|ExifMap
    {
        return $this->value;
    }

    /**
     * @return int|float|string|ExifValueList|ExifValueMap
     */
    public function value(): int|float|string|array
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
     * @phpstan-assert-if-true float $this->value
     * @phpstan-assert-if-true float $this->get()
     * @phpstan-assert-if-true float $this->value()
     */
    public function isFloat(): bool
    {
        return is_float($this->value);
    }

    /**
     * @phpstan-assert-if-true string $this->value
     * @phpstan-assert-if-true string $this->get()
     * @phpstan-assert-if-true string $this->value()
     */
    public function isString(): bool
    {
        return is_string($this->value);
    }

    /**
     * @phpstan-assert-if-true int|float|string $this->value
     * @phpstan-assert-if-true int|float|string $this->get()
     * @phpstan-assert-if-true int|float|string $this->value()
     */
    public function isScalar(): bool
    {
        return $this->isInt() || $this->isFloat() || $this->isString();
    }

    /**
     * @phpstan-assert-if-true ExifList $this->value
     * @phpstan-assert-if-true ExifList $this->get()
     * @phpstan-assert-if-true ExifValueList $this->value()
     */
    public function isList(): bool
    {
        return $this->value instanceof ExifList;
    }

    /**
     * @phpstan-assert-if-true ExifMap $this->value
     * @phpstan-assert-if-true ExifMap $this->get()
     * @phpstan-assert-if-true ExifValueMap $this->value()
     */
    public function isMap(): bool
    {
        return $this->value instanceof ExifMap;
    }

    /**
     * This attempts to convert integers, numeric strings, and fractional strings to
     * a floating point number. EXIF encodes decimals as a fraction (ex: "3930/100"),
     * so the fractional components are extracted, divided, and returned as a float.
     *
     * @throws LogicException when the value is not a scalar
     */
    public function toFloat(): ?float
    {
        if (!$this->isScalar()) {
            throw new LogicException('Non-scalar values cannot be converted to floats.');
        }

        if ($this->isFloat()) {
            return $this->value;
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
     * Attempts to convert integers and strings to a
     * timestamp. Strings will be evaluated using the
     * formats 'Y:m:d H:i:s' and 'Y:m:d' in that order.
     */
    public function toTimestamp(): ?\DateTimeImmutable
    {
        try {
            if ($this->isInt()) {
                return \DateTimeImmutable::createFromTimestamp($this->value);
            }

            if ($this->isString()) {
                foreach (['Y:m:d H:i:s', 'Y:m:d'] as $stringFormat) {
                    $timestamp = \DateTimeImmutable::createFromFormat($stringFormat, $this->value);

                    if (false !== $timestamp) {
                        return $timestamp;
                    }
                }
            }
        } catch (\Throwable) {
        }

        return null;
    }

    /**
     * @param int|float|string|ExifValueList|ExifValueMap $value
     */
    private function clean(int|float|string|array $value): int|float|string|ExifList|ExifMap
    {
        if (is_float($value)) {
            return $value;
        }

        if (is_int($value)) {
            return $value;
        }

        if (is_string($value)) {
            // Convert control bytes to integers
            if ($valueLength = strlen($value)) {
                $controlCharacters = [];

                for ($i = 0; $i < $valueLength; ++$i) {
                    $c = ord($value[$i]);

                    if ($c < 0x20 || 0x7F === $c) {
                        $controlCharacters[] = $c;
                    }
                }

                if (isset($controlCharacters[0])) {
                    // Cast a single byte as an integer
                    if (!isset($controlCharacters[1])) {
                        return $controlCharacters[0];
                    }

                    // Convert multiple bytes to a list of integers
                    return new ExifList($controlCharacters);
                }
            }

            return trim($value);
        }

        if (array_is_list($value)) {
            return new ExifList($value);
        }

        return new ExifMap($value);
    }
}
