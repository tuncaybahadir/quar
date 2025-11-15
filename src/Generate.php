<?php

namespace tbQuar;

use BaconQrCode\Common\ErrorCorrectionLevel;
use BaconQrCode\Encoder\Encoder;
use BaconQrCode\Renderer\Color\Alpha;
use BaconQrCode\Renderer\Color\ColorInterface;
use BaconQrCode\Renderer\Color\Rgb;
use BaconQrCode\Renderer\Eye\EyeInterface;
use BaconQrCode\Renderer\Eye\ModuleEye;
use BaconQrCode\Renderer\Eye\SimpleCircleEye;
use BaconQrCode\Renderer\Eye\SquareEye;
use BaconQrCode\Renderer\Image\EpsImageBackEnd;
use BaconQrCode\Renderer\Image\ImageBackEndInterface;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Module\DotsModule;
use BaconQrCode\Renderer\Module\ModuleInterface;
use BaconQrCode\Renderer\Module\RoundnessModule;
use BaconQrCode\Renderer\Module\SquareModule;
use BaconQrCode\Renderer\RendererStyle\EyeFill;
use BaconQrCode\Renderer\RendererStyle\Fill;
use BaconQrCode\Renderer\RendererStyle\Gradient;
use BaconQrCode\Renderer\RendererStyle\GradientType;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Traits\Conditionable;
use InvalidArgumentException;
use tbQuar\CustomEyes\RingEye;
use tbQuar\CustomEyes\RoundedSquareEye;
use tbQuar\CustomStyle\StarStyle;
use tbQuar\CustomStyle\VertigoStyle;

class Generate
{
    use Conditionable;

    /**
     * Holds the selected formatter.
     *
     * @var string
     */
    protected string $format = 'svg';

    /**
     * Holds the size of the QrCode in pixels.
     *
     * @var int
     */
    protected int $pixels = 100;

    /**
     * Holds the margin size of the QrCode.
     *
     * @var int
     */
    protected int $margin = 0;

    /**
     * Holds the selected error correction.
     * L: 7% loss.
     * M: 15% loss.
     * Q: 25% loss.
     * H: 30% loss.
     *
     * @var ErrorCorrectionLevel|null
     */
    protected ?ErrorCorrectionLevel $errorCorrection = null;

    /**
     * Holds the selected encoder.  Possible values are
     * ISO-8859-2, ISO-8859-3, ISO-8859-4, ISO-8859-5, ISO-8859-6,
     * ISO-8859-7, ISO-8859-8, ISO-8859-9, ISO-8859-10, ISO-8859-11,
     * ISO-8859-12, ISO-8859-13, ISO-8859-14, ISO-8859-15, ISO-8859-16,
     * SHIFT-JIS, WINDOWS-1250, WINDOWS-1251, WINDOWS-1252, WINDOWS-1256,
     * UTF-16BE, UTF-8, ASCII, GBK, EUC-KR.
     *
     * @var string
     */
    protected string $encoding = Encoder::DEFAULT_BYTE_MODE_ENCODING;

    /**
     * The style of the blocks within the QrCode.
     * Possible values are square, dot, and round.
     *
     * @var string
     */
    protected string $style = 'square';

    /**
     * The size of the selected style between 0 and 1.
     * This only applies to dot and round.
     *
     * @var float|null
     */
    protected ?float $styleSize = null;

    /**
     * The style to apply to the eye.
     * Possible values are circle, square and rounded.
     *
     * @var string
     */
    protected string $eyeStyle = 'square';

    /**
     * The foreground color of the QrCode.
     *
     * @var ColorInterface|null
     */
    protected ?ColorInterface $color = null;

    /**
     * The background color of the QrCode.
     *
     * @var ColorInterface|null
     */
    protected ?ColorInterface $backgroundColor = null;

    /**
     * An array that holds EyeFills of the color of the eyes.
     *
     * @var array
     */
    protected array $eyeColors = [];

    /**
     * The gradient to apply to the QrCode.
     *
     * @var Gradient|null
     */
    protected ?Gradient $gradient = null;

    /**
     * Holds an image string that will be merged with the QrCode.
     *
     * @var null|string
     */
    protected ?string $imageMerge = null;

    /**
     * The percentage that a merged image should take over the source image.
     *
     * @var float
     */
    protected float $imagePercentage = .2;

    /**
     * The compression quality for PNG image
     *
     * @var int
     */
    protected int $compressionQuality = 100;

    /**
     * Text overlay object
     *
     * @var TextOverlay|null
     */
    protected ?TextOverlay $textOverlay = null;

    /**
     * Generates the QrCode.
     *
     * @param string $text
     * @param string|null $filename
     * @return bool|HtmlString|string
     */
    public function generate(string $text, ?string $filename = null): bool|HtmlString|string
    {
        $qrCode = $this->getWriter($this->getRenderer())
            ->writeString($text, $this->encoding, $this->errorCorrection);

        if ($this->imageMerge !== null && $this->format === 'png') {
            $merger = new ImageMerge(new Image($qrCode), new Image($this->imageMerge));
            $qrCode = $merger->merge($this->imagePercentage);
        }

        if ($this->textOverlay !== null) {
            $qrCode = $this->applyTextOverlay($qrCode);
        }

        if ($filename) {
            file_put_contents($filename, $qrCode);

            return true;
        }

        if (class_exists(HtmlString::class)) {
            return new HtmlString($qrCode);
        }

        return $qrCode;
    }

    /**
     * Adds text to the QR code
     *
     * @param string $text Text
     * @param string $position Position (top, bottom, left, right, top-left, top-right, bottom-left, bottom-right)
     * @return Generate
     */
    public function withText(string $text, string $position = 'bottom'): self
    {
        $this->textOverlay = new TextOverlay($text, $position);

        return $this;
    }

    /**
     * Helper method for text overlay configuration
     *
     * @param callable $callback
     * @return Generate
     */
    public function configureText(callable $callback): self
    {
        if ($this->textOverlay !== null) {
            $callback($this->textOverlay);
        }

        return $this;
    }

    /**
     * Rotates the current text overlay object
     *
     * @return TextOverlay|null
     */
    public function getTextOverlay(): ?TextOverlay
    {
        return $this->textOverlay;
    }

    /**
     * Apply text overlay
     *
     * @param string $qrCode
     * @return string
     */
    protected function applyTextOverlay(string $qrCode): string
    {
        if ($this->format === 'svg') {
            return $this->textOverlay->applyToSvg($qrCode, $this->pixels, $this->pixels);
        } elseif ($this->format === 'png') {
            return $this->textOverlay->applyToPng($qrCode);
        }

        return $qrCode;
    }

    /**
     * Merges an image over the QrCode.
     *
     * @param string $filepath
     * @param float $percentage
     * @param bool $absolute
     * @return Generate
     */
    public function merge(string $filepath, float $percentage = .2, bool $absolute = false): self
    {
        if (function_exists('base_path') && ! $absolute) {
            $filepath = base_path($filepath);
        }

        $content = @file_get_contents($filepath);

        if ($content !== false) {
            $this->imageMerge = $content;
            $this->imagePercentage = $percentage;
        } else {
            Log::warning('Image merge failed for: '.$filepath);
        }

        return $this;
    }

    /**
     * Merges an image string with the center of the QrCode.
     *
     * @param string $content
     * @param float $percentage
     * @return Generate
     */
    public function mergeString(string $content, float $percentage = .2): self
    {
        $this->imageMerge = $content;
        $this->imagePercentage = $percentage;

        return $this;
    }

    /**
     * Sets the size of the QrCode.
     *
     * @param int $pixels
     * @return Generate
     */
    public function size(int $pixels): self
    {
        $this->pixels = $pixels;

        return $this;
    }

    /**
     * Sets the format of the QrCode.
     *
     * @param string $format
     * @return Generate
     */
    public function format(string $format): self
    {
        if (! in_array($format, ['svg', 'eps', 'png'])) {
            throw new InvalidArgumentException("\$format must be svg, eps, or png. {$format} is not a valid.");
        }

        $this->format = $format;

        return $this;
    }

    /**
     * Sets the foreground color of the QrCode.
     *
     * @param int|string $redOrHex
     * @param int|null $green
     * @param int|null $blue
     * @param ?int $alpha
     * @return Generate
     */
    public function color(int|string $redOrHex, ?int $green = null, ?int $blue = null, ?int $alpha = null): self
    {
        if (is_string($redOrHex)) {
            $hexToRgb = $this->hexToRgb($redOrHex);

            return $this->color(...$hexToRgb);
        } else {
            if (is_null($green) || is_null($blue)) {
                throw new InvalidArgumentException('You must provide a green and blue value.');
            }
        }
        $this->color = $this->createColor($redOrHex, $green, $blue, $alpha);

        return $this;
    }

    /**
     * Sets the background color of the QrCode.
     *
     * @param int|string $redOrHex
     * @param int|null $green
     * @param int|null $blue
     * @param ?int $alpha
     * @return Generate
     */
    public function backgroundColor(int|string $redOrHex, ?int $green = null, ?int $blue = null, ?int $alpha = null): self
    {
        if (is_string($redOrHex)) {
            $hexToRgb = $this->hexToRgb($redOrHex);

            return $this->backgroundColor(...$hexToRgb);
        } else {
            if (is_null($green) || is_null($blue)) {
                throw new InvalidArgumentException('You must provide a green and blue value.');
            }
        }

        $this->backgroundColor = $this->createColor($redOrHex, $green, $blue, $alpha);

        return $this;
    }

    /**
     * Sets the eye color for the provided eye index.
     *
     * @param int $eyeNumber
     * @param int $innerRed
     * @param int $innerGreen
     * @param int $innerBlue
     * @param int $outterRed
     * @param int $outterGreen
     * @param int $outterBlue
     * @return Generate
     */
    public function eyeColor(int $eyeNumber, int $innerRed, int $innerGreen, int $innerBlue, int $outterRed = 0, int $outterGreen = 0, int $outterBlue = 0): self
    {
        if ($eyeNumber < 0 || $eyeNumber > 2) {
            throw new InvalidArgumentException("\$eyeNumber must be 0, 1, or 2.  {$eyeNumber} is not valid.");
        }

        $this->eyeColors[$eyeNumber] = new EyeFill(
            $this->createColor($innerRed, $innerGreen, $innerBlue),
            $this->createColor($outterRed, $outterGreen, $outterBlue)
        );

        return $this;
    }

    /**
     * Sets the eye color for the provided eye index by providing hex codes.
     *
     * @param int $eyeNumber
     * @param string $innerHex
     * @param string $outterHex
     * @return Generate
     */
    public function eyeColorFromHex(int $eyeNumber, string $outterHex = '#000000', string $innerHex = '#000000'): self
    {
        if (! in_array($eyeNumber, [0, 1, 2])) {
            throw new InvalidArgumentException("\$eyeNumber must be 0, 1, or 2.  {$eyeNumber} is not valid.");
        }

        return $this->eyeColor($eyeNumber, ...$this->hexToRgb($outterHex), ...$this->hexToRgb($innerHex));
    }

    /**
     * @param int $startRed
     * @param int $startGreen
     * @param int $startBlue
     * @param int $endRed
     * @param int $endGreen
     * @param int $endBlue
     * @param string $type
     * @return $this
     */
    public function gradient(int $startRed, int $startGreen, int $startBlue, int $endRed, int $endGreen, int $endBlue, string $type): self
    {
        $type = strtoupper($type);

        if (! in_array($type, ['VERTICAL', 'HORIZONTAL', 'DIAGONAL', 'INVERSE_DIAGONAL', 'RADIAL'])) {
            throw new InvalidArgumentException("\$type must be vertical, horizontal, diagonal, inverse diagonal, radial. {$type} is not a valid gradient style.");
        }

        $this->gradient = new Gradient(
            $this->createColor($startRed, $startGreen, $startBlue),
            $this->createColor($endRed, $endGreen, $endBlue),
            GradientType::$type()
        );

        return $this;
    }

    /**
     * Sets the eye style.
     *
     * @param string $style
     * @return Generate
     */
    public function eye(string $style): self
    {
        if (! in_array($style, ['square', 'circle', 'rounded', 'ring'])) {
            throw new InvalidArgumentException("\$style must be square, rounded or circle. {$style} is not a valid eye style.");
        }

        $this->eyeStyle = $style;

        return $this;
    }

    /**
     * Sets the style of the blocks for the QrCode.
     *
     * @param string $style
     * @param float $size
     * @return Generate
     */
    public function style(string $style, float $size = 0.5): self
    {
        if (! in_array($style, ['square', 'dot', 'round', 'star', 'vertigo'])) {
            throw new InvalidArgumentException("\$style must be square, dot, or round. {$style} is not a valid.");
        }

        if ($style === 'star' || $style === 'vertigo') {
            if ($size <= 0 || $size > 0.5) {
                throw new InvalidArgumentException("\$size must be between 0 and 0,5.  {$size} is not valid.");
            }
        } else {
            if ($size <= 0 || $size > 1) {
                throw new InvalidArgumentException("\$size must be between 0 and 1.  {$size} is not valid.");
            }
        }

        $this->style = $style;
        $this->styleSize = $size;

        return $this;
    }

    /**
     * Sets the encoding for the QrCode.
     * Possible values are
     * ISO-8859-2, ISO-8859-3, ISO-8859-4, ISO-8859-5, ISO-8859-6,
     * ISO-8859-7, ISO-8859-8, ISO-8859-9, ISO-8859-10, ISO-8859-11,
     * ISO-8859-12, ISO-8859-13, ISO-8859-14, ISO-8859-15, ISO-8859-16,
     * SHIFT-JIS, WINDOWS-1250, WINDOWS-1251, WINDOWS-1252, WINDOWS-1256,
     * UTF-16BE, UTF-8, ASCII, GBK, EUC-KR.
     *
     * @param string $encoding
     * @return Generate
     */
    public function encoding(string $encoding): self
    {
        $this->encoding = strtoupper($encoding);

        return $this;
    }

    /**
     * Sets the error correction for the QrCode.
     * L: 7% loss.
     * M: 15% loss.
     * Q: 25% loss.
     * H: 30% loss.
     *
     * @param string $errorCorrection
     * @return Generate
     */
    public function errorCorrection(string $errorCorrection): self
    {
        $errorCorrection = strtoupper($errorCorrection);
        $this->errorCorrection = ErrorCorrectionLevel::$errorCorrection();

        return $this;
    }

    /**
     * Sets the margin of the QrCode.
     *
     * @param int $margin
     * @return Generate
     */
    public function margin(int $margin): self
    {
        $this->margin = $margin;

        return $this;
    }

    /**
     * Fetches the Writer.
     *
     * @param ImageRenderer $renderer
     * @return Writer
     */
    public function getWriter(ImageRenderer $renderer): Writer
    {
        return new Writer($renderer);
    }

    /**
     * Fetches the Image Renderer.
     *
     * @return ImageRenderer
     */
    public function getRenderer(): ImageRenderer
    {
        return new ImageRenderer(
            $this->getRendererStyle(),
            $this->getFormatter()
        );
    }

    /**
     * Returns the Renderer Style.
     *
     * @return RendererStyle
     */
    public function getRendererStyle(): RendererStyle
    {
        return new RendererStyle($this->pixels, $this->margin, $this->getModule(), $this->getEye(), $this->getFill());
    }

    /**
     * Fetches the formatter.
     *
     * @return ImageBackEndInterface
     */
    public function getFormatter(): ImageBackEndInterface
    {
        if ($this->format === 'png') {
            return new ImagickImageBackEnd('png', $this->compressionQuality);
        }

        if ($this->format === 'eps') {
            return new EpsImageBackEnd;
        }

        return new SvgImageBackEnd;
    }

    /**
     * Fetches the module.
     *
     * @return ModuleInterface
     */
    public function getModule(): ModuleInterface
    {
        if ($this->style === 'dot') {
            return new DotsModule($this->styleSize);
        }

        if ($this->style === 'round') {
            return new RoundnessModule($this->styleSize);
        }

        if ($this->style === 'star') {
            return new StarStyle($this->styleSize);
        }

        if ($this->style === 'vertigo') {
            return new VertigoStyle($this->styleSize);
        }

        return SquareModule::instance();
    }

    /**
     * Fetches the eye style.
     *
     * @return EyeInterface
     */
    public function getEye(): EyeInterface
    {
        if ($this->eyeStyle === 'square') {
            return SquareEye::instance();
        }

        if ($this->eyeStyle === 'circle') {
            return SimpleCircleEye::instance();
        }

        if ($this->eyeStyle === 'rounded') {
            return RoundedSquareEye::instance();
        }

        if ($this->eyeStyle === 'ring') {
            return RingEye::instance();
        }

        return new ModuleEye($this->getModule());
    }

    /**
     * Fetches the color fill.
     *
     * @return Fill
     */
    public function getFill(): Fill
    {
        $foregroundColor = $this->color ?? new Rgb(0, 0, 0);
        $backgroundColor = $this->backgroundColor ?? new Rgb(255, 255, 255);
        $eye0 = $this->eyeColors[0] ?? EyeFill::inherit();
        $eye1 = $this->eyeColors[1] ?? EyeFill::inherit();
        $eye2 = $this->eyeColors[2] ?? EyeFill::inherit();

        if ($this->gradient) {
            return Fill::withForegroundGradient($backgroundColor, $this->gradient, $eye0, $eye1, $eye2);
        }

        return Fill::withForegroundColor($backgroundColor, $foregroundColor, $eye0, $eye1, $eye2);
    }

    /**
     * Sets the compression quality
     *
     *
     * @param int $quality
     * @return Generate
     */
    public function setPngCompression(int $quality): static
    {
        if ($quality < 1 || $quality > 100) {
            throw new InvalidArgumentException("\$quality must be between 1 and 100. {$quality} is not valid.");
        }

        $this->compressionQuality = $quality;

        return $this;
    }

    /**
     * Creates a RGB or Alpha channel color.
     *
     * @param int $red
     * @param int $green
     * @param int $blue
     * @param int|null $alpha
     * @return ColorInterface
     */
    private function createColor(int $red, int $green, int $blue, ?int $alpha = null): ColorInterface
    {
        if (is_null($alpha)) {
            return new Rgb($red, $green, $blue);
        }

        return new Alpha($alpha, new Rgb($red, $green, $blue));
    }

    /**
     * Converts a hex color to an array of rgb values.
     *
     * @param string $hex
     * @return array
     */
    private function hexToRgb(string $hex): array
    {
        return (new HexToRgb($hex))->toRGBArray();
    }
}
