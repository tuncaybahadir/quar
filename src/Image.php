<?php

namespace tbQuar;

use GdImage;

class Image
{
    /**
     * Holds the image resource.
     *
     * @var resource
     */
    protected $image;

    /**
     * Creates a new Image object.
     *
     * @param $image string An image string
     */
    public function __construct(string $image)
    {
        $this->image = imagecreatefromstring($image);
    }

    /*
     * Returns the width of an image
     *
     * @return int
    */
    public function getWidth(): int
    {
        return imagesx($this->image);
    }

    /*
     * Returns the height of an image
     *
     * @return int
     */
    public function getHeight(): int
    {
        return imagesy($this->image);
    }

    /**
     * Returns the image string.
     *
     * @return string|GdImage
     */
    public function getImageResource(): string|GdImage
    {
        return $this->image;
    }

    /**
     * Sets the image string.
     *
     * @param resource $image
     * @return void
     */
    public function setImageResource($image): void
    {
        $this->image = $image;
    }
}
