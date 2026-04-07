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
use ReflectionProperty;

#[CoversClass(Horde_JavascriptMinify::class)]
class SourcemapTest extends TestCase
{
    public function testSourcemapReturnsNullByDefault(): void
    {
        $minifier = new Horde_JavascriptMinify_Null('var x = 1;');
        $minifier->minify();

        $this->assertNull($minifier->sourcemap());
    }

    public function testSourcemapReturnsNullWhenFileNotReadable(): void
    {
        $files = [
            'https://example.com/one.js' => dirname(__DIR__) . '/fixtures/one.js',
        ];
        $minifier = new Horde_JavascriptMinify_Null($files);
        $minifier->minify();

        $prop = new ReflectionProperty(Horde_JavascriptMinify::class, '_sourcemap');
        $prop->setValue($minifier, '/nonexistent/sourcemap.json');

        $this->assertNull($minifier->sourcemap());
    }

    public function testSourcemapReturnsNullWhenFileIsEmpty(): void
    {
        $files = [
            'https://example.com/one.js' => dirname(__DIR__) . '/fixtures/one.js',
        ];
        $minifier = new Horde_JavascriptMinify_Null($files);
        $minifier->minify();

        $tmpFile = tempnam(sys_get_temp_dir(), 'srcmap');
        file_put_contents($tmpFile, '');

        $prop = new ReflectionProperty(Horde_JavascriptMinify::class, '_sourcemap');
        $prop->setValue($minifier, $tmpFile);

        $this->assertNull($minifier->sourcemap());
        unlink($tmpFile);
    }

    public function testSourcemapParsesValidSourcemapFile(): void
    {
        $fixtureDir = dirname(__DIR__) . '/fixtures';
        $files = [
            'https://example.com/js/one.js' => $fixtureDir . '/one.js',
            'https://example.com/js/two.js' => $fixtureDir . '/two.js',
        ];
        $minifier = new Horde_JavascriptMinify_Null($files);
        $minifier->minify();

        $sourcemapData = json_encode([
            'version' => 3,
            'sources' => [$fixtureDir . '/one.js', $fixtureDir . '/two.js'],
            'mappings' => 'AAAA',
            'sourceRoot' => '/root',
            'file' => 'output.js',
        ]);

        $tmpFile = tempnam(sys_get_temp_dir(), 'srcmap');
        file_put_contents($tmpFile, $sourcemapData);

        $prop = new ReflectionProperty(Horde_JavascriptMinify::class, '_sourcemap');
        $prop->setValue($minifier, $tmpFile);

        $result = $minifier->sourcemap();
        $this->assertNotNull($result);

        $decoded = json_decode($result, true);
        $this->assertSame(3, $decoded['version']);
        $this->assertSame(
            ['https://example.com/js/one.js', 'https://example.com/js/two.js'],
            $decoded['sources']
        );
        $this->assertSame('AAAA', $decoded['mappings']);
        $this->assertArrayNotHasKey('sourceRoot', $decoded);
        $this->assertArrayNotHasKey('file', $decoded);

        unlink($tmpFile);
    }
}
