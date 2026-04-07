<?php

declare(strict_types=1);

/**
 * Copyright 2017-2026 The Horde Project (http://www.horde.org/)
 *
 * See the enclosed file LICENSE for license information (LGPL). If you
 * did not receive this file, see http://www.horde.org/licenses/lgpl21.
 */

namespace Horde\JavascriptMinify\Test;

use Horde_JavascriptMinify_Yui;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Horde_JavascriptMinify_Yui::class)]
class YuiSetOptionsTest extends TestCase
{
    public function testSetOptionsRequiresJavaOption(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('java');

        new Horde_JavascriptMinify_Yui('var x = 1;', [
            'yui' => '/usr/bin/yui.jar',
        ]);
    }

    public function testSetOptionsRequiresYuiOption(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('yui');

        new Horde_JavascriptMinify_Yui('var x = 1;', [
            'java' => '/usr/bin/java',
        ]);
    }

    public function testSetOptionsAcceptsValidOptions(): void
    {
        $minifier = new Horde_JavascriptMinify_Yui('var x = 1;', [
            'java' => '/usr/bin/java',
            'yui' => '/usr/bin/yui.jar',
        ]);

        $this->assertInstanceOf(Horde_JavascriptMinify_Yui::class, $minifier);
    }

    public function testSetOptionsAcceptsOptionalCmdline(): void
    {
        $minifier = new Horde_JavascriptMinify_Yui('var x = 1;', [
            'java' => '/usr/bin/java',
            'yui' => '/usr/bin/yui.jar',
            'cmdline' => '--charset utf-8',
        ]);

        $this->assertInstanceOf(Horde_JavascriptMinify_Yui::class, $minifier);
    }
}
