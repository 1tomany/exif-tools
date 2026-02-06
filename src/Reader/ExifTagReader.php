<?php

namespace OneToMany\ExifTools\Reader;

use OneToMany\ExifTools\Contract\Reader\ExifReaderInterface;
use OneToMany\ExifTools\Contract\Reader\ExifTagReaderInterface;
use OneToMany\ExifTools\Exception\InvalidArgumentException;
use OneToMany\ExifTools\Record\ExifMap;
use OneToMany\ExifTools\Record\ExifValue;

use function is_file;
use function is_readable;
use function sprintf;

/**
 * @phpstan-import-type ExifValueList from ExifValue
 * @phpstan-import-type ExifValueMap from ExifValue
 */
class ExifTagReader implements ExifTagReaderInterface
{
    public function __construct(
        private readonly ExifReaderInterface $reader = new NativeExifReader(),
    ) {
    }

    /**
     * @see OneToMany\ExifTools\Contract\Reader\ExifTagReaderInterface
     */
    public function read(string $path): ExifMap
    {
        if (!is_file($path) || !is_readable($path)) {
            throw new InvalidArgumentException(sprintf('The file "%s" is not readable.', $path));
        }

        if (false === $this->reader->imageType($path)) {
            throw new InvalidArgumentException(sprintf('The file "%s" is not a valid image.', $path));
        }

        /**
         * @var false|array<non-empty-string, int|string|ExifValueList|ExifValueMap> $exifTags
         */
        $exifTags = $this->reader->readData($path);

        if (false === $exifTags) {
            throw new InvalidArgumentException(sprintf('Reading the EXIF data from the file "%s" failed.', $path));
        }

        return new ExifMap($exifTags);
    }
}
