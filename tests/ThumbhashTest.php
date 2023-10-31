<?php

namespace Riajul\Thumbhash\Tests;

use Riajul\Thumbhash\Thumbhash;
use PHPUnit\Framework\TestCase;

/**
 * These tests are only Imagick compatible.
 * If you use GD driver, than results will unexpected.
 */
class ThumbhashTest extends TestCase
{
    public function testEncode(): void
    {
        $hash = new Thumbhash();

        $this->assertSame(
            'imagick',
            $hash->getDriver()
        );

        $this->assertSame(
            'm5uDBQAS1wt3iIemifd3h4CIKUd5iZBoSQ',
            $hash->encode(__DIR__ . '/images/1.png')
        );

        $this->assertSame(
            '2quDAYATh4iIeI+HiH+I95eYlnigqIY',
            $hash->encode(__DIR__ . '/images/2.png')
        );

        $this->assertSame(
            'm5uDBQADxPyIdm6IhpSKj/h3fGh8jtBVTA',
            $hash->encode(__DIR__ . '/images/3.png')
        );

        $this->assertSame(
            '2quDAYAm9bZweIeXcGf+p6eXcHiTaaY',
            $hash->encode(__DIR__ . '/images/4.png')
        );
    }

    public function testEncodeDifferentImageWidth(): void
    {
        $hash = new Thumbhash();

        $this->assertSame(
            'imagick',
            $hash->getDriver()
        );

        $this->assertSame(
            '2quDAYAm9bZweIeXcGf+p6eXcHiTaaY',
            $hash->encode(__DIR__ . '/images/4.png')
        );

        $hash->setResizedImageMaxSize(48);

        $this->assertSame(
            '2quDAYAmeHh3eH9peI93+KeXcHiTaaY',
            $hash->encode(__DIR__ . '/images/4.png')
        );

        $hash->setResizedImageMaxSize(32);

        $this->assertSame(
            '2quDAYAmTHmId49oh8+E+KencHiTaZY',
            $hash->encode(__DIR__ . '/images/4.png')
        );

        $hash->setResizedImageMaxSize(96);

        $this->assertSame(
            '2quDAYAmeHiIeHB4dY+HCKeXcHiTaaY',
            $hash->encode(__DIR__ . '/images/4.png')
        );
    }

    public function testEncodeWithGifFormat(): void
    {
        $hash = new Thumbhash();

        $this->assertSame(
            'imagick',
            $hash->getDriver()
        );

        $this->assertSame(
            'KccFVYjQSZcVZ3lwdVnGi3VwSQiG',
            $hash->encode(__DIR__ . '/images/6.gif')
        );
    }
}
