<?php

require_once __DIR__.'/../vendor/autoload.php';

$exifTagReader = new OneToMany\ExifTools\Reader\ExifTagReader();

// Photo with ComponentsConfiguration tag containing NUL bytes
$exifTags = $exifTagReader->read(__DIR__.'/example-ComponentsConfiguration.jpeg');

if ($name = $exifTags->get('FileName')) {
    printf("FileName: %s\n", $name->value());
}

if ($componentsConfiguration = $exifTags->get('ComponentsConfiguration')) {
    printf("ComponentsConfiguration: %s\n", (string) $componentsConfiguration);
}

printf("%s\n", str_repeat('-', 40));

// Photo with creation timestamp, GPS coordinates, and altitude
$exifTags = $exifTagReader->read(__DIR__.'/example-GPSCoordinates.jpeg');

if ($name = $exifTags->get('FileName')) {
    printf("FileName: %s\n", $name->value());
}

if (null !== $capturedAt = $exifTags->capturedAt()) {
    printf("CapturedAt: %s\n", $capturedAt->format('Y-m-d H:i:s'));
}

if (true === ($gps = $exifTags->gps())->isValid()) {
    printf("Latitude: %s\n", $gps->getLatitudeDecimal());
    printf("Longitude: %s\n", $gps->getLongitudeDecimal());
    printf("Altitude: %sm\n", $gps->getAltitudeDecimal());
}

printf("%s\n", str_repeat('-', 40));

// Photo with SceneType tag containing a control character
$exifTags = $exifTagReader->read(__DIR__.'/example-SceneType.jpeg');

if ($name = $exifTags->get('FileName')) {
    printf("FileName: %s\n", $name->value());
}

if ($sceneType = $exifTags->get('SceneType')) {
    printf("SceneType: %s\n", $sceneType->value());
}

printf("%s\n", str_repeat('-', 40));
