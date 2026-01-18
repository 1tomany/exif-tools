<?php

namespace OneToMany\ExifTools\Record;

use OneToMany\ExifTools\Exception\InvalidArgumentException;

final readonly class GpsValue
{
    public function __construct(
        public ?float $latitude = null,
        public ?float $longitude = null,
        public ?float $altitude = null,
        // public ?float $direction = null,
    ) {
    }

    public static function create(
        ?ExifValue $gpsLatitude,
        ?ExifValue $gpsLatitudeRef,
        ?ExifValue $gpsLongitude,
        ?ExifValue $gpsLongitudeRef,
        ?ExifValue $gpsAltitude = null,
        ?ExifValue $gpsAltitudeRef = null,
        // ?string $speed = null,
        // ?string $speedRef = null,
        // ?string $direction = null,
    ): self {
        $latitudeDMS = $longitudeDMS = $altitudeMeters = null;

        if ($gpsLatitude && $gpsLatitudeRef) {
            if (!$gpsLatitude->isList()) {
                throw new InvalidArgumentException('Latitude must be a list.');
            }

            if (!$gpsLatitudeRef->isString()) {
                throw new InvalidArgumentException('LatitudeRef must be a string.');
            }

            $latitudeDMS = self::toDMS($gpsLatitude->get(), $gpsLatitudeRef->get());
        }

        if ($gpsLongitude && $gpsLongitudeRef) {
            if (!$gpsLongitude->isList()) {
                throw new InvalidArgumentException('Longitude must be a list.');
            }

            if (!$gpsLongitudeRef->isString()) {
                throw new InvalidArgumentException('LongitudeRef must be a string.');
            }

            $longitudeDMS = self::toDMS($gpsLongitude->get(), $gpsLongitudeRef->get());
        }

        if ($gpsAltitude && $gpsAltitude->isString()) {
            $altitudeMeters = $gpsAltitude->asDecimal();

            if (1 === $gpsAltitudeRef?->get()) {
                $altitudeMeters = -1 * $altitudeMeters; // Below sea level
            }
        }

        return new self($latitudeDMS, $longitudeDMS, $altitudeMeters);
    }

    private static function toDMS(ExifList $list, string $ref): ?float
    {
        list($deg, $min) = [$list->get(0), $list->get(1)];

        if (!$deg || !$min) {
            return null;
        }

        $dms = $deg->asDecimal() + ($min->asDecimal() / 60) + ((float) $list->get(2)?->asDecimal() / 3600);

        if (\in_array($ref, ['W', 'S'])) {
            $dms = -1.0 * $dms;
        }

        return $dms;
    }
}
