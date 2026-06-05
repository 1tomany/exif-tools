<?php

namespace OneToMany\ExifTools\Tests\Record;

use OneToMany\ExifTools\Exception\LogicException;
use OneToMany\ExifTools\Record\ExifValue;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

use function time;

use const PHP_INT_MAX;
use const PHP_INT_MIN;

/**
 * @phpstan-import-type ExifValueList from ExifValue
 * @phpstan-import-type ExifValueMap from ExifValue
 */
#[Group('UnitTests')]
#[Group('RecordTests')]
final class ExifValueTest extends TestCase
{
    #[DataProvider('providerFloatValue')]
    public function testIsNotInt(float|string $value): void
    {
        $this->assertFalse(new ExifValue($value)->isInt());
    }

    #[DataProvider('providerIntValue')]
    public function testIsInt(int $value): void
    {
        $this->assertTrue(new ExifValue($value)->isInt());
    }

    /**
     * @return non-empty-list<non-empty-list<int>>
     */
    public static function providerIntValue(): array
    {
        $provider = [
            [\PHP_INT_MIN],
            [\PHP_INT_MAX],
            [\random_int(\PHP_INT_MIN, \PHP_INT_MAX)],
        ];

        return $provider;
    }

    /**
     * @return non-empty-list<non-empty-list<float>>
     */
    public static function providerFloatValue(): array
    {
        $provider = [
            [\M_PI],
            [\PHP_FLOAT_MIN],
            [\PHP_FLOAT_MAX],
            [\PHP_FLOAT_EPSILON],
        ];

        return $provider;
    }

    public function testToFloatRequiresScalar(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Non-scalar values cannot be converted to floats.');

        (new ExifValue([1, 2, 3]))->toFloat();
    }

    #[DataProvider('providerToFloatValues')]
    public function testToFloatReturnsExpected(
        int|float|string $value,
        ?float $expected,
    ): void
    {
        $this->assertSame($expected, (new ExifValue($value))->toFloat());
    }

    /**
     * @return list<array{0: int|float|string, 1: ?float}>
     */
    public static function providerToFloatValues(): array
    {
        return [
            [0, 0.0],
            [1, 1.0],
            ['1', 1.0],
            ['1.25', 1.25],
            ['0010', 10.0],
            ['10/2', 5.0],
            ['3930/100', 39.3],
            ['1/0', null],
            ['0/0', null],
            ['', null],
            ['not-a-number', null],
            [39.3, 39.3],
            [-179.5, -179.5],
        ];
    }

    public function testFloatValueIsStoredNatively(): void
    {
        $exifValue = new ExifValue(39.3);

        $this->assertTrue($exifValue->isFloat());
        $this->assertSame(39.3, $exifValue->value());
    }

    public function testFloatValuePreservesPrecision(): void
    {
        $exifValue = new ExifValue(-179.89999389648438);

        $this->assertSame(-179.89999389648438, $exifValue->toFloat());
    }

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
