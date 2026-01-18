<?php

namespace OneToMany\ExifTools\Reader;

use OneToMany\ExifTools\Contract\Reader\ExifTagReaderInterface;
use OneToMany\ExifTools\Contract\Record\ExifListInterface;
use OneToMany\ExifTools\Exception\InvalidArgumentException;

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
    public function read(string $path): ExifListInterface
    {
        if (!is_file($path) || !is_readable($path)) {
            throw new InvalidArgumentException(sprintf('The file "%s" is not readable.', $path));
        }

        if (false === @exif_imagetype($path)) {
            throw new InvalidArgumentException(sprintf('The file "%s" is not a valid image.', $path));
        }

        /**
         * @var false|array<string, int|string|list<int|string>|array<string, int|string>> $exifTags
         */
        $exifTags = @exif_read_data($path, null, false, false);

        if (false === $exifTags) {
            throw new InvalidArgumentException(sprintf('Reading the EXIF data from the file "%s" failed.', $path));
        }

        throw new \RuntimeException('Not implemented!');
    }
}
