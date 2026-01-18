<?php

namespace OneToMany\ExifTools\Contract\Record;

interface ExifTagInterface
{
    /**
     * @return non-empty-string
     */
    public function getTag(): string;

    /**
     * @param non-empty-string $tag
     */
    public function is(string $tag): bool;

    /**
     * @return int|string|list<int|string>|ExifTagListInterface
     */
    public function getValue(): int|string|array|ExifTagListInterface;
}
