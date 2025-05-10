<?php

namespace tbQuar;

use InvalidArgumentException;

class HexToRgb
{
    /**
     * @var int
     */
    private int $red;

    /**
     * @var int
     */
    private int $green;

    /**
     * @var int
     */
    private int $blue;

    /**
     * @param string $hex
     * @param bool $alpha
     */
    public function __construct(public string $hex, public bool $alpha = false)
    {
        if ($this->validateHex($hex) === false) {
            throw new InvalidArgumentException('Invalid hex value, not a hex code');
        }

        $this->hexToRgb($hex, $alpha);
    }

    /**
     * @return array
     */
    public function toRGBArray(): array
    {
        return [
            $this->red,
            $this->green,
            $this->blue,
        ];
    }

    /**
     * Validate a hex code. Returns true if valid, false if not.
     *
     * @param string $hex
     * @return bool
     */
    private function validateHex(string $hex): bool
    {
        return preg_match('/^[#]?([0-9a-fA-F]{3}){1,2}$/', $hex) === 1;
    }

    /**
     * Convert a hex code to RGB.
     *
     * @param string $hex
     * @param bool $alpha
     * @return void
     */
    private function hexToRgb(string $hex, bool $alpha = false): void
    {
        $hex = str_replace('#', '', $hex);
        $length = strlen($hex);

        if ($length === 6) {
            $this->red = hexdec(substr($hex, 0, 2));
            $this->green = hexdec(substr($hex, 2, 2));
            $this->blue = hexdec(substr($hex, 4, 2));
        } elseif ($length === 3) {
            $this->red = hexdec(str_repeat(substr($hex, 0, 1), 2));
            $this->green = hexdec(str_repeat(substr($hex, 1, 1), 2));
            $this->blue = hexdec(str_repeat(substr($hex, 2, 1), 2));
        } else {
            $this->red = $this->green = $this->blue = 0;
        }

        if ($alpha) {
            $this->alpha = $alpha;
        }

    }
}
