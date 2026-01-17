<?php

namespace OneToMany\ExifTools\Record;

use OneToMany\ExifTools\Contract\Record\ExifTagInterface;
use OneToMany\ExifTools\Contract\Record\ExifTagListInterface;

use function array_filter;
use function count;

/**
 * @implements \IteratorAggregate<int, ExifTagInterface>
 */
readonly class ExifTagList implements ExifTagListInterface, \Countable, \IteratorAggregate
{
    /**
     * @param list<ExifTagInterface> $tags
     */
    public function __construct(protected array $tags)
    {
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
        return 0 !== count(array_filter($this->tags, fn ($t) => $t->isTag($tag)));
    }

    /**
     * @see OneToMany\ExifTools\Contract\Record\ExifTagListInterface
     */
    public function get(string $tag): ?ExifTagInterface
    {
        throw new \Exception('Not implemented');
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
