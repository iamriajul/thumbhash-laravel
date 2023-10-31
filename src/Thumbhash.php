<?php

namespace Riajul\Thumbhash;

use Intervention\Image\Constraint;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Thumbhash\Thumbhash as ThumbhashLib;
use function Thumbhash\extract_size_and_pixels_with_gd;
use function Thumbhash\extract_size_and_pixels_with_imagick;

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
            })->encode();
        } else {
            $data = $data->encode();
        }

        if ($this->driver === 'imagick') {
            [$width, $height, $rgba] = extract_size_and_pixels_with_imagick($data->getEncoded());
        } else {
            [$width, $height, $rgba] = extract_size_and_pixels_with_gd($data->getEncoded());
        }

        $data->destroy();

        $hashBinary = ThumbhashLib::RGBAToHash($width, $height, $rgba);

        return ThumbhashLib::convertHashToString($hashBinary);
    }
}
