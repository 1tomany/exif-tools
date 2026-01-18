<?php

namespace OneToMany\ExifTools\Contract\Record;

interface ExifValueInterface
{
    public function getValue(): int|string|ExifListInterface|ExifMapInterface;

    /**
     * @phpstan-assert-if-true int $this->getValue()
     */
    public function isInt(): bool;

    /**
     * @phpstan-assert-if-true string $this->getValue()
     */
    public function isString(): bool;

    /**
     * @phpstan-assert-if-true ExifListInterface $this->getValue()
     */
    public function isList(): bool;

    /**
     * @phpstan-assert-if-true ExifMapInterface $this->getValue()
     */
    public function isMap(): bool;
}
