<?php

namespace OneToMany\ExifTools\Contract\Record;

interface ExifListInterface
{
    /**
     * @return list<ExifValueInterface>
     */
    public function all(): array;

    public function has(int $index): bool;

    public function get(int $index): ?ExifValueInterface;
}
