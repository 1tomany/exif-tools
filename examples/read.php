<?php

require_once __DIR__.'/../vendor/autoload.php';

// $tags = new \OneToMany\ExifTools\Reader\ExifTagReader()->read('/Users/vic/Downloads/ao-smith-label.jpg');
$tags = new OneToMany\ExifTools\Reader\ExifTagReader()->read('/Users/vic/Downloads/IMG_3470.jpeg');

// print_r($tags->all());
var_dump($tags->has('GPSAltitudeRef'));
var_dump($tags->has('COMPUTED'));

var_dump($tags->get('GPSDestBearing')->getValue());
// print_r($tags->get('COMPUTED')->getValue());
