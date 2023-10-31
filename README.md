# Thumbhash

[![Testing](https://github.com/bepsvpt/blurhash/actions/workflows/testing.yml/badge.svg)](https://github.com/bepsvpt/blurhash/actions/workflows/testing.yml)
[![Latest Stable Version](https://poser.pugx.org/bepsvpt/blurhash/v/stable)](https://packagist.org/packages/bepsvpt/blurhash)
[![Total Downloads](https://poser.pugx.org/bepsvpt/blurhash/downloads)](https://packagist.org/packages/bepsvpt/blurhash)
[![License](https://poser.pugx.org/bepsvpt/blurhash/license)](https://packagist.org/packages/bepsvpt/blurhash)

A PHP implementation of [Thumbhash](https://github.com/evanw/thumbhash) with Laravel integration.

Thumbhash is a compact representation of a placeholder for an image.

![screenshot](screenshot.png)

<p align="center">Nr8%YLkDR4j[aej]NSaznzjuk9ayR3jYofayj[f6</p>

⚠️ I highly recommend to have Imagick extension installed on your computer. GD extension has only 7 bits of alpha channel resolution, and 127 is transparent, 0 opaque. While the library will still work, you may have different image between platforms. [See on stackoverflow](https://stackoverflow.com/questions/41079110/is-it-possible-to-retrieve-the-alpha-value-of-a-pixel-of-a-png-file-in-the-0-255)

## Supported Laravel Version

8.0 ~ 10.x

## Installation

Install using composer

```shell
composer require iamriajul/thumbhash-laravel
```

Publish config file (Optional for )

```shell
php artisan vendor:publish --provider="Riajul\Thumbhash\ThumbhashServiceProvider"
```

Set up config file on config/thumbhash.php

Done!

## Usage

### Facade

```php
Thumbhash::encode($file);
```

`$file` can be any [Intervention make method](https://image.intervention.io/v2/api/make) acceptable source.

### app helper function

```php
app('thumbhash')
  ->encode(request('file'));
```

## License

Thumbhash is licensed under [The MIT License (MIT).](LICENSE.md)
