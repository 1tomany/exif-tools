<?php

require_once __DIR__.'/../vendor/autoload.php';

$file = '/Users/vic/Downloads/IMG_3470.jpeg';
// $file = '/Users/vic/Downloads/ao-smith-label.jpg';

$tags = new OneToMany\ExifTools\Reader\ExifTagReader()->read($file);

// print_r($tags->get('COMPUTED')->get()->getIterator()->getArrayCopy());

print_r($tags->gps());
// print_r($tags->toArray());

// $tags->getGps()->latitude;
