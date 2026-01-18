<?php

namespace OneToMany\ExifTools\Record;

use OneToMany\ExifTools\Contract\Record\ExifMapInterface;
use OneToMany\ExifTools\Contract\Record\ExifValueInterface;

use function count;

/**
 * @implements \IteratorAggregate<non-empty-string, ExifValueInterface>
 */
final readonly class ExifMap implements \Countable, \IteratorAggregate, ExifMapInterface
{
    /**
     * @param array<non-empty-string, ExifValueInterface> $values
     */
    public function __construct(public array $values)
    {
    }

    /**
     * @param array<non-empty-string, int|string> $values
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
        throw new \Exception('Not implemented');
    }

    public function get(string $tag): ?ExifValueInterface
    {
        throw new \Exception('Not implemented');
    }

    /**
     * @return \ArrayIterator<non-empty-string, ExifValueInterface>
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->values); // @phpstan-ignore-line
    }

    public function count(): int
    {
        return count($this->values);
    }
}
