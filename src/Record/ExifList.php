<?php

namespace OneToMany\ExifTools\Record;

use OneToMany\ExifTools\Contract\Record\ExifListInterface;
use OneToMany\ExifTools\Contract\Record\ExifValueInterface;

use function array_map;
use function count;

/**
 * @implements \IteratorAggregate<int, ExifValueInterface>
 */
final readonly class ExifList implements \Countable, \IteratorAggregate, ExifListInterface
{
    /**
     * @param list<ExifValueInterface> $values
     */
    public function __construct(public array $values)
    {
        // $exifValues = [];

        // foreach ($values as $value) {
        //     $exifValues[] = new ExifValue($value);
        // }

        // $this->values = $exifValues;
        // $this->values = array_map(fn ($v) => new ExifValue($v), $values);
    }

    /**
     * @param list<int|string> $values
     */
    public static function create(array $values): self
    {
        return new self(array_map(fn ($v) => new ExifValue($v), $values));
    }

    public function all(): array
    {
        return $this->values;
    }

    public function has(string $tag): bool
    {
        throw new \Exception('Not implemented');
    }

    public function get(string $tag): ?ExifValueInterface
    {
        throw new \Exception('Not implemented');
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
