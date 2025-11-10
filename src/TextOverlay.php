<?php

namespace tbQuar;

use InvalidArgumentException;

class TextOverlay
{
    /**
     * The text to be added
     *
     * @var string
     */
    protected string $text;

    /**
     * Text position
     *
     * @var string
     */
    protected string $position = 'bottom';

    /**
     * Text color (hex)
     *
     * @var string
     */
    protected string $textColor = '#000000';

    /**
     * Background color (hex)
     *
     * @var string
     */
    protected string $backgroundColor = '#FFFFFF';

    /**
     * Font size
     *
     * @var int
     */
    protected int $fontSize = 12;

    /**
     * Padding
     *
     * @var int
     */
    protected int $padding = 10;

    /**
     * Is background enabled?
     *
     * @var bool
     */
    protected bool $backgroundEnabled = true;

    /**
     * Background opacity (0.0 - 1.0)
     *
     * @var float
     */
    protected float $backgroundOpacity = 1.0;

    /**
     * Font file path
     *
     * @var string|null
     */
    protected ?string $fontPath = null;

    /**
     * Valid positions
     *
     * @var array
     */
    protected const VALID_POSITIONS = [
        'top',
        'bottom',
        'left',
        'right',
        'top-left',
        'top-right',
        'bottom-left',
        'bottom-right',
    ];

    /**
     * Constructor
     *
     * @param string $text
     * @param string $position
     */
    public function __construct(string $text, string $position = 'bottom')
    {
        $this->setText($text);
        $this->setPosition($position);
    }

    /**
     * Sets the text
     *
     * @param string $text
     * @return TextOverlay
     */
    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Sets the position
     *
     * @param string $position
     * @return TextOverlay
     */
    public function setPosition(string $position): self
    {
        if (! in_array($position, self::VALID_POSITIONS)) {
            throw new InvalidArgumentException(
                'Position must be one of: '.implode(', ', self::VALID_POSITIONS).'. '.$position.' is not valid.'
            );
        }

        $this->position = $position;

        return $this;
    }

    /**
     * Sets the text color
     *
     * @param string $hexColor
     * @return TextOverlay
     */
    public function setTextColor(string $hexColor): self
    {
        $this->validateHexColor($hexColor);
        $this->textColor = $hexColor;

        return $this;
    }

    /**
     * Sets the background color
     *
     * @param string $hexColor
     * @return TextOverlay
     */
    public function setBackgroundColor(string $hexColor): self
    {
        if (strtolower($hexColor) === 'transparent') {
            $this->backgroundEnabled = false;

            return $this;
        }

        $this->validateHexColor($hexColor);
        $this->backgroundColor = $hexColor;

        return $this;
    }

    /**
     * Sets the background opacity (alpha channel)
     *
     * @param float $opacity Between 0.0 (fully transparent) and 1.0 (fully opaque)
     * @return TextOverlay
     */
    public function setBackgroundOpacity(float $opacity): self
    {
        if ($opacity < 0 || $opacity > 1) {
            throw new InvalidArgumentException('Opacity must be between 0.0 and 1.0. '.$opacity.' is not valid.');
        }

        $this->backgroundOpacity = $opacity;

        return $this;
    }

    /**
     * Sets the font size
     *
     * @param int $size
     * @return TextOverlay
     */
    public function setFontSize(int $size): self
    {
        if ($size < 1) {
            throw new InvalidArgumentException('Font size must be greater than 0. '.$size.' is not valid.');
        }

        $this->fontSize = $size;

        return $this;
    }

    /**
     * Sets the padding
     *
     * @param int $padding
     * @return TextOverlay
     */
    public function setPadding(int $padding): self
    {
        if ($padding < 0) {
            throw new InvalidArgumentException('Padding must be 0 or greater. '.$padding.' is not valid.');
        }

        $this->padding = $padding;

        return $this;
    }

    /**
     * Enables or disables the background
     *
     * @param bool $enabled
     * @return TextOverlay
     */
    public function setBackgroundEnabled(bool $enabled): self
    {
        $this->backgroundEnabled = $enabled;

        return $this;
    }

    /**
     * Sets the font path
     *
     * @param string $path
     * @return TextOverlay
     */
    public function setFont(string $path): self
    {
        if (! file_exists($path)) {
            throw new InvalidArgumentException('Font file not found at: '.$path);
        }

        $this->fontPath = $path;

        return $this;
    }

    /**
     * Applies text overlay to SVG
     *
     * @param string $svg
     * @param int $qrWidth
     * @param int $qrHeight
     * @return string
     */
    public function applyToSvg(string $svg, int $qrWidth, int $qrHeight): string
    {
        $textWidth = strlen($this->text) * ($this->fontSize * 0.6);
        $textHeight = $this->fontSize + ($this->padding * 2);

        [$x, $y, $textAnchor, $newWidth, $newHeight] = $this->calculateSvgPosition(
            $qrWidth,
            $qrHeight,
            $textWidth,
            $textHeight
        );

        $svg = preg_replace('/width="(\d+)"/', 'width="'.$newWidth.'"', $svg);
        $svg = preg_replace('/height="(\d+)"/', 'height="'.$newHeight.'"', $svg);
        $svg = preg_replace('/viewBox="[^"]*"/', 'viewBox="0 0 '.$newWidth.' '.$newHeight.'"', $svg);

        $groupX = 0;
        $groupY = 0;

        if (in_array($this->position, ['top', 'top-left', 'top-right'])) {
            $groupY = $textHeight;
        }
        if ($this->position === 'left') {
            $groupX = $textWidth + ($this->padding * 2);
        }

        preg_match('/<svg[^>]*>(.*)<\/svg>/s', $svg, $contentMatch);
        $content = $contentMatch[1] ?? '';

        $textY = $y + ($this->fontSize / 2) + ($this->padding / 2);

        $textSvg = '';

        if ($this->backgroundEnabled) {
            $bgX = $x - ($textWidth / 2) - $this->padding;
            $bgY = $y - $this->padding;
            $bgWidth = $textWidth + ($this->padding * 2);
            $bgHeight = $textHeight;

            if ($textAnchor === 'start') {
                $bgX = $x - $this->padding;
            } elseif ($textAnchor === 'end') {
                $bgX = $x - $textWidth - $this->padding;
            }

            $fillColor = $this->backgroundColor;
            $fillOpacity = '';

            if ($this->backgroundOpacity < 1.0) {
                $fillOpacity = sprintf(' fill-opacity="%.2f"', $this->backgroundOpacity);
            }

            $textSvg .= sprintf(
                '<rect x="%d" y="%d" width="%d" height="%d" fill="%s"%s/>',
                $bgX,
                $bgY,
                $bgWidth,
                $bgHeight,
                $fillColor,
                $fillOpacity
            );
        }

        $textSvg .= sprintf(
            '<text x="%d" y="%d" font-family="Arial, sans-serif" font-size="%d" fill="%s" text-anchor="%s">%s</text>',
            $x,
            $textY,
            $this->fontSize,
            $this->textColor,
            $textAnchor,
            htmlspecialchars($this->text)
        );

        return preg_replace(
            '/<svg([^>]*)>.*<\/svg>/s',
            sprintf(
                '<svg$1><g transform="translate(%d, %d)">%s</g>%s</svg>',
                $groupX,
                $groupY,
                $content,
                $textSvg
            ),
            $svg
        );
    }

    /**
     * Applies text overlay to PNG
     *
     * @param string $pngData
     * @return string
     */
    public function applyToPng(string $pngData): string
    {
        $image = imagecreatefromstring($pngData);
        if (! $image) {
            throw new InvalidArgumentException('Invalid PNG data provided.');
        }

        $width = imagesx($image);
        $height = imagesy($image);

        $fontPath = $this->fontPath ?? $this->getDefaultFont();
        if (empty($fontPath)) {
            throw new InvalidArgumentException(
                'No font file available. Please set a font using setFont() method.'
            );
        }

        $textBox = imagettfbbox($this->fontSize, 0, $fontPath, $this->text);
        $textWidth = abs($textBox[4] - $textBox[0]);
        $textHeight = abs($textBox[5] - $textBox[1]);

        [$newWidth, $newHeight, $offsetX, $offsetY] = $this->calculatePngDimensions(
            $width,
            $height,
            $textWidth,
            $textHeight
        );

        $newImage = imagecreatetruecolor($newWidth, $newHeight);

        imagealphablending($newImage, false);
        imagesavealpha($newImage, true);

        $transparent = imagecolorallocatealpha($newImage, 0, 0, 0, 127);
        imagefill($newImage, 0, 0, $transparent);

        imagealphablending($newImage, true);

        imagecopy($newImage, $image, $offsetX, $offsetY, 0, 0, $width, $height);

        $textRgb = $this->hexToRgb($this->textColor);
        $textColor = imagecolorallocate($newImage, $textRgb[0], $textRgb[1], $textRgb[2]);

        [$textX, $textY] = $this->calculatePngTextPosition(
            $newWidth,
            $newHeight,
            $textWidth,
            $textHeight,
            $offsetX,
            $offsetY,
            $width,
            $height
        );

        if ($this->backgroundEnabled) {
            $boxBgRgb = $this->hexToRgb($this->backgroundColor);

            if ($this->backgroundOpacity < 1.0) {
                $alpha = (int) (127 - ($this->backgroundOpacity * 127));
                $boxBgColor = imagecolorallocatealpha($newImage, $boxBgRgb[0], $boxBgRgb[1], $boxBgRgb[2], $alpha);
            } else {
                $boxBgColor = imagecolorallocate($newImage, $boxBgRgb[0], $boxBgRgb[1], $boxBgRgb[2]);
            }

            imagefilledrectangle(
                $newImage,
                $textX - $this->padding,
                $textY - $textHeight - $this->padding,
                $textX + $textWidth + $this->padding,
                $textY + $this->padding,
                $boxBgColor
            );
        }

        imagettftext(
            $newImage,
            $this->fontSize,
            0,
            $textX,
            $textY,
            $textColor,
            $fontPath,
            $this->text
        );

        ob_start();
        imagepng($newImage);
        $result = ob_get_clean();

        imagedestroy($image);
        imagedestroy($newImage);

        return $result;
    }

    /**
     * Calculates position for SVG
     *
     * @param int $width
     * @param int $height
     * @param float $textWidth
     * @param int $textHeight
     * @return array
     */
    protected function calculateSvgPosition(int $width, int $height, float $textWidth, int $textHeight): array
    {
        $x = $width / 2;
        $y = 0;
        $textAnchor = 'middle';
        $newWidth = $width;
        $newHeight = $height;

        switch ($this->position) {
            case 'bottom':
                $y = $height + $this->padding;
                $newHeight = $height + $textHeight;
                break;
            case 'top':
                $y = $this->padding;
                $newHeight = $height + $textHeight;
                break;
            case 'left':
                $x = $this->padding;
                $y = $height / 2;
                $textAnchor = 'start';
                $newWidth = $width + $textWidth + ($this->padding * 2);
                break;
            case 'right':
                $x = $width + $this->padding;
                $y = $height / 2;
                $textAnchor = 'start';
                $newWidth = $width + $textWidth + ($this->padding * 2);
                break;
            case 'top-left':
                $x = $this->padding;
                $y = $this->padding;
                $textAnchor = 'start';
                $newHeight = $height + $textHeight;
                break;
            case 'top-right':
                $x = $width - $this->padding;
                $y = $this->padding;
                $textAnchor = 'end';
                $newHeight = $height + $textHeight;
                break;
            case 'bottom-left':
                $x = $this->padding;
                $y = $height + $this->padding;
                $textAnchor = 'start';
                $newHeight = $height + $textHeight;
                break;
            case 'bottom-right':
                $x = $width - $this->padding;
                $y = $height + $this->padding;
                $textAnchor = 'end';
                $newHeight = $height + $textHeight;
                break;
        }

        return [$x, $y, $textAnchor, $newWidth, $newHeight];
    }

    /**
     * Calculates dimensions for PNG
     *
     * @param int $width
     * @param int $height
     * @param int $textWidth
     * @param int $textHeight
     * @return array
     */
    protected function calculatePngDimensions(int $width, int $height, int $textWidth, int $textHeight): array
    {
        $textArea = $textHeight + ($this->padding * 2);

        $newWidth = $width;
        $newHeight = $height;
        $offsetX = 0;
        $offsetY = 0;

        switch ($this->position) {
            case 'bottom':
            case 'bottom-left':
            case 'bottom-right':
                $newHeight = $height + $textArea;
                break;
            case 'top':
            case 'top-right':
            case 'top-left':
                $newHeight = $height + $textArea;
                $offsetY = $textArea;
                break;
            case 'left':
                $newWidth = $width + $textWidth + ($this->padding * 2);
                $offsetX = $textWidth + ($this->padding * 2);
                break;
            case 'right':
                $newWidth = $width + $textWidth + ($this->padding * 2);
                break;
        }

        return [$newWidth, $newHeight, $offsetX, $offsetY];
    }

    /**
     * Calculates text position for PNG
     *
     * @param int $newWidth
     * @param int $newHeight
     * @param int $textWidth
     * @param int $textHeight
     * @param int $offsetX
     * @param int $offsetY
     * @param int $qrWidth
     * @param int $qrHeight
     * @return array
     */
    protected function calculatePngTextPosition(
        int $newWidth,
        int $newHeight,
        int $textWidth,
        int $textHeight,
        int $offsetX,
        int $offsetY,
        int $qrWidth,
        int $qrHeight
    ): array {
        $textX = 0;
        $textY = 0;

        switch ($this->position) {
            case 'bottom':
                $textX = ($newWidth - $textWidth) / 2;
                $textY = $offsetY + $qrHeight + $textHeight + $this->padding;
                break;
            case 'top':
                $textX = ($newWidth - $textWidth) / 2;
                $textY = $textHeight + $this->padding;
                break;
            case 'left':
                $textX = $this->padding;
                $textY = ($newHeight + $textHeight) / 2;
                break;
            case 'right':
                $textX = $offsetX + $qrWidth + $this->padding;
                $textY = ($newHeight + $textHeight) / 2;
                break;
            case 'top-left':
                $textX = $this->padding;
                $textY = $textHeight + $this->padding;
                break;
            case 'top-right':
                $textX = $newWidth - $textWidth - $this->padding;
                $textY = $textHeight + $this->padding;
                break;
            case 'bottom-left':
                $textX = $this->padding;
                $textY = $offsetY + $qrHeight + $textHeight + $this->padding;
                break;
            case 'bottom-right':
                $textX = $newWidth - $textWidth - $this->padding;
                $textY = $offsetY + $qrHeight + $textHeight + $this->padding;
                break;
        }

        return [$textX, $textY];
    }

    /**
     * Converts hex color to RGB
     *
     * @param string $hex
     * @return array
     */
    protected function hexToRgb(string $hex): array
    {
        return (new HexToRgb($hex))->toRGBArray();
    }

    /**
     * Validates hex color code
     *
     * @param string $hex
     * @return void
     */
    protected function validateHexColor(string $hex): void
    {
        if (! preg_match('/^#[0-9A-Fa-f]{6}$/', $hex)) {
            throw new InvalidArgumentException(
                'Invalid hex color format: '.$hex.'. Expected format: #RRGGBB'
            );
        }
    }

    /**
     * Returns default font path
     *
     * @return string|null
     */
    protected function getDefaultFont(): ?string
    {
        $possibleFonts = [
            '/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf',
            '/usr/share/fonts/truetype/liberation/LiberationSans-Regular.ttf',
            '/System/Library/Fonts/Helvetica.ttc',
            'C:\Windows\Fonts\arial.ttf',
        ];

        foreach ($possibleFonts as $font) {
            if (file_exists($font)) {
                return $font;
            }
        }

        return null;
    }

    // Getters

    /**
     * Gets the text
     *
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * Gets the position
     *
     * @return string
     */
    public function getPosition(): string
    {
        return $this->position;
    }

    /**
     * Gets the text color
     *
     * @return string
     */
    public function getTextColor(): string
    {
        return $this->textColor;
    }

    /**
     * Gets the background color
     *
     * @return string
     */
    public function getBackgroundColor(): string
    {
        return $this->backgroundColor;
    }

    /**
     * Gets the font size
     *
     * @return int
     */
    public function getFontSize(): int
    {
        return $this->fontSize;
    }

    /**
     * Gets the padding
     *
     * @return int
     */
    public function getPadding(): int
    {
        return $this->padding;
    }

    /**
     * Checks if background is enabled
     *
     * @return bool
     */
    public function isBackgroundEnabled(): bool
    {
        return $this->backgroundEnabled;
    }

    /**
     * Gets the background opacity
     *
     * @return float
     */
    public function getBackgroundOpacity(): float
    {
        return $this->backgroundOpacity;
    }

    /**
     * Gets the font path
     *
     * @return string|null
     */
    public function getFontPath(): ?string
    {
        return $this->fontPath;
    }
}
