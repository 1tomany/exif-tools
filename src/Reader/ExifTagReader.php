<?php

namespace OneToMany\ExifTools\Reader;

use OneToMany\ExifTools\Contract\Reader\ExifTagReaderInterface;
use OneToMany\ExifTools\Contract\Record\ExifTagListInterface;
use OneToMany\ExifTools\Exception\InvalidArgumentException as ExceptionInvalidArgumentException;

use function exif_imagetype;
use function exif_read_data;
use function is_file;
use function is_readable;
use function sprintf;

class ExifTagReader implements ExifTagReaderInterface
{
    public function __construct()
    {
    }

    /**
     * @see OneToMany\ExifTools\Contract\Reader\ExifTagReaderInterface
     */
    public function read(string $path): ExifTagListInterface
    {
        if (!is_file($path) || !is_readable($path)) {
            throw new \InvalidArgumentException(sprintf('The file "%s" is not readable.', $path));
        }

        if (false === @exif_imagetype($path)) {
            throw new \InvalidArgumentException(sprintf('The file "%s" is not a valid image.', $path));
        }

        $tags = @exif_read_data($path, null, false, false);

        if (false === $tags) {
            throw new ExceptionInvalidArgumentException(sprintf('Failed to read EXIF tags from the file "%s".', $path));
        }
    }
}
