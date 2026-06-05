<?php

namespace OneToMany\ExifTools\Contract\Reader;

use OneToMany\ExifTools\Record\ExifMap;

interface ExifTagReaderInterface
{
    /**
     * Reads EXIF data from an image and converts it to a map for easy access.
     *
     * @param non-empty-string $path
     */
    public function read(string $path): ExifMap;

    /**
     * Reads EXIF data from an image without throwing any exceptions.
     *
     * @param non-empty-string $path
     */
    public function readSafely(string $path): ExifMap;
}
