<?php

namespace tbQuar\CustomEyes;

use BaconQrCode\Renderer\Eye\EyeInterface;
use BaconQrCode\Renderer\Path\Path;

/**
 * Renders the eyes with a rounded square shape.
 */
final class RoundedSquareEye implements EyeInterface
{
    /**
     * @var RoundedSquareEye|null
     */
    private static ?RoundedSquareEye $instance = null;

    /**
     * @return self
     */
    public static function instance(): self
    {
        return self::$instance ?: self::$instance = new self;
    }

    /**
     * @return Path
     */
    public function getExternalPath(): Path
    {
        $outerSize = 3.5;
        $outerMidPoint = 1.0;

        $innerSize = 2.5;
        $innerMidPoint = 1.0;

        return (new Path)
            ->move(0, $outerSize)
            ->line($outerMidPoint, $outerSize)
            ->curve($outerMidPoint, $outerSize, $outerSize, $outerSize, $outerSize, $outerMidPoint)
            ->line($outerSize, 0)
            ->line($outerSize, -$outerMidPoint)
            ->curve($outerSize, -$outerMidPoint, $outerSize, -$outerSize, $outerMidPoint, -$outerSize)
            ->line(0, -$outerSize)
            ->line(-$outerMidPoint, -$outerSize)
            ->curve(-$outerMidPoint, -$outerSize, -$outerSize, -$outerSize, -$outerSize, -$outerMidPoint)
            ->line(-$outerSize, 0)
            ->line(-$outerSize, $outerMidPoint)
            ->curve(-$outerSize, $outerMidPoint, -$outerSize, $outerSize, -$outerMidPoint, $outerSize)
            ->line(0, $outerSize)
            ->close()

            ->move(0, $innerSize)
            ->line($innerMidPoint, $innerSize)
            ->curve($innerMidPoint, $innerSize, $innerSize, $innerSize, $innerSize, $innerMidPoint)
            ->line($innerSize, 0)
            ->line($innerSize, -$innerMidPoint)
            ->curve($innerSize, -$innerMidPoint, $innerSize, -$innerSize, $innerMidPoint, -$innerSize)
            ->line(0, -$innerSize)
            ->line(-$innerMidPoint, -$innerSize)
            ->curve(-$innerMidPoint, -$innerSize, -$innerSize, -$innerSize, -$innerSize, -$innerMidPoint)
            ->line(-$innerSize, 0)
            ->line(-$innerSize, $innerMidPoint)
            ->curve(-$innerSize, $innerMidPoint, -$innerSize, $innerSize, -$innerMidPoint, $innerSize)
            ->line(0, $innerSize)
            ->close();
    }

    /**
     * @return Path
     */
    public function getInternalPath(): Path
    {
        $size = 1.5;
        $midPoint = 0.75;

        return (new Path)
            ->move(0, $size)
            ->line($midPoint, $size)
            ->curve($midPoint, $size, $size, $size, $size, $midPoint)
            ->line($size, 0)
            ->line($size, -$midPoint)
            ->curve($size, -$midPoint, $size, -$size, $midPoint, -$size)
            ->line(0, -$size)
            ->line(-$midPoint, -$size)
            ->curve(-$midPoint, -$size, -$size, -$size, -$size, -$midPoint)
            ->line(-$size, 0)
            ->line(-$size, $midPoint)
            ->curve(-$size, $midPoint, -$size, $size, -$midPoint, $size)
            ->line(0, $size)
            ->close();
    }
}
