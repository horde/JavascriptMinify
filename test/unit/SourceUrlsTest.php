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
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * Tests the protected _sourceUrls() method via a test subclass.
 */
#[CoversClass(Horde_JavascriptMinify::class)]
class SourceUrlsTest extends TestCase
{
    public function testSourceUrlsEmptyForStringInput(): void
    {
        $minifier = new class ('var x = 1;') extends Horde_JavascriptMinify_Null {
            public function getSourceUrls(): string
            {
                return $this->_sourceUrls();
            }
        };

        $this->assertSame('', $minifier->getSourceUrls());
    }

    public function testSourceUrlsListedForFileInput(): void
    {
        $files = [
            'https://example.com/js/one.js' => dirname(__DIR__) . '/fixtures/one.js',
            'https://example.com/js/two.js' => dirname(__DIR__) . '/fixtures/two.js',
        ];

        $minifier = new class ($files) extends Horde_JavascriptMinify_Null {
            public function getSourceUrls(): string
            {
                return $this->_sourceUrls();
            }
        };

        $result = $minifier->getSourceUrls();

        $this->assertStringContainsString('// @source: https://example.com/js/one.js', $result);
        $this->assertStringContainsString('// @source: https://example.com/js/two.js', $result);
    }

    public function testSourceUrlsFormatPerUrl(): void
    {
        $files = [
            'https://example.com/app.js' => dirname(__DIR__) . '/fixtures/one.js',
        ];

        $minifier = new class ($files) extends Horde_JavascriptMinify_Null {
            public function getSourceUrls(): string
            {
                return $this->_sourceUrls();
            }
        };

        $this->assertSame(
            "\n// @source: https://example.com/app.js",
            $minifier->getSourceUrls()
        );
    }
}
