<?php

namespace Backend\Modules\MediaLibrary\Component;

/**
 * Thumbnail settings
 *
 * We use this component as a helper to create an image thumbnail.
 */
class ImageSettings
{
    /**
     * @var int
     */
    protected $width;

    /**
     * @var int
     */
    protected $height;

    /**
     * @var ImageTransformationMethod
     */
    protected $transformationMethod;

    /**
     * @var integer
     */
    protected $quality = 100;

    /**
     * Construct
     *
     * @param ImageTransformationMethod $transformationMethod "crop" or "resize"
     * @param int|null $width
     * @param int|null $height
     * @param int $quality
     * @throws \Exception
     */
    private function __construct(
        ImageTransformationMethod $transformationMethod,
        int $width = null,
        int $height = null,
        int $quality = 100
    ) {
        $this->transformationMethod = $transformationMethod;
        $this->setWidth($width);
        $this->setHeight($height);
        $this->setQuality($quality);
    }

    /**
     * Create
     *
     * @param ImageTransformationMethod $transformationMethod "crop" or "resize"
     * @param int|null $width
     * @param int|null $height
     * @param int $quality
     * @return ImageSettings
     */
    public static function create(
        ImageTransformationMethod $transformationMethod,
        int $width = null,
        int $height = null,
        int $quality = 100
    ) : ImageSettings{
        return new self(
            $transformationMethod,
            $width,
            $height,
            $quality
        );
    }

    /**
     * From string
     *
     * @param string $value
     * @return ImageSettings
     * @throws \Exception
     */
    public static function fromString(string $value): ImageSettings
    {
        // Divide value into width and height
        list($width, $heightAndMore) = explode('x', $value);

        // Redefine
        $width = (int) $width;

        if (strpos($heightAndMore, '-') !== false) {
            $height = (int) trim(strstr($heightAndMore, '-', true), '-');

            // Define quality
            $quality = (int) ltrim(substr($heightAndMore, -3), '-');
        } else {
            $height = (int) $heightAndMore;
            $quality = 100;
        }

        // Define method, by default we use "resize"
        $transformationMethod = ImageTransformationMethod::fromString($value);

        if ($quality === 0) {
            $quality = 100;
        }

        return new self(
            $transformationMethod,
            $width,
            $height,
            $quality
        );
    }

    /**
     * Gets the value of width.
     *
     * @return integer
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * Gets the value of height.
     *
     * @return integer
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * Get method
     *
     * @return ImageTransformationMethod
     */
    public function getTransformationMethod(): ImageTransformationMethod
    {
        return $this->transformationMethod;
    }

    /**
     * Get resolution ("widthx", "xheight" or "widthxheight")
     *
     * @return string
     */
    public function getResolution(): string
    {
        $resolution = '';

        if ($this->getWidth() !== 0) {
            $resolution .= $this->getWidth();
        }

        $resolution .= 'x';

        if ($this->getHeight() !== 0) {
            $resolution .= $this->getHeight();
        }

        return $resolution;
    }

    /**
     * Gets the value of quality.
     *
     * @return integer
     */
    public function getQuality(): int
    {
        return $this->quality;
    }

    /**
     * Set height
     *
     * @param int|null $height
     * @return ImageSettings
     * @throws \Exception
     */
    protected function setHeight(int $height): ImageSettings
    {
        if ($height < 0) {
            throw new \Exception(
                'The height should be higher then or equal to 0.'
            );
        }

        $this->height = $height;
        return $this;
    }

    /**
     * Set quality
     *
     * @param int $quality
     * @return ImageSettings
     * @throws \Exception
     */
    protected function setQuality(int $quality): ImageSettings
    {
        if ($quality < 0 || $quality > 100) {
            throw new \Exception(
                'The quality must be between 0 and 100.'
            );
        }

        $this->quality = $quality;
        return $this;
    }

    /**
     * Set width
     *
     * @param int|null $width
     * @return ImageSettings
     * @throws \Exception
     */
    protected function setWidth(int $width): ImageSettings
    {
        if ($width < 0) {
            throw new \Exception(
                'The width should be higher then or equal to 0.'
            );
        }

        $this->width = $width;
        return $this;
    }

    /**
     * To string - This will return the folder name
     *
     * @return string
     */
    public function toString(): string
    {
        // Add resolution (width - height)
        $value = $this->getResolution();

        // Add tranformation method
        $value .= $this->getTransformationMethod()->toString();

        if ($this->getQuality() < 100) {
            // Add quality
            $value .= '-' . $this->getQuality();
        }

        return $value;
    }
}
