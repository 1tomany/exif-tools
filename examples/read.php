<?php

require_once __DIR__.'/../vendor/autoload.php';

$separator = function (int $length = 60): void {
    printf("%s\n", str_repeat('-', $length));
};

$exifTagReader = new OneToMany\ExifTools\Reader\ExifTagReader();

$separator();

// Photo with ComponentsConfiguration tag containing NUL bytes
$exifTags = $exifTagReader->read(__DIR__.'/example-ComponentsConfiguration.jpeg');

if ($name = $exifTags->get('FileName')) {
    printf("FileName: %s\n", (string) $name);
}

if ($componentsConfiguration = $exifTags->get('ComponentsConfiguration')) {
    printf("ComponentsConfiguration: %s\n", (string) $componentsConfiguration);
}

$separator();

// Photo with creation timestamp, GPS coordinates, and altitude
$exifTags = $exifTagReader->read(__DIR__.'/example-GPSCoordinates.jpeg');

if ($name = $exifTags->get('FileName')) {
    printf("FileName: %s\n", (string) $name);
}

if (null !== $capturedAt = $exifTags->capturedAt()) {
    printf("CapturedAt: %s\n", $capturedAt->format('Y-m-d H:i:s'));
}

if (true === ($gps = $exifTags->gps())->isValid()) {
    printf("Latitude: %s\n", $gps->getLatitudeDecimal());
    printf("Longitude: %s\n", $gps->getLongitudeDecimal());
    printf("Altitude: %sm\n", $gps->getAltitudeDecimal());
}

$separator();

// Photo with SceneType tag containing a control character
$exifTags = $exifTagReader->read(__DIR__.'/example-SceneType.jpeg');

if ($name = $exifTags->get('FileName')) {
    printf("FileName: %s\n", (string) $name);
}

if ($sceneType = $exifTags->get('SceneType')) {
    printf("SceneType: %s\n", (string) $sceneType);
}

print_r($exifTags->toArray());

$separator();
