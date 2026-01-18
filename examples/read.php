<?php

require_once __DIR__.'/../vendor/autoload.php';

$tags = new \OneToMany\ExifTools\Reader\ExifTagReader()->read('/Users/vic/Downloads/ao-smith-label.jpg');
// $tags = new OneToMany\ExifTools\Reader\ExifTagReader()->read('/Users/vic/Downloads/ao-smith-label.jpg');

print_r($tags->toArray());
