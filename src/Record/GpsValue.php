<?php

namespace OneToMany\ExifTools\Record;

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
        ?ExifList $latitude,
        ?string $latitudeRef,
        ?ExifList $longitude,
        ?string $longitudeRef,
        ?string $altitude = null,
        ?string $altitudeRef = null,
        ?string $speed = null,
        ?string $speedRef = null,
        // ?string $direction = null,
    ): self {
        if ($latitude && $latitude->count() >= 2 && $latitudeRef) {
        }

        throw new \Exception('Not implemented!');
    }
}
