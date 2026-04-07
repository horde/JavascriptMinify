<?php

declare(strict_types=1);

/**
 * Copyright 2017-2026 The Horde Project (http://www.horde.org/)
 *
 * See the enclosed file LICENSE for license information (LGPL). If you
 * did not receive this file, see http://www.horde.org/licenses/lgpl21.
 */

namespace Horde\JavascriptMinify\Test;

use Horde_JavascriptMinify_Closure;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Horde_JavascriptMinify_Closure::class)]
class ClosureSetOptionsTest extends TestCase
{
    public function testSetOptionsRequiresJavaOption(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('java');

        new Horde_JavascriptMinify_Closure('var x = 1;', [
            'closure' => '/usr/bin/closure',
        ]);
    }

    public function testSetOptionsRequiresClosureOption(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('closure');

        new Horde_JavascriptMinify_Closure('var x = 1;', [
            'java' => '/usr/bin/java',
        ]);
    }

    public function testSetOptionsAcceptsValidOptions(): void
    {
        $minifier = new Horde_JavascriptMinify_Closure('var x = 1;', [
            'java' => '/usr/bin/java',
            'closure' => '/usr/bin/closure',
        ]);

        $this->assertInstanceOf(Horde_JavascriptMinify_Closure::class, $minifier);
    }

    public function testSetOptionsAcceptsOptionalCmdline(): void
    {
        $minifier = new Horde_JavascriptMinify_Closure('var x = 1;', [
            'java' => '/usr/bin/java',
            'closure' => '/usr/bin/closure',
            'cmdline' => '--compilation_level ADVANCED',
        ]);

        $this->assertInstanceOf(Horde_JavascriptMinify_Closure::class, $minifier);
    }

    public function testSetOptionsAcceptsOptionalSourcemap(): void
    {
        $minifier = new Horde_JavascriptMinify_Closure('var x = 1;', [
            'java' => '/usr/bin/java',
            'closure' => '/usr/bin/closure',
            'sourcemap' => 'https://example.com/sourcemap',
        ]);

        $this->assertInstanceOf(Horde_JavascriptMinify_Closure::class, $minifier);
    }
}
