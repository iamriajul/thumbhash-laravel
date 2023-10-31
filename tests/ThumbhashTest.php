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
        $data = [
            [
                'url'  => 'assets/sunrise.jpg',
                'hash' => '1QcSHQRnh493V4dIh4eXh1h4kJUI'
            ],
            [
                'url'  => 'assets/sunset.jpg',
                'hash' => '3PcNNYSFeXh/d3eld0iHZoZgVwh2'
            ],
            [
                'url'  => 'assets/field.jpg',
                'hash' => '3OcRJYB4d3h/iIeHeEh3eIhw6j7A'
            ],
            [
                'url'  => 'assets/fall.jpg',
                'hash' => 'HBkSHYSIeHiPiHh8eJd4eTN0EEQG'
            ],
            [
                'url'  => 'assets/street.jpg',
                'hash' => 'VggKDYAW6lZvdYd6d2iZh/p4CE/j'
            ],
            [
                'url'  => 'assets/mountain.jpg',
                'hash' => '2fcZFIB3iId/h3iJh4aJUJ2V8g'
            ],
            [
                'url'  => 'assets/coast.jpg',
                'hash' => 'IQgSLYZ6iHePh4h1eFeHh4dwgwg3'
            ],

            // Images with transparency
            [
                'url'  => 'assets/firefox.png',
                'hash' => 'YJqGPQw7sFlslqhFafSE+Q6oJ1h2iHB2Rw'
            ],
            [
                'url'  => 'assets/opera.png',
                'hash' => 'mYqDBQQnxnj0JoLYdN7f8JhpuDeHiHdwZw'
            ],
        ];

        $hash = new Thumbhash();

        $this->assertSame(
            'imagick',
            $hash->getDriver(),
        );

        foreach ($data as $item) {
            $this->assertSame(
                $item['hash'],
                $hash->encode(__DIR__ . '/' . $item['url'])
            );
        }
    }
}
