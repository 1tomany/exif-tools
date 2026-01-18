<?php

namespace OneToMany\ExifTools\Contract\Reader;

use OneToMany\ExifTools\Contract\Record\ExifListInterface;

interface ExifTagReaderInterface
{
    /**
     * @param non-empty-string $path
     */
    public function read(string $path): ExifListInterface;
}
