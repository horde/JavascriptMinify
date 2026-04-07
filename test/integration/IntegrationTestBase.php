<?php

declare(strict_types=1);

/**
 * Copyright 2017-2026 The Horde Project (http://www.horde.org/)
 *
 * See the enclosed file LICENSE for license information (LGPL). If you
 * did not receive this file, see http://www.horde.org/licenses/lgpl21.
 */

namespace Horde\JavascriptMinify\Test\Integration;

use PHPUnit\Framework\TestCase;

abstract class IntegrationTestBase extends TestCase
{
    protected function getFixtureString(): string
    {
        return <<<'JAVASCRIPT'
            /**
             * Some example code.
             *
             */
            var Foo = {
                doit: function(foo)
                {
                    var test, xyz = 1;

                    this.callme();
                    xyz++;
                    test = 'Bar';
                    alert(test + foo);
                }
            };

            Foo.doit("Boo");
            JAVASCRIPT;
    }

    protected function getFixtureFiles(): array
    {
        $fixtureDir = dirname(__DIR__) . '/fixtures';

        return [
            'https://www.example.com/js/one.js' => $fixtureDir . '/one.js',
            'https://www.example.com/js/two.js' => $fixtureDir . '/two.js',
        ];
    }

    protected function loadConfig(string $envVar): ?array
    {
        $configFile = getenv($envVar);
        if ($configFile === false || !is_readable($configFile)) {
            return null;
        }

        return require $configFile;
    }

    protected function assertMinifies(string $original, string $minified): void
    {
        $this->assertNotEmpty($minified);
        $this->assertLessThan(strlen($original), strlen($minified));
    }
}
