<?php

declare(strict_types=1);

/**
 * Copyright 2017-2026 The Horde Project (http://www.horde.org/)
 *
 * See the enclosed file LICENSE for license information (LGPL). If you
 * did not receive this file, see http://www.horde.org/licenses/lgpl21.
 */

namespace Horde\JavascriptMinify\Test\Integration;

use Horde_JavascriptMinify_Closure;
use PHPUnit\Framework\Attributes\CoversNothing;

#[CoversNothing]
class ClosureTest extends IntegrationTestBase
{
    private array $config;

    protected function setUp(): void
    {
        $config = $this->loadConfig('JAVASCRIPTMINIFY_CLOSURE_TEST_CONFIG');
        if ($config === null || empty($config['javascriptminify']['closure'])) {
            $this->markTestSkipped('Closure compressor not configured');
        }
        $this->config = $config['javascriptminify']['closure'];
    }

    public function testMinifyString(): void
    {
        $fixture = $this->getFixtureString();
        $minifier = new Horde_JavascriptMinify_Closure($fixture, $this->config);

        $this->assertMinifies($fixture, $minifier->minify());
    }

    public function testMinifyFiles(): void
    {
        $files = $this->getFixtureFiles();
        $minifier = new Horde_JavascriptMinify_Closure($files, $this->config);
        $minified = $minifier->minify();
        $original = implode('', array_map('file_get_contents', $files));

        $this->assertMinifies($original, $minified);
    }

    public function testSourcemap(): void
    {
        $opts = array_merge($this->config, [
            'sourcemap' => 'https://www.example.com/js/sourcemap',
        ]);
        $minifier = new Horde_JavascriptMinify_Closure(
            $this->getFixtureFiles(),
            $opts
        );
        $minifier->minify();

        $sourcemap = $minifier->sourcemap();
        $this->assertNotEmpty($sourcemap);
        $this->assertNotEmpty(json_decode($sourcemap));
    }

    public function testToString(): void
    {
        $minifier = new Horde_JavascriptMinify_Closure(
            $this->getFixtureString(),
            $this->config
        );

        $this->assertSame($minifier->minify(), (string) $minifier);
    }
}
