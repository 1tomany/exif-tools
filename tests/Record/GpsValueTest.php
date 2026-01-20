<?php

namespace OneToMany\ExifTools\Tests\Record;

use OneToMany\ExifTools\Exception\InvalidArgumentException;
use OneToMany\ExifTools\Record\GpsValue;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

use function random_int;

#[Group('UnitTests')]
#[Group('RecordTests')]
final class GpsValueTest extends TestCase
{
    public function testConstructorRequiresValidLatitude(): void
    {
        // Random latitude less than -90 or greater than +90
        $latitude = (0 === random_int(0, 1) ? -1 : 1) * (random_int(90, 360) + (random_int(1, 100) / random_int(1, 100)));
        $this->assertTrue($latitude < -90 || $latitude > 90);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The latitude "'.GpsValue::toDecimal($latitude, 6).'" must be between -90 and +90.');

        new GpsValue($latitude, null, null);
    }

    public function testConstructorRequiresValidLongitude(): void
    {
        // Random longitude less than -180 or greater than +180
        $longitude = (0 === random_int(0, 1) ? -1 : 1) * (random_int(180, 360) + (random_int(1, 100) / random_int(1, 100)));
        $this->assertTrue($longitude < -180 || $longitude > 180);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The longitude "'.GpsValue::toDecimal($longitude, 6).'" must be between -180 and +180.');

        new GpsValue(null, $longitude, null);
    }
}
