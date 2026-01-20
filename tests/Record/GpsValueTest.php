<?php

namespace OneToMany\ExifTools\Tests\Record;

use OneToMany\ExifTools\Exception\InvalidArgumentException;
use OneToMany\ExifTools\Record\GpsValue;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

use function random_int;

use const PHP_INT_MAX;

#[Group('UnitTests')]
#[Group('RecordTests')]
final class GpsValueTest extends TestCase
{
    public function testConstructorRequiresValidLatitude(): void
    {
        $signFlipper = 0 === random_int(0, 1) ? -1 : 1;

        // Latitude less than -90 or greater than +90
        $latitude = ($signFlipper * random_int(90, 180));
        $this->assertTrue($latitude < -90 || $latitude > 90);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The latitude "'.GpsValue::toDecimal($latitude, 6).'" must be between -90 and +90.');

        new GpsValue($latitude, null, null);
    }

    public function testConstructorRequiresValidLongitude(): void
    {
        $signFlipper = 0 === random_int(0, 1) ? -1 : 1;

        // Longitude less than -180 or greater than +180
        $longitude = $signFlipper * random_int(180, 360);
        $this->assertTrue($longitude < -180 || $longitude > 180);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The longitude "'.GpsValue::toDecimal($longitude, 6).'" must be between -180 and +180.');

        new GpsValue(null, $longitude, null);
    }

    public function testConstructorRequiresValidAltitude(): void
    {
        // Altitude lower than the depth of the Mariana Trench
        $altitude = -1 * random_int(GpsValue::MARIANA_TRENCH_DEPTH + 1, GpsValue::MARIANA_TRENCH_DEPTH + 1_000_000);
        $this->assertTrue($altitude < GpsValue::MARIANA_TRENCH_DEPTH);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The altitude "'.GpsValue::toDecimal($altitude, 2).'" must be greater than '.GpsValue::MARIANA_TRENCH_DEPTH.'.');

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
