<?php

namespace OneToMany\ExifTools\Record;

use OneToMany\ExifTools\Exception\InvalidArgumentException;

use function array_combine;
use function array_key_exists;
use function array_keys;
use function array_map;
use function count;
use function implode;
use function sprintf;
use function vsprintf;

/**
 * @phpstan-import-type ExifValueList from ExifValue
 * @phpstan-import-type ExifValueMap from ExifValue
 *
 * @implements \IteratorAggregate<string, ExifValue>
 */
final class ExifMap implements \Countable, \IteratorAggregate, \Stringable
{
    /**
     * @var array<string, ExifValue>
     */
    private readonly array $values;

    private ?GpsValue $gps = null;

    /**
     * @param array<int|string, int|string|ExifValueList|ExifValueMap> $values
     */
    public function __construct(array $values)
    {
        // Convert all keys to strings and all values to ExifValue objects
        $this->values = array_combine(array_map(fn ($k) => (string) $k, array_keys($values)), array_map(fn ($v) => new ExifValue($v), $values));
    }

    public function __toString(): string
    {
        $stringValues = [];

        foreach ($this->values as $key => $value) {
            $stringValues[] = vsprintf('%s: %s', [
                $key, (string) $value,
            ]);
        }

        return sprintf('Map{%s}', implode(', ', $stringValues));
    }

    /**
     * @return array<string, ExifValue>
     */
    public function all(): array
    {
        return $this->values;
    }

    /**
     * @phpstan-assert-if-true ExifValue $this->get()
     */
    public function has(string $tag): bool
    {
        return array_key_exists($tag, $this->values);
    }

    public function get(string $tag): ?ExifValue
    {
        return $this->values[$tag] ?? null;
    }

    public function getTimestamp(string $tag): ?\DateTimeImmutable
    {
        return $this->get($tag)?->toTimestamp();
    }

    /**
     * Attempts to determine when the photo was captured
     * by analyzing the following tags in this order:
     * DateTimeOriginal, DateTimeDigitized, and DateTime.
     */
    public function capturedAt(): ?\DateTimeImmutable
    {
        $dateTimeTags = [
            'DateTimeOriginal',
            'DateTimeDigitized',
            'DateTime',
        ];

        foreach ($dateTimeTags as $dateTimeTag) {
            $capturedAt = $this->getTimestamp(...[
                'tag' => $dateTimeTag,
            ]);

            if (null !== $capturedAt) {
                return $capturedAt;
            }
        }

        return null;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function gps(): GpsValue
    {
        $this->gps ??= GpsValue::create(
            $this->get('GPSLatitude'),
            $this->get('GPSLatitudeRef'),
            $this->get('GPSLongitude'),
            $this->get('GPSLongitudeRef'),
            $this->get('GPSAltitude'),
            $this->get('GPSAltitudeRef'),
        );

        return $this->gps;
    }

    /**
     * @see \Countable
     */
    public function count(): int
    {
        return count($this->values);
    }

    /**
     * @see \IteratorAggregate
     *
     * @return \ArrayIterator<string, ExifValue>
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->values);
    }

    /**
     * @return array<string, int|string|ExifValueList|ExifValueMap>
     */
    public function toArray(): array
    {
        return array_combine(array_keys($this->values), array_map(fn ($v) => $v->value(), $this->values));
    }
}
