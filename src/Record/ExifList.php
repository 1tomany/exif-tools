<?php

namespace OneToMany\ExifTools\Record;

use function array_map;
use function count;

final readonly class ExifList implements \Countable
{
    /**
     * @var list<ExifValue>
     */
    public array $values;

    /**
     * @param list<int|string> $values
     */
    public function __construct(array $values)
    {
        $this->values = array_map(fn ($v) => new ExifValue($v), $values);
    }

    /**
     * @return list<ExifValue>
     */
    public function all(): array
    {
        return $this->values;
    }

    public function has(int $index): bool
    {
        return isset($this->values[$index]);
    }

    public function get(int $index): ?ExifValue
    {
        return $this->values[$index] ?? null;
    }

    public function count(): int
    {
        return count($this->values);
    }
}
