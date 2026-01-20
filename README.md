# EXIF Reader for PHP
This simple library makes it easy to read EXIF data from JPEG and TIFF files. It handles NUL-bytes correctly and automatically converts GPS coordinates to floating point numbers.

## Installation
```
composer require 1tomany/exif-tools
```

## Usage
Using the library is simple:

```php
use OneToMany\Reader\ExifTagReader;

// $exifTags is an ExifMap object
$exifTags = new ExifTagReader()->read('/path/to/photo.jpeg');

var_dump($exifTags->has('FileName')); // bool(true)
var_dump($exifTags->get('FileName')->value()); // string(10) "photo.jpeg"
```

Behind the scenes, each EXIF tag value is an object of type `ExifValue`. The `ExifValue` object can represent an integer, a string, an `ExifList`, or an `ExifMap`. Each element of an `ExifList` and `ExifMap` object is also an `ExifValue` object. 

### GPS Coordinates
`ExifMap` objects have a method named `gps()` that will attempt to read the `GPSLatitude`, `GPSLatitudeRef`, `GPSLongitude`, `GPSLongitudeRef`, `GPSAltitude`, and `GPSAltitudeRef` tags and convert them to their floating point equivalents. The `gps()` method returns an object of type `GpsValue`. The `GpsValue` object has three properties:

- `?float $latitude`
- `?float $longitude`
- `?float $altitude`

These properties will be a non-null floating point number if the value was found in the EXIF tag. A positive `$altitude` represents the altitude in meters above sealevel, and a negative `$altitude` represents the same below sealevel.

### NUL-bytes
Some EXIF tags store data encoded with NUL-bytes. For example, the tag `ComponentsConfiguration` stores a list of numbers packed as bytes. This library will unpack those bytes and convert them to their integer or list equivalents so the data can be safely encoded as JSON and stored in a database as text. 

## Credits
- [Vic Cherubini](https://github.com/viccherubini), [1:N Labs, LLC](https://1tomany.com)

## License
The MIT License
