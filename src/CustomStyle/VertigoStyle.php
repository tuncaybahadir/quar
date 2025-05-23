<?php

namespace tbQuar\CustomStyle;

use BaconQrCode\Encoder\ByteMatrix;
use BaconQrCode\Exception\InvalidArgumentException;
use BaconQrCode\Renderer\Module\EdgeIterator\EdgeIterator;
use BaconQrCode\Renderer\Module\ModuleInterface;
use BaconQrCode\Renderer\Path\Path;

/**
 * Creates organic, flowing QR code modules with smooth curves.
 */
final class VertigoStyle implements ModuleInterface
{
    /**
     * @param float $smoothness
     */
    public function __construct(private float $smoothness)
    {
        if ($smoothness <= 0 || $smoothness > 0.5) {
            throw new InvalidArgumentException('Smoothness must be between 0 (exclusive) and 0.5 (inclusive)');
        }

        $this->smoothness = $smoothness;
    }

    /**
     * @param ByteMatrix $matrix
     * @return Path
     */
    public function createPath(ByteMatrix $matrix): Path
    {
        $path = new Path;

        foreach (new EdgeIterator($matrix) as $edge) {
            $points = $edge->getSimplifiedPoints();
            $length = count($points);

            if ($length < 3) {
                continue;
            }

            $startPoint = $points[0];
            $secondPoint = $points[1];
            $lastPoint = $points[$length - 1];

            $startOffset = $this->calculateSmoothOffset($lastPoint, $startPoint, $secondPoint);

            $path = $path->move(
                $startPoint[0] + $startOffset[0],
                $startPoint[1] + $startOffset[1]
            );

            for ($i = 0; $i < $length; $i++) {
                $prevPoint = $points[($i - 1 + $length) % $length];
                $currentPoint = $points[$i];
                $nextPoint = $points[($i + 1) % $length];
                $nextNextPoint = $points[($i + 2) % $length];

                $cp1 = $this->calculateControlPoint($prevPoint, $currentPoint, $nextPoint, true);
                $cp2 = $this->calculateControlPoint($currentPoint, $nextPoint, $nextNextPoint, false);

                $endOffset = $this->calculateSmoothOffset($currentPoint, $nextPoint, $nextNextPoint);
                $endPoint = [
                    $nextPoint[0] + $endOffset[0],
                    $nextPoint[1] + $endOffset[1],
                ];

                $path = $path->curve(
                    $cp1[0], $cp1[1],
                    $cp2[0], $cp2[1],
                    $endPoint[0], $endPoint[1]
                );
            }

            $path = $path->close();
        }

        return $path;
    }

    /**
     * @param array $prev
     * @param array $current
     * @param array $next
     * @return float[]|int[]
     */
    private function calculateSmoothOffset(array $prev, array $current, array $next): array
    {
        $dir1 = [$current[0] - $prev[0], $current[1] - $prev[1]];
        $dir2 = [$next[0] - $current[0], $next[1] - $current[1]];

        $len1 = sqrt($dir1[0] * $dir1[0] + $dir1[1] * $dir1[1]);
        $len2 = sqrt($dir2[0] * $dir2[0] + $dir2[1] * $dir2[1]);

        if ($len1 > 0) {
            $dir1[0] /= $len1;
            $dir1[1] /= $len1;
        }
        if ($len2 > 0) {
            $dir2[0] /= $len2;
            $dir2[1] /= $len2;
        }

        $avgDir = [
            ($dir1[0] + $dir2[0]) * 0.5,
            ($dir1[1] + $dir2[1]) * 0.5,
        ];

        return [
            -$avgDir[1] * $this->smoothness,
            $avgDir[0] * $this->smoothness,
        ];
    }

    /**
     * @param array $prev
     * @param array $current
     * @param array $next
     * @param bool $isFirst
     * @return float[]
     */
    private function calculateControlPoint(array $prev, array $current, array $next, bool $isFirst): array
    {
        $factor = $isFirst ? 0.3 : 0.7;

        $direction = [
            $next[0] - $prev[0],
            $next[1] - $prev[1],
        ];

        return [
            $current[0] + $direction[0] * $this->smoothness * $factor,
            $current[1] + $direction[1] * $this->smoothness * $factor,
        ];
    }
}
