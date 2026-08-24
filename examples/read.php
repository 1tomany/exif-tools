#!/usr/bin/env php
<?php

require_once __DIR__.'/../vendor/autoload.php';

use OneToMany\ExifTools\Reader\ExifTagReader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\SingleCommandApplication;
use Symfony\Component\Console\Style\SymfonyStyle;

$command = function (SymfonyStyle $io): int {
    $io->title('exif-tools Examples');

    $exifTagReader = new ExifTagReader();

    // Photo with ComponentsConfiguration tag containing multiple control characters
    $exifTags = $exifTagReader->read($file = __DIR__.'/../config/files/ComponentsConfiguration.jpeg');

    $tableData = [];

    foreach (['ComponentsConfiguration'] as $tag) {
        $tableData[$tag] = $exifTags->get($tag);
    }

    $io->section(basename($file));

    $io->table(array_keys($tableData), [
        array_values($tableData),
    ]);

    // Photo with creation timestamp, GPS coordinates, and altitude
    $exifTags = $exifTagReader->read($file = __DIR__.'/../config/files/GPSCoordinates.jpeg');

    $tableData = [];

    if (null !== $capturedAt = $exifTags->getCapturedAt()) {
        $tableData['CapturedAt'] = $capturedAt->format('c');
    }

    $tableData['Latitude'] = $exifTags->gps()->getLatitude(false);
    $tableData['Longitude'] = $exifTags->gps()->getLongitude(false);
    $tableData['Altitude'] = $exifTags->gps()->getAltitude(false);

    if ($capturedAt = $exifTags->gps()->getCapturedAt()) {
        $tableData['GPSCapturedAt'] = $capturedAt->format('c');
    }

    $io->section(basename($file));

    $io->table(array_keys($tableData), [
        array_values($tableData),
    ]);

    // Photo with SceneType tag containing a single control character
    $exifTags = $exifTagReader->read($file = __DIR__.'/../config/files/SceneType.jpeg');

    $tableData = [];

    foreach (['Make', 'Model', 'Software', 'SceneType'] as $tag) {
        $tableData[$tag] = $exifTags->get($tag);
    }

    $io->section(basename($file));

    $io->table(array_keys($tableData), [
        array_values($tableData),
    ]);

    return Command::SUCCESS;
};

new SingleCommandApplication()->setName('exif-tools Examples')->setCode($command)->run();
