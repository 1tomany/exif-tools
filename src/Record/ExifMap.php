<?php

namespace OneToMany\ExifTools\Record;

use function array_combine;
use function array_key_exists;
use function array_keys;
use function array_map;
use function count;

/**
 * @implements \IteratorAggregate<non-empty-string, ExifValue>
 */
final readonly class ExifMap implements \Countable, \IteratorAggregate
{
    /**
     * @var array<non-empty-string, ExifValue>
     */
    public array $values;

    /**
     * @param array<non-empty-string, int|string|list<int|string>|array<non-empty-string, int|string>> $values
     */
    public function __construct(array $values)
    {
        $this->values = array_combine(array_keys($values), array_map(fn ($v) => new ExifValue($v), $values));
    }

    /**
     * @return array<non-empty-string, ExifValue>
     */
    public function all(): array
    {
        return $this->values;
    }

    public function has(string $tag): bool
    {
        return array_key_exists($tag, $this->values);
    }

    public function get(string $tag): ?ExifValue
    {
        return $this->values[$tag] ?? null;
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
     * @return \ArrayIterator<string, ExifValue>
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->values);
    }

    public function toArray(): array
    {
        return array_combine(array_keys($this->values), array_map(fn ($v) => $v->toPrimitive(), $this->values));
    }
}
