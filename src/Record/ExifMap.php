<?php

namespace OneToMany\ExifTools\Record;

use function array_combine;
use function array_key_exists;
use function array_keys;
use function array_map;
use function count;

/**
 * @phpstan-import-type ExifValueList from ExifValue
 * @phpstan-import-type ExifValueMap from ExifValue
 *
 * @implements \IteratorAggregate<int|string, ExifValue>
 */
final readonly class ExifMap implements \Countable, \IteratorAggregate
{
    /**
     * @var array<int|string, ExifValue>
     */
    private array $values;

    /**
     * @param array<int|string, int|string|ExifValueList|ExifValueMap> $values
     */
    public function __construct(array $values)
    {
        $this->values = array_combine(array_keys($values), array_map(fn ($v) => new ExifValue($v), $values));
    }

    /**
     * @return array<int|string, ExifValue>
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
     * @return \ArrayIterator<int|string, ExifValue>
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->values);
    }

    /**
     * @return array<int|string, int|string|ExifList|ExifMap>
     */
    public function toArray(): array
    {
        return array_combine(array_keys($this->values), array_map(fn ($v) => $v->value(), $this->values));
    }
}
