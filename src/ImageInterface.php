<?php

namespace tbQuar;

interface ImageInterface
{
    /**
     * Creates a new Image object.
     *
     * @param $image string An image string
     */
    public function __construct(string $image);

    /*
     * Returns the width of an image
     *
     * @return int
     */
    public function getWidth(): int;

    /*
     * Returns the height of an image
     *
     * @return int
     */
    public function getHeight(): int;

    /**
     * Returns the image string.
     *
     * @return string
     */
    public function getImageResource(): string;
}
