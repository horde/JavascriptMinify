<?php

declare(strict_types=1);

/**
 * Copyright 2017-2026 The Horde Project (http://www.horde.org/)
 *
 * See the enclosed file LICENSE for license information (LGPL). If you
 * did not receive this file, see http://www.horde.org/licenses/lgpl21.
 */

namespace Horde\JavascriptMinify\Test;

use Horde_JavascriptMinify;
use Horde_JavascriptMinify_Null;
use Horde_Log_Handler_Null;
use Horde_Log_Logger;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;
use stdClass;

#[CoversClass(Horde_JavascriptMinify::class)]
class ConstructorTest extends TestCase
{
    public function testConstructorAcceptsString(): void
    {
        $js = 'var x = 1;';
        $minifier = new Horde_JavascriptMinify_Null($js);

        $data = new ReflectionProperty(Horde_JavascriptMinify::class, '_data');
        $this->assertSame($js, $data->getValue($minifier));
    }

    public function testConstructorAcceptsArray(): void
    {
        $files = [
            'https://example.com/one.js' => '/tmp/one.js',
        ];
        $minifier = new Horde_JavascriptMinify_Null($files);

        $data = new ReflectionProperty(Horde_JavascriptMinify::class, '_data');
        $this->assertSame($files, $data->getValue($minifier));
    }

    public function testConstructorRejectsInteger(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Horde_JavascriptMinify_Null(42);
    }

    public function testConstructorRejectsNull(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Horde_JavascriptMinify_Null(null);
    }

    public function testConstructorRejectsObject(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Horde_JavascriptMinify_Null(new stdClass());
    }

    public function testSetOptionsCreatesNullLoggerByDefault(): void
    {
        $minifier = new Horde_JavascriptMinify_Null('var x = 1;');

        $opts = new ReflectionProperty(Horde_JavascriptMinify::class, '_opts');
        $optsValue = $opts->getValue($minifier);

        $this->assertInstanceOf(Horde_Log_Logger::class, $optsValue['logger']);
    }

    public function testSetOptionsAcceptsCustomLogger(): void
    {
        $logger = new Horde_Log_Logger(new Horde_Log_Handler_Null());
        $minifier = new Horde_JavascriptMinify_Null('var x = 1;', ['logger' => $logger]);

        $opts = new ReflectionProperty(Horde_JavascriptMinify::class, '_opts');
        $optsValue = $opts->getValue($minifier);

        $this->assertSame($logger, $optsValue['logger']);
    }

    public function testSetOptionsMergesOptions(): void
    {
        $minifier = new Horde_JavascriptMinify_Null('var x = 1;', ['foo' => 'bar']);
        $minifier->setOptions(['baz' => 'qux']);

        $opts = new ReflectionProperty(Horde_JavascriptMinify::class, '_opts');
        $optsValue = $opts->getValue($minifier);

        $this->assertSame('bar', $optsValue['foo']);
        $this->assertSame('qux', $optsValue['baz']);
    }

    public function testSetOptionsOverridesExistingKeys(): void
    {
        $minifier = new Horde_JavascriptMinify_Null('var x = 1;', ['foo' => 'bar']);
        $minifier->setOptions(['foo' => 'updated']);

        $opts = new ReflectionProperty(Horde_JavascriptMinify::class, '_opts');
        $optsValue = $opts->getValue($minifier);

        $this->assertSame('updated', $optsValue['foo']);
    }

    public function testConstructorPassesOptsToSetOptions(): void
    {
        $minifier = new Horde_JavascriptMinify_Null('var x = 1;', ['custom' => 'value']);

        $opts = new ReflectionProperty(Horde_JavascriptMinify::class, '_opts');
        $optsValue = $opts->getValue($minifier);

        $this->assertSame('value', $optsValue['custom']);
    }
}
