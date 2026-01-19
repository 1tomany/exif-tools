<?php

require_once __DIR__.'/../vendor/autoload.php';

// Photo with GPS coordinates
$photoPath = __DIR__.'/utah-landscape.jpeg';
$gps = new OneToMany\ExifTools\Reader\ExifTagReader()->read($photoPath)->gps();

printf("Photo: %s\n", basename($photoPath));
printf("Latitude: %s\n", $gps->getLatitudeDecimal());
printf("Longitude: %s\n", $gps->getLongitudeDecimal());
printf("Altitude: %sm\n", $gps->getAltitudeDecimal());
printf("%s\n", str_repeat('-', 40));

// Photo with NUL bytes converted to list of integers
$photoPath = __DIR__.'/heater-label.jpeg';
$exifTags = new OneToMany\ExifTools\Reader\ExifTagReader()->read($photoPath);

assert(true === $exifTags->get('ComponentsConfiguration')?->isList());

printf("Photo: %s\n", basename($photoPath));
printf("ComponentsConfiguration: [%s]\n", implode(',', $exifTags->get('ComponentsConfiguration')->value()));
