<?php

namespace OneToMany\ExifTools\Tests\Record;

use OneToMany\ExifTools\Exception\InvalidArgumentException;
use OneToMany\ExifTools\Record\GpsValue;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

use function random_int;
use function sprintf;

#[Group('UnitTests')]
#[Group('RecordTests')]
final class GpsValueTest extends TestCase
{
    public function testConstructorRequiresValidLatitude(): void
    {
        // Latitude less than -90 or greater than +90
        $latitude = (0 === random_int(0, 1) ? -1 : 1) * random_int(91, 180);
        $this->assertTrue($latitude < -90 || $latitude > 90, sprintf('Latitude = %d', $latitude));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The latitude "'.GpsValue::toDecimal($latitude, 6).'" must be between -90 and +90.');

        new GpsValue($latitude, null, null);
    }

    public function testConstructorRequiresValidLongitude(): void
    {
        // Longitude less than -180 or greater than +180
        $longitude = (0 === random_int(0, 1) ? -1 : 1) * random_int(181, 360);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The longitude "'.GpsValue::toDecimal($longitude, 6).'" must be between -180 and +180.');

        new GpsValue(null, $longitude, null);
    }

    public function testConstructorRequiresValidAltitude(): void
    {
        // Altitude lower than the depth of the Mariana Trench
        $altitude = random_int(2 * GpsValue::MARIANA_TRENCH_DEPTH, GpsValue::MARIANA_TRENCH_DEPTH - 1);
        $this->assertTrue($altitude < GpsValue::MARIANA_TRENCH_DEPTH, sprintf('Altitude = %d', $altitude));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The altitude "'.GpsValue::toDecimal($altitude, 2).'" must be greater than or equal to '.GpsValue::MARIANA_TRENCH_DEPTH.'.');

        new GpsValue(null, null, $altitude);
    }

    public function testIsNotValidWithNullLatitude(): void
    {
        $gps = new GpsValue(null, random_int(-180, 180));

        $this->assertNull($gps->latitude);
        $this->assertFalse($gps->isValid());
    }

    public function testIsNotValidWithNullLongitude(): void
    {
        $gps = new GpsValue(random_int(-90, 90), null);

        $this->assertNull($gps->longitude);
        $this->assertFalse($gps->isValid());
    }
}
