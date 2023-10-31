<?php

namespace Riajul\Thumbhash;

use Intervention\Image\Constraint;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use Intervention\Image\Imagick\Driver as ImagickDriver;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Thumbhash\Thumbhash as ThumbhashLib;

class Thumbhash
{
    protected string $driver;
    protected int $imageMaxSize;

    /**
     * Thumbhash constructor.
     */
    public function __construct(
        string $driver = 'imagick',
        int $resizedMaxSize = 100,
    ) {
        $this->setDriver($driver);
        $this->setResizedImageMaxSize($resizedMaxSize);
    }

    /**
     * Set resized image max width.
     */
    public function setResizedImageMaxSize(int $imageMaxSize): self
    {
        if ($imageMaxSize > 100) {
            throw new \InvalidArgumentException('Image max size must be less than or equal to 100.');
        }

        $this->imageMaxSize = $imageMaxSize;

        return $this;
    }

    /**
     * Set driver.
     */
    public function setDriver(string $driver): self {
        if (!in_array($driver, ['imagick', 'gd'])) {
            throw new \InvalidArgumentException('Driver must be imagick or gd.');
        }

        if (!extension_loaded($driver)) {
            throw new \InvalidArgumentException("Driver $driver is not installed.");
        }

        $this->driver = $driver;

        return $this;
    }

    /**
     * @return string
     */
    public function getDriver(): string
    {
        return $this->driver;
    }

    /**
     * Encode an image to thumbhash string - base64 encoded.
     *
     * @param string|resource|Image|UploadedFile $data
     * @throws \ImagickException
     */
    public function encode(mixed $data): string
    {
        $imageManager = new ImageManager([
            'driver' => $this->driver,
        ]);

        $data = $imageManager->make($data);

        // Resize the image to lower resolution. max 100x100.
        $originalWidth = $data->width();
        $originalHeight = $data->height();
        if ($originalWidth >  $this->imageMaxSize || $originalHeight > $this->imageMaxSize) {
            $scale = $this->imageMaxSize / max($originalWidth, $originalHeight);
            $newWidth = $originalWidth * $scale;
            $newHeight = $originalHeight * $scale;
            $data = $data->resize($newWidth, $newHeight, function (Constraint $constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        $width = $data->width();
        $height = $data->height();

        // The pixels in the input image, row-by-row. Must have w*h*4 elements.
        $rgba = [];

        if ($data->getDriver() instanceof ImagickDriver) {
            /**
             * @var \Imagick $imagickImage
             */
            $imagickImage = $data->getCore();
            $rgba = $imagickImage->exportImagePixels(0, 0, $width, $height, 'RGBA', \Imagick::PIXEL_CHAR);
        } else {
            /**
             * @var \GdImage $gdImage
             */
            $gdImage = $data->getCore();
            $rgba = $this->gdImageToPixelsArray($gdImage);
        }

        $data->destroy();

        $hashBinary = ThumbhashLib::RGBAToHash($width, $height, $rgba);

        return ThumbhashLib::convertHashToString($hashBinary);
    }

    private function gdImageToPixelsArray(\GdImage $gdImage): array
    {
        // Get the width and height of the image
        $width = imagesx($gdImage);
        $height = imagesy($gdImage);

        // Create a new image resource to store the RGBA values
        $rgbaImage = imagecreatetruecolor($width, $height);

        // Initialize an array to store the RGBA pixel values
        $rgba = [];

        // Loop through the image to extract the RGBA values
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $color = imagecolorat($gdImage, $x, $y);
                $red = ($color >> 16) & 0xFF;
                $green = ($color >> 8) & 0xFF;
                $blue = $color & 0xFF;
                $alpha = 127 - (($color >> 24) & 0xFF) / 2; // Adjust alpha for GD

                $rgba[] = $red;
                $rgba[] = $green;
                $rgba[] = $blue;
                $rgba[] = $alpha;

                // Set the RGBA values to the new image resource
                $rgbaColor = imagecolorallocatealpha($rgbaImage, $red, $green, $blue, $alpha);
                imagesetpixel($rgbaImage, $x, $y, $rgbaColor);
            }
        }

        return $rgba;
    }
}
