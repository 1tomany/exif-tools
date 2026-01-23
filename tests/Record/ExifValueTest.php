<?php

namespace OneToMany\ExifTools\Tests\Record;

use OneToMany\ExifTools\Record\ExifValue;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

use function time;

use const PHP_INT_MAX;
use const PHP_INT_MIN;

#[Group('UnitTests')]
#[Group('RecordTests')]
final class ExifValueTest extends TestCase
{
    public function testToTimestampRequiresIntegerOrString(): void
    {
        $exifValue = new ExifValue([1, 2, 3]);

        $this->assertTrue($exifValue->isList());
        $this->assertNull($exifValue->toTimestamp());
    }

    #[DataProvider('providerIntegerTimestamp')]
    public function testToTimestampWithInteger(int $timestamp): void
    {
        $this->assertInstanceOf(\DateTimeImmutable::class, new ExifValue($timestamp)->toTimestamp());
    }

    /**
     * @return list<list<int>>
     */
    public static function providerIntegerTimestamp(): array
    {
        $provider = [
            [PHP_INT_MIN],
            [-10],
            [-1],
            [0],
            [1],
            [10],
            [100],
            [time()],
            [PHP_INT_MAX],
        ];

        return $provider;
    }

    public function testToTimestampWithStringRequiresValidFormat(): void
    {
        $this->assertNull(new ExifValue('invalid timestamp')->toTimestamp());
    }

    /**
     * @param non-empty-string $timestamp
     */
    #[DataProvider('providerStringTimestamp')]
    public function testToTimestampWithString(string $timestamp): void
    {
        $this->assertInstanceOf(\DateTimeImmutable::class, new ExifValue($timestamp)->toTimestamp());
    }

    /**
     * @return list<list<non-empty-string>>
     */
    public static function providerStringTimestamp(): array
    {
        return [
            ['0000:00:00'],
            ['0000:00:01'],
            ['1111:11:11'],
            ['0000:00:00 00:00:00'],
            ['0000:00:01 00:00:00'],
            ['0532:01:01 12:49:52'],
            ['1111:11:11 11:11:11'],
            ['1984:08:25 13:23:00'],
            ['1999:12:31 23:59:59'],
            ['2000:01:01 00:00:00'],
            ['2148:03:26 07:54:01'],
        ];
    }
}
