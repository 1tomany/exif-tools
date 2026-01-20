<?php

namespace OneToMany\ExifTools\Tests\Reader;

use OneToMany\ExifTools\Exception\InvalidArgumentException;
use OneToMany\ExifTools\Reader\ExifTagReader;
use PHPUnit\Framework\TestCase;

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
}
