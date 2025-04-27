<?php

namespace tbQuar;

use InvalidArgumentException;

class ImageMerge
{
    /**
     * Holds the QrCode image.
     *
     * @var Image
     */
    protected Image $sourceImage;

    /**
     * Holds the merging image.
     *
     * @var Image
     */
    protected Image $mergeImage;

    /**
     * The height of the source image.
     *
     * @var int
     */
    protected int $sourceImageHeight;

    /**
     * The width of the source image.
     *
     * @var int
     */
    protected int $sourceImageWidth;

    /**
     * The height of the merge image.
     *
     * @var int
     */
    protected int $mergeImageHeight;

    /**
     * The width of the merge image.
     *
     * @var int
     */
    protected int $mergeImageWidth;

    /**
     * Holds the radio of the merging image.
     *
     * @var float
     */
    protected float $mergeRatio;

    /**
     * The height of the merge image after it is merged.
     *
     * @var int
     */
    protected int $postMergeImageHeight;

    /**
     * The width of the merge image after it is merged.
     *
     * @var int
     */
    protected int $postMergeImageWidth;

    /**
     * The position that the merge image is placed on top of the source image.
     *
     * @var int
     */
    protected int $centerY;

    /**
     * The position that the merge image is placed on top of the source image.
     *
     * @var int
     */
    protected int $centerX;

    /**
     * Creates a new ImageMerge object.
     *
     * @param $sourceImage Image The image that will be merged over.
     * @param $mergeImage Image The image that will be used to merge with $sourceImage
     */
    public function __construct(Image $sourceImage, Image $mergeImage)
    {
        $this->sourceImage = $sourceImage;
        $this->mergeImage = $mergeImage;
    }

    /**
     * Returns an QrCode that has been merge with another image.
     * This is usually used with logos to imprint a logo into a QrCode.
     *
     * @param $percentage float The percentage of size relative to the entire QR of the merged image
     * @return string
     */
    public function merge(float $percentage): string
    {
        $this->setProperties($percentage);

        $img = imagecreatetruecolor($this->sourceImage->getWidth(), $this->sourceImage->getHeight());
        imageantialias($img, true);
        imagealphablending($img, false);
        imagesavealpha($img, true);
        $transparent = imagecolorallocatealpha($img, 0, 0, 0, 127);
        imagefilledrectangle($img, 0, 0, $this->sourceImage->getWidth() - 1, $this->sourceImage->getHeight() - 1, $transparent);

        imagecopy(
            $img,
            $this->sourceImage->getImageResource(),
            0,
            0,
            0,
            0,
            $this->sourceImage->getWidth(),
            $this->sourceImage->getHeight()
        );

        imagealphablending($img, true);
        imagesetinterpolation($img, IMG_BICUBIC_FIXED);

        imagecopyresampled(
            $img,
            $this->mergeImage->getImageResource(),
            $this->centerX,
            $this->centerY,
            0,
            0,
            $this->postMergeImageWidth,
            $this->postMergeImageHeight,
            $this->mergeImageWidth,
            $this->mergeImageHeight
        );

        imagesavealpha($img, true);

        $this->sourceImage->setImageResource($img);

        return $this->createImage();
    }

    /**
     * Creates a PNG Image.
     *
     * @return string
     */
    private function createImage(): string
    {
        ob_start();
        imagepng($this->sourceImage->getImageResource());

        return ob_get_clean();
    }

    /**
     * Sets the objects properties.
     *
     * @param $percentage float The percentage that the merge image should take up.
     * @return void
     */
    private function setProperties(float $percentage): void
    {
        if ($percentage > 1) {
            throw new InvalidArgumentException('$percentage must be less than 1');
        }

        $this->sourceImageHeight = $this->sourceImage->getHeight();
        $this->sourceImageWidth = $this->sourceImage->getWidth();

        $this->mergeImageHeight = $this->mergeImage->getHeight();
        $this->mergeImageWidth = $this->mergeImage->getWidth();

        $this->calculateOverlap($percentage);
        $this->calculateCenter();
    }

    /**
     * Calculates the center of the source Image using the Merge image.
     *
     * @return void
     */
    private function calculateCenter(): void
    {
        $this->centerX = intval(($this->sourceImageWidth / 2) - ($this->postMergeImageWidth / 2));
        $this->centerY = intval(($this->sourceImageHeight / 2) - ($this->postMergeImageHeight / 2));
    }

    /**
     * Calculates the width of the merge image being placed on the source image.
     *
     * @param float $percentage
     * @return void
     */
    private function calculateOverlap(float $percentage): void
    {
        $this->mergeRatio = round($this->mergeImageWidth / $this->mergeImageHeight, 2);
        $this->postMergeImageWidth = intval($this->sourceImageWidth * $percentage);
        $this->postMergeImageHeight = intval($this->postMergeImageWidth / $this->mergeRatio);
    }
}
