<?php

namespace OneToMany\ExifTools\Contract\Reader;

use OneToMany\ExifTools\Contract\Record\ExifTagListInterface;

interface ExifTagReaderInterface
{
    /**
     * @param non-empty-string $path
     */
    public function read(string $path): ExifTagListInterface;
}
