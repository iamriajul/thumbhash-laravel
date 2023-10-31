<?php

namespace Bepsvpt\Blurhash\Tests;

use Riajul\Thumbhash\Thumbhash;
use PHPUnit\Framework\TestCase;

class ThumbhashTest extends TestCase
{
    public function testEncode(): void
    {
        $hash = new Thumbhash();

        $this->assertSame(
            'm5uDBQASyz+XiWrFZweHBqxUKTd5eZBpSQ',
            $hash->encode(__DIR__ . '/images/1.png')
        );

        $this->assertSame(
            '2quDAYATz1uJhwFluQ9DtJeYlnigqIY',
            $hash->encode(__DIR__ . '/images/2.png')
        );

        $this->assertSame(
            'm5uDBQADe/VYeWhYefOPVgF7jGh7jsBVTA',
            $hash->encode(__DIR__ . '/images/3.png')
        );
    }

//    public function testEncodeDifferentImageWidth(): void
//    {
//        $hash = new Thumbhash();
//
//        $this->assertSame(
//            'LxTP#UtRe9t7*Jj[f6kCh0enene.',
//            $hash->encode(__DIR__ . '/images/4.png')
//        );
//
//        $hash->setResizedImageMaxWidth(48);
//
//        $this->assertSame(
//            'LyTPVWtRe.t7.mkCfkkWh0enene.',
//            $hash->encode(__DIR__ . '/images/4.png')
//        );
//
//        $hash->setResizedImageMaxWidth(32);
//
//        $this->assertSame(
//            'L*TOwtxaeTxu?^kCf6kWhKe.enf6',
//            $hash->encode(__DIR__ . '/images/4.png')
//        );
//
//        $hash->setResizedImageMaxWidth(96);
//
//        $this->assertSame(
//            'LrTQ13ozeTo}*Jfkf6f+h0enemen',
//            $hash->encode(__DIR__ . '/images/4.png')
//        );
//    }

    public function testEncodeWithGifFormat(): void
    {
        $hash = new Thumbhash();

        $this->assertSame(
            'KccFVYjQSZcVZ3lwdVm2i3VwSQiG',
            $hash->encode(__DIR__ . '/images/6.gif')
        );
    }
}
