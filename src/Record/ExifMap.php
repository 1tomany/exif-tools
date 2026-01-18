<?php

namespace OneToMany\ExifTools\Record;

use OneToMany\ExifTools\Contract\Record\ExifMapInterface;
use OneToMany\ExifTools\Contract\Record\ExifValueInterface;

use function array_key_exists;
use function count;

final readonly class ExifMap implements \Countable, ExifMapInterface
{
    /**
     * @param array<non-empty-string, ExifValueInterface> $values
     */
    public function __construct(public array $values)
    {
    }

    /**
     * @param array<non-empty-string, int|string|list<int|string>|array<non-empty-string, int|string>> $values
     */
    public static function create(array $values): self
    {
        $exifMap = [];

        foreach ($values as $key => $value) {
            $exifMap[$key] = new ExifValue($value);
        }

        return new self($exifMap);
    }

    public function all(): array
    {
        return $this->values;
    }

    public function has(string $tag): bool
    {
        return array_key_exists($tag, $this->values);
    }

    public function get(string $tag): ?ExifValueInterface
    {
        return $this->values[$tag] ?? null;
    }

    public function count(): int
    {
        return count($this->values);
    }
}
