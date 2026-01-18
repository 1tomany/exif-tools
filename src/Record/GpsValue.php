<?php

namespace OneToMany\ExifTools\Record;

use OneToMany\ExifTools\Exception\InvalidArgumentException;

use function in_array;
use function strtoupper;
use function trim;

final readonly class GpsValue
{
    public function __construct(
        public ?float $latitude = null,
        public ?float $longitude = null,
        public ?float $altitude = null,
    ) {
    }

    public static function create(
        ?ExifValue $gpsLatitude,
        ?ExifValue $gpsLatitudeRef,
        ?ExifValue $gpsLongitude,
        ?ExifValue $gpsLongitudeRef,
        ?ExifValue $gpsAltitude = null,
        ?ExifValue $gpsAltitudeRef = null,
    ): self {
        $latitude = $longitude = $altitude = null;

        if ($gpsLatitude && $gpsLatitudeRef) {
            if (!$gpsLatitude->isList()) {
                throw new InvalidArgumentException('GPSLatitude must be a list.');
            }

            if (!$gpsLatitudeRef->isString()) {
                throw new InvalidArgumentException('GPSLatitudeRef must be a string.');
            }

            $latitude = self::toDMS($gpsLatitude->get(), $gpsLatitudeRef->get());
        }

        if ($gpsLongitude && $gpsLongitudeRef) {
            if (!$gpsLongitude->isList()) {
                throw new InvalidArgumentException('GPSLongitude must be a list.');
            }

            if (!$gpsLongitudeRef->isString()) {
                throw new InvalidArgumentException('GPSLongitudeRef must be a string.');
            }

            $longitude = self::toDMS($gpsLongitude->get(), $gpsLongitudeRef->get());
        }

        if ($gpsAltitude && $gpsAltitude->isString()) {
            $altitude = $gpsAltitude->asDecimal();

            // Photo was taken below sea level
            if (1 === $gpsAltitudeRef?->get()) {
                $altitude = -1 * $altitude;
            }
        }

        return new self($latitude, $longitude, $altitude);
    }

    private static function toDMS(ExifList $list, string $ref): ?float
    {
        list($deg, $min) = [$list->get(0), $list->get(1)];

        if (!$deg || !$min) {
            return null;
        }

        if (!$ref = trim($ref)) {
            return null;
        }

        $degMinSec = $deg->asDecimal() + ($min->asDecimal() / 60) + ((float) $list->get(2)?->asDecimal() / 3600);

        if (in_array(strtoupper($ref), ['W', 'S'])) {
            $degMinSec = -1.0 * $degMinSec;
        }

        return $degMinSec;
    }
}
