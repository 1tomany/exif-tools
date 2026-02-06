<?php

namespace OneToMany\ExifTools\Contract\Reader;

interface ExifReaderInterface
{
    /**
     * @param non-empty-string $path
     */
    public function imageType(string $path): bool;

    /**
     * @param non-empty-string $path
     *
     * @return false|array<non-empty-string, mixed>
     */
    public function readData(string $path): false|array;
}
