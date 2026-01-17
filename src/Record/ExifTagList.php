<?php

namespace OneToMany\ExifTools\Record;

use OneToMany\ExifTools\Contract\Record\ExifTagInterface;
use OneToMany\ExifTools\Contract\Record\ExifTagListInterface;

use function count;

/**
 * @implements \IteratorAggregate<int, ExifTagInterface>
 */
readonly class ExifTagList implements ExifTagListInterface, \Countable, \IteratorAggregate
{
    /**
     * @param list<ExifTagInterface> $tags
     */
    public function __construct(public array $tags)
    {
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
