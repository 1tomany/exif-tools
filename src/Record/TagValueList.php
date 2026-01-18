<?php

namespace OneToMany\ExifTools\Record;

final readonly class TagValueList
{
    /**
     * @param list<TagValue>|array<non-empty-string, TagValue> $values
     */
    public function __construct(public array $values)
    {
    }

    /**
     * @return list<TagValue>|array<non-empty-string, TagValue>
     */
    public function getValues(): array
    {
        return $this->values;
    }
}
