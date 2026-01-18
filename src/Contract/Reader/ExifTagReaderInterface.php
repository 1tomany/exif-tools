<?php

namespace OneToMany\ExifTools\Contract\Reader;

use OneToMany\ExifTools\Record\ExifMap;

interface ExifTagReaderInterface
{
    /**
     * @param non-empty-string $path
     */
    public function read(string $path): ExifMap;
}
