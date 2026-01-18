<?php

namespace OneToMany\ExifTools\Record;

use OneToMany\ExifTools\Contract\Record\ExifTagInterface;
use OneToMany\ExifTools\Contract\Record\ExifTagListInterface;

use function array_filter;
use function array_values;
use function count;

/**
 * @implements \IteratorAggregate<int, ExifTagInterface>
 */
class ExifTagList implements ExifTagListInterface, \Countable, \IteratorAggregate
{
    /**
     * @var list<ExifTagInterface>
     */
    private array $tags = [];

    /**
     * @param array<string, int|string|list<int|string>|array<string, int|string>> $tags
     */
    public function __construct(array $tags)
    {
        foreach ($tags as $tag => $value) {
            $this->tags[] = new ExifTag($tag, $value);
        }
    }

    /**
     * @see OneToMany\ExifTools\Contract\Record\ExifTagListInterface
     */
    public function all(): array
    {
        return $this->tags;
    }

    /**
     * @see OneToMany\ExifTools\Contract\Record\ExifTagListInterface
     */
    public function has(string $tag): bool
    {
        return null !== $this->get($tag);
    }

    /**
     * @see OneToMany\ExifTools\Contract\Record\ExifTagListInterface
     */
    public function get(string $tag): ?ExifTagInterface
    {
        return array_values(array_filter($this->tags, fn ($t) => $t->is($tag)))[0] ?? null;
    }

    /**
     * @see \IteratorAggregate
     *
     * @return \ArrayIterator<int, ExifTagInterface>
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->tags);
    }

    /**
     * @see \Countable
     */
    public function count(): int
    {
        return count($this->tags);
    }
}
