<?php

return [

    /*
     * Driver to use.
     * Supported: "imagick", "gd"
     * Default: "imagick"
     * GD is not recommended.
     * ⚠️ I highly recommend to use Imagick extension.
     * GD extension has only 7 bits of alpha channel resolution, and 127 is transparent, 0 opaque.
     * While the library will still work, you may have different image between platforms.
     * [See on stackoverflow](https://stackoverflow.com/questions/41079110/is-it-possible-to-retrieve-the-alpha-value-of-a-pixel-of-a-png-file-in-the-0-255)
     */

    'driver' => 'imagick',

    /*
     * Resize image max width or height.
     *
     * When encoding the image, image will resize to
     * small one to optimize performance. It is not
     * max is 100. configuring this higher than 100 will throw an exception.
     *
     */

    'resized-image-max-size' => 100,

];
