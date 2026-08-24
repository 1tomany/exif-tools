#!/usr/bin/env php
<?php

require_once __DIR__.'/../vendor/autoload.php';

use OneToMany\ExifTools\Reader\ExifTagReader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\SingleCommandApplication;
use Symfony\Component\Console\Style\SymfonyStyle;

$command = function (SymfonyStyle $io): int {
    $io->title('exif-tools Examples');

    $exifTagReader = new ExifTagReader();

    // Photo with ComponentsConfiguration tag containing multiple control characters
    $exifTags = $exifTagReader->read($file = __DIR__.'/../config/files/ComponentsConfiguration.jpeg');

    $exifList = [];

    foreach (['ComponentsConfiguration'] as $tag) {
        $exifList[$tag] = $exifTags->get($tag);
    }

    $io->section(basename($file));
    $io->table(array_keys($exifList), [$exifList]);

    // Photo with creation timestamp, GPS coordinates, and altitude
    $exifTags = $exifTagReader->read($file = __DIR__.'/../config/files/GPSCoordinates.jpeg');

    $exifList = [];

    if (null !== $capturedAt = $exifTags->getCapturedAt()) {
        $exifList['CapturedAt'] = $capturedAt->format('c');
    }

    if (null !== $gps = $exifTags->gps()) {
        $exifList['Latitude'] = $gps->getLatitude(false);
        $exifList['Longitude'] = $gps->getLongitude(false);
        $exifList['Altitude'] = $gps->getAltitude(false);

        if (null !== $capturedAt = $gps->getCapturedAt()) {
            $exifList['GPSCapturedAt'] = $capturedAt->format('c');
        }
    }

    $io->section(basename($file));
    $io->table(array_keys($exifList), [$exifList]);

    // Photo with SceneType tag containing a single control character
    $exifTags = $exifTagReader->read($file = __DIR__.'/../config/files/SceneType.jpeg');

    $exifList = [];

    foreach (['Make', 'Model', 'Software', 'SceneType'] as $tag) {
        $exifList[$tag] = $exifTags->get($tag);
    }

    $io->section(basename($file));
    $io->table(array_keys($exifList), [$exifList]);

    return Command::SUCCESS;
};

new SingleCommandApplication()->setName('exif-tools Examples')->setCode($command)->run();
