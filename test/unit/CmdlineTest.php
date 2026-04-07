<?php

declare(strict_types=1);

/**
 * Copyright 2017-2026 The Horde Project (http://www.horde.org/)
 *
 * See the enclosed file LICENSE for license information (LGPL). If you
 * did not receive this file, see http://www.horde.org/licenses/lgpl21.
 */

namespace Horde\JavascriptMinify\Test;

use Horde_JavascriptMinify_Util_Cmdline;
use Horde_Log_Exception;
use Horde_Log_Handler_Null;
use Horde_Log_Logger;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Horde_JavascriptMinify_Util_Cmdline::class)]
class CmdlineTest extends TestCase
{
    private function createNullLogger(): Horde_Log_Logger
    {
        return new Horde_Log_Logger(new Horde_Log_Handler_Null());
    }

    public function testRunCmdReturnsCommandOutput(): void
    {
        $cmdline = new Horde_JavascriptMinify_Util_Cmdline();

        $input = 'hello world';
        $result = $cmdline->runCmd($input, 'cat', $this->createNullLogger());

        $this->assertSame($input, $result);
    }

    public function testRunCmdReturnsEmptyStringForEmptyInput(): void
    {
        $cmdline = new Horde_JavascriptMinify_Util_Cmdline();

        $result = $cmdline->runCmd('', 'cat', $this->createNullLogger());

        $this->assertSame('', $result);
    }

    public function testRunCmdPassesLargeInput(): void
    {
        $cmdline = new Horde_JavascriptMinify_Util_Cmdline();

        $input = str_repeat('var x = 1;', 1000);
        $result = $cmdline->runCmd($input, 'cat', $this->createNullLogger());

        $this->assertSame($input, $result);
    }

    /**
     * The source code uses the string 'WARN' as a log level, which
     * Horde_Log_Logger does not recognize. This test documents the
     * current (broken) behavior: stderr output causes a Horde_Log_Exception.
     */
    public function testRunCmdWithStderrThrowsLogException(): void
    {
        $cmdline = new Horde_JavascriptMinify_Util_Cmdline();

        $this->expectException(Horde_Log_Exception::class);
        $this->expectExceptionMessage('Bad log level');

        $cmdline->runCmd('', 'echo "test error" >&2', $this->createNullLogger());
    }

    public function testRunCmdExecutesCommandTransformation(): void
    {
        $cmdline = new Horde_JavascriptMinify_Util_Cmdline();

        $result = $cmdline->runCmd('hello', 'tr "a-z" "A-Z"', $this->createNullLogger());

        $this->assertSame('HELLO', $result);
    }
}
