<?php

require_once __DIR__.'/../vendor/autoload.php';

// $tags = new \OneToMany\ExifTools\Reader\ExifTagReader()->read('/Users/vic/Downloads/ao-smith-label.jpg');
$tags = new OneToMany\ExifTools\Reader\ExifTagReader()->read('/Users/vic/Downloads/ao-smith-label.jpg');

print_r($tags->toArray());
// var_dump($tags->get('ComponentsConfiguration')->isList());
// var_dump($tags->has('COMPUTED'));
// var_dump($tags->get('COMPUTED')->getValue()->has('height'));
// var_dump($tags->get('HostComputer')->getValue());
// print_r($tags->get('COMPUTED')->getValue());
