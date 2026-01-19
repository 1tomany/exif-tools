<?php

namespace OneToMany\ExifTools\Record;

use OneToMany\ExifTools\Exception\InvalidArgumentException;

use function bcdiv;
use function in_array;
use function number_format;
use function round;
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
            $altitude = $gpsAltitude->toFloat();

            // Photo was taken below sea level
            if (1 === $gpsAltitudeRef?->get()) {
                $altitude = -1 * $altitude;
            }
        }

        return new self($latitude, $longitude, $altitude);
    }

    public function getLatitudeDecimal(int $scale = 6): ?string
    {
        return $this->toDecimal($this->latitude, $scale);
    }

    public function getLongitudeDecimal(int $scale = 6): ?string
    {
        return $this->toDecimal($this->latitude, $scale);
    }

    public function getAltitudeDecimal(int $scale = 2): ?string
    {
        return $this->toDecimal($this->latitude, $scale);
    }

    private function toDecimal(?float $number, int $scale): ?string
    {
        if (null === $number) {
            return null;
        }

        return bcdiv(number_format(round((float) $number, $scale), $scale, '.', ''), '1', $scale);
    }

    /**
     * Converts rational list coordinates to a floating point degree, minute,
     * and second representation. For example, an ExifList with the values
     * ["32/1", "54/1", "3930/100"] would become approximately 32.910916666.
     *
     * @param string $direction the direction the photo was taken in (N, S, E, W)
     *
     * @return ?float null if the list does not have at least two elements or the direction is missing
     */
    private static function toDMS(ExifList $list, string $direction): ?float
    {
        // Ensure we have at least degrees and minutes
        list($deg, $min) = [$list->get(0), $list->get(1)];

        if (!$deg || !$min) {
            return null;
        }

        $direction = trim($direction);

        if (!$direction) {
            return null;
        }

        $degMinSec = $deg->toFloat() + ($min->toFloat() / 60) + ((float) $list->get(2)?->toFloat() / 3600);

        if (in_array(strtoupper($direction), ['W', 'S'])) {
            $degMinSec = -1.0 * $degMinSec;
        }

        return $degMinSec;
    }
}
