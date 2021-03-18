<?php
/**
 * Copyright 2017 Horde LLC (http://www.horde.org/)
 *
 * See the enclosed file LICENSE for license information (LGPL). If you
 * did not receive this file, see http://www.horde.org/licenses/lgpl21.
 *
 * @author     Jan Schneider <jan@horde.org>
 * @category   Horde
 * @license    http://www.horde.org/licenses/lgpl21 LGPL 2.1
 * @package    JavascriptMinify
 * @subpackage UnitTests
 */
namespace Horde\JavascriptMinify;
use Horde_JavascriptMinify_TestBase as TestBase;

/**
 * Tests the YUI backend.
 *
 * @author     Jan Schneider <jan@horde.org>
 * @category   Horde
 * @copyright  2017 Horde LLC
 * @license    http://www.horde.org/licenses/lgpl21 LGPL 2.1
 * @package    JavascriptMinify
 * @subpackage UnitTests
 */
class YuiTest extends TestBase
{
    protected $_config;

    public function setUp(): void
    {
        $this->_config = self::getConfig(
            'JAVASCRIPTMINIFY_YUI_TEST_CONFIG',
            __DIR__
        );
        if (!$this->_config ||
            empty($this->_config['javascriptminify']['yui'])) {
            $this->markTestSkipped('YUI compressor not configured');
        }
    }

    public function testMinify()
    {
        $this->_minify();
    }

    public function testToString()
    {
        $this->_toString();
    }

    protected function _getMinifier($files = false)
    {
        return new Horde_JavascriptMinify_Yui(
            $this->_getFixture($files),
            $this->_config['javascriptminify']['yui']
        );
    }
}
