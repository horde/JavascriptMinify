<?php

declare(strict_types=1);

/**
 * Copyright 2017-2026 The Horde Project (http://www.horde.org/)
 *
 * See the enclosed file LICENSE for license information (LGPL). If you
 * did not receive this file, see http://www.horde.org/licenses/lgpl21.
 */

namespace Horde\JavascriptMinify\Test;

use Horde_JavascriptMinify_Uglifyjs;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Horde_JavascriptMinify_Uglifyjs::class)]
class UglifyjsSetOptionsTest extends TestCase
{
    public function testSetOptionsRequiresUglifyjsOption(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('uglifyjs');

        new Horde_JavascriptMinify_Uglifyjs('var x = 1;');
    }

    public function testSetOptionsAcceptsValidOptions(): void
    {
        $minifier = new Horde_JavascriptMinify_Uglifyjs('var x = 1;', [
            'uglifyjs' => '/usr/bin/uglifyjs',
        ]);

        $this->assertInstanceOf(Horde_JavascriptMinify_Uglifyjs::class, $minifier);
    }

    public function testSetOptionsAcceptsOptionalCmdline(): void
    {
        $minifier = new Horde_JavascriptMinify_Uglifyjs('var x = 1;', [
            'uglifyjs' => '/usr/bin/uglifyjs',
            'cmdline' => '--compress --mangle',
        ]);

        $this->assertInstanceOf(Horde_JavascriptMinify_Uglifyjs::class, $minifier);
    }

    public function testSetOptionsAcceptsOptionalSourcemap(): void
    {
        $minifier = new Horde_JavascriptMinify_Uglifyjs('var x = 1;', [
            'uglifyjs' => '/usr/bin/uglifyjs',
            'sourcemap' => 'https://example.com/sourcemap',
        ]);

        $this->assertInstanceOf(Horde_JavascriptMinify_Uglifyjs::class, $minifier);
    }
}
