<?php

namespace tbQuar\CustomEyes;

use BaconQrCode\Renderer\Eye\EyeInterface;
use BaconQrCode\Renderer\Path\Path;

/**
 * It turns the eyes into a ring shape.
 */
final class RingEye implements EyeInterface
{
    /**
     * @var RingEye|null
     */
    private static ?RingEye $instance = null;

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
        $outerRadius = 3.5;

        return (new Path)
            ->move($outerRadius, 0)
            ->ellipticArc($outerRadius, $outerRadius, 0, false, true, 0, $outerRadius)
            ->ellipticArc($outerRadius, $outerRadius, 0, false, true, -$outerRadius, 0)
            ->ellipticArc($outerRadius, $outerRadius, 0, false, true, 0, -$outerRadius)
            ->ellipticArc($outerRadius, $outerRadius, 0, false, true, $outerRadius, 0)
            ->close()
            ->move(2.5, 0)
            ->ellipticArc(2.5, 2.5, 0, false, true, 0, 2.5)
            ->ellipticArc(2.5, 2.5, 0, false, true, -2.5, 0)
            ->ellipticArc(2.5, 2.5, 0, false, true, 0, -2.5)
            ->ellipticArc(2.5, 2.5, 0, false, true, 2.5, 0)
            ->close();
    }

    /**
     * @return Path
     */
    public function getInternalPath(): Path
    {
        $innerRadius = 1.6;

        return (new Path)
            ->move($innerRadius, 0)
            ->ellipticArc($innerRadius, $innerRadius, 0, false, true, 0, $innerRadius)
            ->ellipticArc($innerRadius, $innerRadius, 0, false, true, -$innerRadius, 0)
            ->ellipticArc($innerRadius, $innerRadius, 0, false, true, 0, -$innerRadius)
            ->ellipticArc($innerRadius, $innerRadius, 0, false, true, $innerRadius, 0)
            ->close();
    }
}
