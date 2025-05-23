<?php

namespace tbQuar\CustomStyle;

use BaconQrCode\Encoder\ByteMatrix;
use BaconQrCode\Exception\InvalidArgumentException;
use BaconQrCode\Renderer\Module\ModuleInterface;
use BaconQrCode\Renderer\Path\Path;

/**
 *  Creates individual rounded rectangle modules with simple line approximation.
 */
final class StarStyle implements ModuleInterface
{
    /**
     * @param float $radius
     */
    public function __construct(private float $radius)
    {
        if ($radius <= 0 || $radius > 0.5) {
            throw new InvalidArgumentException('Radius must be between 0 (exclusive) and 0.5 (inclusive)');
        }

        $this->radius = $radius;
    }

    /**
     * @param ByteMatrix $matrix
     * @return Path
     */
    public function createPath(ByteMatrix $matrix): Path
    {
        $path = new Path;
        $width = $matrix->getWidth();
        $height = $matrix->getHeight();

        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                if ($matrix->get($x, $y) === 1) {
                    $path = $this->addRoundedRectangle($path, $x, $y);
                }
            }
        }

        return $path;
    }

    /**
     * @param Path $path
     * @param int $x
     * @param int $y
     * @return Path
     */
    private function addRoundedRectangle(Path $path, int $x, int $y): Path
    {
        $left = $x;
        $top = $y;
        $right = $x + 1;
        $bottom = $y + 1;
        $r = $this->radius;

        $path = $path->move($left + $r, $top);
        $path = $path->line($right - $r, $top);
        $path = $path->line($right, $top + $r);
        $path = $path->line($right, $bottom - $r);
        $path = $path->line($right - $r, $bottom);
        $path = $path->line($left + $r, $bottom);
        $path = $path->line($left, $bottom - $r);
        $path = $path->line($left, $top + $r);
        $path = $path->line($left + $r, $top);

        return $path->close();
    }
}
