<?php

require_once __DIR__.'/../vendor/autoload.php';

// Photo with GPS coordinates and altitude
$exifTags = new OneToMany\ExifTools\Reader\ExifTagReader()->read(__DIR__.'/utah-landscape.jpeg');

if ($name = $exifTags->get('FileName')) {
    printf("FileName: %s\n", (string) $name);
}

printf("CapturedAt: %s\n", $exifTags->capturedAt()?->format('Y-m-d H:i:s'));
printf("Latitude: %s\n", $exifTags->gps()->getLatitudeDecimal());
printf("Longitude: %s\n", $exifTags->gps()->getLongitudeDecimal());
printf("Altitude: %sm\n", $exifTags->gps()->getAltitudeDecimal());
printf("%s\n", str_repeat('-', 40));

// NUL bytes converted to list of integers
$exifTags = new OneToMany\ExifTools\Reader\ExifTagReader()->read(__DIR__.'/heater-label.jpeg');

if ($name = $exifTags->get('FileName')) {
    printf("FileName: %s\n", (string) $name);
}

// isList() = true ensures get() returns an ExifValue object
assert(true === $exifTags->get('ComponentsConfiguration')?->isList());

// ComponentsConfiguration is usally a list encoded as binary bytes
printf("ComponentsConfiguration: [%s]\n", implode(',', $exifTags->get('ComponentsConfiguration')->value()));
