<?php

namespace OneToMany\ExifTools\Contract\Record;

interface TagValueInterface
{
    /**
     * @return bool|int|float|string|list<int|string>|array<non-empty-string, int|string>|null
     */
    public function getValue(): bool|int|float|string|array|null;
}
