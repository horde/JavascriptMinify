<?php

declare(strict_types=1);

/**
 * Copyright 2017-2026 The Horde Project (http://www.horde.org/)
 *
 * See the enclosed file LICENSE for license information (LGPL). If you
 * did not receive this file, see http://www.horde.org/licenses/lgpl21.
 */

namespace Horde\JavascriptMinify\Test;

use Horde_JavascriptMinify_Exception;
use Horde_JavascriptMinify_Null;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Horde_JavascriptMinify_Null::class)]
class NullTest extends TestCase
{
    private string $fixtureDir;

    protected function setUp(): void
    {
        $this->fixtureDir = dirname(__DIR__) . '/fixtures';
    }

    public function testMinifyWithStringReturnsUnmodifiedString(): void
    {
        $js = "var x = 1;\nvar y = 2;";
        $minifier = new Horde_JavascriptMinify_Null($js);

        $this->assertSame($js, $minifier->minify());
    }

    public function testMinifyWithEmptyStringReturnsEmptyString(): void
    {
        $minifier = new Horde_JavascriptMinify_Null('');

        $this->assertSame('', $minifier->minify());
    }

    public function testMinifyWithFilesReturnsConcatenatedContents(): void
    {
        $files = [
            'https://example.com/one.js' => $this->fixtureDir . '/one.js',
            'https://example.com/two.js' => $this->fixtureDir . '/two.js',
        ];
        $minifier = new Horde_JavascriptMinify_Null($files);
        $result = $minifier->minify();

        $expected = file_get_contents($this->fixtureDir . '/one.js') . "\n"
            . file_get_contents($this->fixtureDir . '/two.js') . "\n";

        $this->assertSame($expected, $result);
    }

    public function testMinifyWithSingleFileReturnsFileContentsWithNewline(): void
    {
        $files = [
            'https://example.com/one.js' => $this->fixtureDir . '/one.js',
        ];
        $minifier = new Horde_JavascriptMinify_Null($files);
        $result = $minifier->minify();

        $expected = file_get_contents($this->fixtureDir . '/one.js') . "\n";
        $this->assertSame($expected, $result);
    }

    public function testMinifyWithUnreadableFileThrowsException(): void
    {
        $files = [
            'https://example.com/missing.js' => '/nonexistent/path/missing.js',
        ];
        $minifier = new Horde_JavascriptMinify_Null($files);

        $this->expectException(Horde_JavascriptMinify_Exception::class);
        $minifier->minify();
    }

    public function testToStringReturnsMinifyResult(): void
    {
        $js = 'var x = 1;';
        $minifier = new Horde_JavascriptMinify_Null($js);

        $this->assertSame($minifier->minify(), (string) $minifier);
    }

    public function testToStringWithFilesReturnsMinifyResult(): void
    {
        $files = [
            'https://example.com/one.js' => $this->fixtureDir . '/one.js',
        ];
        $minifier = new Horde_JavascriptMinify_Null($files);

        $this->assertSame($minifier->minify(), (string) $minifier);
    }
}
