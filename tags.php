<?php

class ExifTag
{
    public string $tag;
    public int|string|array|ExifTagList $value;

    public function __construct(string $tag, int|string|array $value)
    {
        $this->tag = trim($tag);

        if (is_int($value)) {
            $this->value = $value;
        } elseif (is_string($value)) {
            $this->value = trim($value);
        } else {
            if (array_is_list($value)) {
                $this->value = $value;
            } else {
                $this->value = new ExifTagList($value);
            }
        }
    }
}

class ExifTagList
{
    public array $tags = [];

    public function __construct(array $tags)
    {
        foreach ($tags as $tag => $value) {
            $this->tags[] = new ExifTag($tag, $value);
        }
    }
}

$tags = exif_read_data('/Users/vic/Downloads/IMG_3470.jpeg');

$exifTagList = new ExifTagList($tags);
print_r($exifTagList->tags[6]);
