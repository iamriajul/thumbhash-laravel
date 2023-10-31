<?php

namespace Riajul\Thumbhash;

use GdImage;
use Imagick;
use ImagickException;
use Intervention\Image\Image;
use Intervention\Image\Facades\Image as ImageFacade;
use Intervention\Image\Imagick\Driver as ImagickDriver;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Thumbhash\Thumbhash as ThumbhashLib;

class Thumbhash
{
    protected int $imageMaxSize;

    /**
     * Thumbhash constructor.
     */
    public function __construct(
        int $resizedMaxSize = 100
    ) {
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
     * Encode an image to thumbhash string - base64 encoded.
     *
     * @param string|resource|Image|UploadedFile $data
     * @throws ImagickException
     */
    public function encode(mixed $data): string
    {
        $data = ImageFacade::make($data);

        // Prefer Imagick driver if available.
        if (extension_loaded('imagick') && !$data->getDriver() instanceof ImagickDriver) {
            $data->setDriver(new ImagickDriver());
        }

        // Resize the image to lower resolution. max 100x100.
        if ($data->width() > $this->imageMaxSize) {
            $data->resize($this->imageMaxSize, null, function ($constraint) {
                $constraint->aspectRatio();
            });
        } else if ($data->height() > $this->imageMaxSize) {
            $data->resize(null, $this->imageMaxSize, function ($constraint) {
                $constraint->aspectRatio();
            });
        }

        $width = $data->width();
        $height = $data->height();

        // The pixels in the input image, row-by-row. Must have w*h*4 elements.
        $rgba = [];

        if (extension_loaded('imagick')) {
            /**
             * @var Imagick $imagickImage
             */
            $imagickImage = $data->getCore();
            $rgba = $imagickImage->exportImagePixels(0, 0, $width, $height, 'RGBA', \Imagick::PIXEL_INTEGER);
        } else {
            /**
             * @var GdImage $gdImage
             */
            $gdImage = $data->getCore();

            for ($y = 0; $y < $height; ++$y) {
                $item = [];
                for ($x = 0; $x < $width; ++$x) {
                    $item = imagecolorat($gdImage, $x, $y);
                }
                $rgba[] = $item;
            }
        }

        $hashBinary = ThumbhashLib::RGBAToHash($width, $height, $rgba);

        return ThumbhashLib::convertHashToString($hashBinary);
    }
}
