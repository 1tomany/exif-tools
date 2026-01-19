<?php

namespace OneToMany\ExifTools\Contract\Reader;

use OneToMany\ExifTools\Exception\InvalidArgumentException;
use OneToMany\ExifTools\Record\ExifMap;

interface ExifTagReaderInterface
{
    /**
     * @param non-empty-string $path
     *
     * @throws InvalidArgumentException
     */
    public function read(string $path): ExifMap;
}
