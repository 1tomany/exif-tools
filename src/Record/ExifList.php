<?php

namespace OneToMany\ExifTools\Record;

use function array_map;
use function count;
use function implode;
use function sprintf;

/**
 * @phpstan-import-type ExifValueList from ExifValue
 * @phpstan-import-type ExifValueMap from ExifValue
 *
 * @implements \IteratorAggregate<int, ExifValue>
 */
final readonly class ExifList implements \Countable, \IteratorAggregate, \Stringable
{
    /**
     * @var list<ExifValue>
     */
    private array $values;

    /**
     * @param ExifValueList $values
     */
    public function __construct(array $values)
    {
        $this->values = array_map(fn ($v) => new ExifValue($v), $values);
    }

    public function __toString(): string
    {
        return sprintf('List[%s]', implode(', ', array_map(fn ($v) => (string) $v, $this->values)));
    }

    /**
     * @return list<ExifValue>
     */
    public function all(): array
    {
        return $this->values;
    }

    /**
     * @phpstan-assert-if-true ExifValue $this->get()
     */
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
     * @return list<int|string|ExifValueList|ExifValueMap>
     */
    public function toArray(): array
    {
        return array_map(fn ($v) => $v->value(), $this->values);
    }
}
