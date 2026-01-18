<?php

namespace OneToMany\ExifTools\Record;

use function array_map;
use function count;

/**
 * @implements \IteratorAggregate<int, ExifValue>
 */
final readonly class ExifList implements \Countable, \IteratorAggregate
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

    /**
     * @see \Countable
     */
    public function count(): int
    {
        return count($this->values);
    }

    /**
     * @see \IteratorAggregate
     *
     * @return \ArrayIterator<int, ExifValue>
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->values);
    }

    /**
     * @return list<int|string|list<int|string>|array<non-empty-string, int|string>>
     */
    public function toArray(): array
    {
        return array_map(fn ($v) => $v->get(), $this->values);
    }
}
