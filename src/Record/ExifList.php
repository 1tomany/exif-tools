<?php

namespace OneToMany\ExifTools\Record;

use OneToMany\ExifTools\Contract\Record\ExifListInterface;
use OneToMany\ExifTools\Contract\Record\ExifValueInterface;

use function array_map;
use function count;

/**
 * @template-covariant ExifValueInterface
 * @implements \IteratorAggregate<int, ExifValueInterface>
 */
final readonly class ExifList implements \Countable, \IteratorAggregate, ExifListInterface
{
    /**
     * @var list<ExifValueInterface>
     */
    public array $values;

    /**
     * @param list<int|string> $values
     */
    public function __construct(array $values)
    {
        $this->values = array_map(fn ($v) => new ExifValue($v), $values);
    }

    public function all(): array
    {
        return $this->values;
    }

    public function has(int $index): bool
    {
        return isset($this->values[$index]);
    }

    public function get(int $index): ?ExifValueInterface
    {
        return $this->values[$index] ?? null;
    }

    /**
     * @return \ArrayIterator<int, ExifValueInterface>
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->values);
    }

    public function count(): int
    {
        return count($this->values);
    }
}
