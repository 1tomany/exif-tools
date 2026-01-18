<?php

namespace OneToMany\ExifTools\Record;

use OneToMany\ExifTools\Contract\Record\ExifMapInterface;
use OneToMany\ExifTools\Contract\Record\ExifValueInterface;

use function array_combine;
use function array_key_exists;
use function array_keys;
use function array_map;
use function count;

final readonly class ExifMap implements \Countable, ExifMapInterface
{
    /**
     * @var array<non-empty-string, ExifValueInterface>
     */
    public array $values;

    /**
     * @param array<non-empty-string, int|string|list<int|string>|array<non-empty-string, int|string>> $values
     */
    public function __construct(array $values)
    {
        $this->values = array_combine(array_keys($values), array_map(fn ($v) => new ExifValue($v), $values));
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
