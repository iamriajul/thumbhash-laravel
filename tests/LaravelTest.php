<?php

namespace Riajul\Thumbhash\Tests;

use Riajul\Thumbhash\Facades\Thumbhash;
use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase;

class LaravelTest extends TestCase
{
    /**
     * Get package providers.
     *
     * @param  Application  $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return ['Riajul\Thumbhash\ThumbhashServiceProvider'];
    }

    /**
     * Get package aliases.
     *
     * @param  Application  $app
     * @return array<string, class-string>
     */
    protected function getPackageAliases($app): array
    {
        return [
            'Thumbhash' => 'Thumbhash\Thumbhash\Facades\Thumbhash',
        ];
    }

    public function testPackageLoaded(): void
    {
        $hash = 'fCgGDwTnqHiHd4l/hJaHmHmHhoEHGHgA';

        $this->assertSame(
            'imagick',
            Thumbhash::getDriver()
        );

        $this->assertSame(
            $hash,
            Thumbhash::encode(__DIR__ . '/images/5.png'),
        );
    }
}
