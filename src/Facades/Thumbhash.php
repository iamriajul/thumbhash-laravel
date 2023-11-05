<?php

namespace Riajul\Thumbhash\Facades;

use Illuminate\Support\Facades\Facade;
use Intervention\Image\Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @method static string encode(mixed|string|UploadedFile|Image $data)
 * @method static void setDriver(string $driver)
 * @method static string getDriver()
 * @method static void setResizedImageMaxSize(int $imageMaxSize)
 *
 * @see \Riajul\Thumbhash\Thumbhash
 */
class Thumbhash extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'thumbhash';
    }
}
