<?php

namespace OneToMany\ExifTools\Tests\Reader;

use OneToMany\ExifTools\Exception\InvalidArgumentException;
use OneToMany\ExifTools\Reader\ExifTagReader;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
#[Group('ReaderTests')]
final class ExifTagReaderTest extends TestCase
{
    public function testReadingFileRequiresReadableFile(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The file "invalid_photo.jpeg" is not readable.');

        new ExifTagReader()->read('invalid_photo.jpeg');
    }

    public function testReadingFileRequiresValidImage(): void
    {
        $path = __FILE__;
        $this->assertFileExists($path);
        $this->assertFileIsReadable($path);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The file "'.$path.'" is not a valid image.');

        new ExifTagReader()->read($path);
    }

    public function testReadingFileRequiresImageWithExifData(): void
    {
        $path = __DIR__.'/../data/extract.dev-sticker.png';
        $this->assertFileExists($path);
        $this->assertFileIsReadable($path);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Reading the EXIF data from the file "'.$path.'" failed.');

        new ExifTagReader()->read($path);
    }
}
