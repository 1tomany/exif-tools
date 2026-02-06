<?php

namespace OneToMany\ExifTools\Reader;

use OneToMany\ExifTools\Contract\Reader\ExifReaderInterface;

use function exif_imagetype;
use function exif_read_data;

final class NativeExifReader implements ExifReaderInterface
{
    public function imageType(string $path): bool
    {
        return false !== @exif_imagetype($path);
    }

    public function readData(string $path): false|array
    {
        /** @var false|array<non-empty-string, mixed> $data */
        $data = @exif_read_data($path, null, false, false);

        return $data;
    }
}
