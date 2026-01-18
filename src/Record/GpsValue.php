<?php

namespace OneToMany\ExifTools\Record;

use OneToMany\ExifTools\Exception\InvalidArgumentException;

final readonly class GpsValue
{
    public function __construct(
        public ?float $latitude = null,
        public ?float $longitude = null,
        public ?float $altitude = null,
        public ?float $direction = null,
    ) {
    }

    public static function create(
        ?ExifValue $latitude,
        ?ExifValue $latitudeRef,
        ?ExifValue $longitude,
        ?ExifValue $longitudeRef,
        // ?string $altitude = null,
        // ?string $altitudeRef = null,
        // ?string $speed = null,
        // ?string $speedRef = null,
        // ?string $direction = null,
    ): self {
        $latitudeDMS = null;

        if ($latitude && $latitudeRef) {
            if (!$latitude->isList()) {
                throw new InvalidArgumentException('Latitude must be a list.');
            }

            if (!$latitudeRef->isString()) {
                throw new InvalidArgumentException('LatitudeRef must be a string.');
            }

            list($degrees, $minutes, $seconds) = [
                $latitude->get()->get(0),
                $latitude->get()->get(1),
                $latitude->get()->get(2),
            ];

            if ($degrees && $minutes) {
                $latitudeDMS = $degrees->asDecimal() + ($minutes->asDecimal() / 60) + ((float) $seconds?->asDecimal() / 3600);
            }

            var_dump($latitudeDMS);
        }

        if ($longitude && !$longitude->isList()) {
            throw new InvalidArgumentException('Longitude must be a list.');
        }

        throw new \Exception('Not implemented!');
    }
}
