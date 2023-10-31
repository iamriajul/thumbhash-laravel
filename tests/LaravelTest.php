<?php

namespace Riajul\Thumbhash\Tests;

use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase;
use Riajul\Thumbhash\Facades\Thumbhash;
use Riajul\Thumbhash\ThumbhashServiceProvider;

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
        return [ThumbhashServiceProvider::class];
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
            'Thumbhash' => Thumbhash::class,
        ];
    }

    public function testPackageLoaded(): void
    {
        $hash = '1QcSHQRnh493V4dIh4eXh1h4kJUI';

        $this->assertSame(
            'imagick',
            Thumbhash::getDriver()
        );

        $this->assertSame(
            $hash,
            Thumbhash::encode(__DIR__ . '/assets/sunrise.jpg'),
        );

        // Test GD

        Thumbhash::setDriver('gd');

        $this->assertSame(
            'gd',
            Thumbhash::getDriver()
        );

        $this->assertSame(
            $hash,
            Thumbhash::encode(__DIR__ . '/assets/sunrise.jpg'),
        );
    }
}
