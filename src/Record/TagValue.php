<?php

namespace OneToMany\ExifTools\Record;

use function ctype_digit;
use function is_array;
use function is_string;
use function ord;
use function str_contains;
use function strlen;

final readonly class TagValue
{
    /**
     * @var bool|int|float|string|list<scalar>|null
     */
    public bool|int|float|string|array|null $value;

    /**
     * @param bool|int|float|string|list<scalar>|null $value
     */
    public function __construct(bool|int|float|string|array|null $value)
    {
        $this->value = is_array($value) ? $value : $this->clean($value);
    }

    /**
     * @return bool|int|float|string|list<scalar>|null
     */
    public function getValue(): bool|int|float|string|array|null
    {
        return $this->value;
    }

    /**
     * @return bool|int|float|string|list<scalar>|null
     */
    private function clean(bool|int|float|string|null $value): bool|int|float|string|array|null
    {
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

            return trim($value) ?: null;
        }

        return $value;
    }
}
