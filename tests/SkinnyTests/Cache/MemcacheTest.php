<?php
/**
 * Skinny - a straight forward no-nonsense PHP library
 *
 * @author      Roger Thomas <roger.thomas@rogerethomas.com>
 * @copyright   2013 Roger Thomas
 * @link        http://www.rogerethomas.com
 * @license     http://www.rogerethomas.com/license
 * @since       2.0
 * @package     Skinny
 *
 * MIT LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
namespace SkinnyTests\Cache;

use PHPUnit\Framework\TestCase;
use Skinny\Cache\MemcacheService;

class MemcacheTest extends TestCase
{
    /**
     * @var MemcacheService|null
     */
    private $memcache = null;

    /**
     * @var MemcacheService|null
     */
    private $memcacheInvalid = null;

    private $prefix = 'SKINNY_TESTS_';

    public function setUp()
    {
    }

    public function tearDown()
    {
        unset($this->memcache);
        $this->memcache = null;
    }

    public function testSet()
    {
        if (!class_exists('\Memcache')) {
            $this->markTestSkipped('Memcache is not installed. Skipping.');
            return;
        }
        $this->memcache = MemcacheService::getInstance('localhost');
        $result = $this->memcache->set($this->prefix . 'abc', '123', 10);
        $this->assertTrue($result);
        $this->memcache->disconnect();
        $resultTwo = $this->memcache->set($this->prefix . 'abc', '123', 10);
        // $resultTwo is void.
    }

    public function testGet()
    {
        if (!class_exists('\Memcache')) {
            $this->markTestSkipped('Memcache is not installed. Skipping.');
            return;
        }
        $this->memcache = MemcacheService::getInstance('localhost');
        $this->memcache->set($this->prefix . 'abc', '123', 10);
        $result = $this->memcache->get($this->prefix . 'abc');
        $this->assertEquals('123', $result);
        $this->memcache->disconnect();
        $resultTwo = $this->memcache->get($this->prefix . 'abc');
        $this->assertFalse($resultTwo);
    }

    public function testHas()
    {
        if (!class_exists('\Memcache')) {
            $this->markTestSkipped('Memcache is not installed. Skipping.');
            return;
        }
        $this->memcache = MemcacheService::getInstance('localhost');
        $result = $this->memcache->has($this->prefix . 'abc');
        $this->assertTrue($result);
        $resultTwo = $this->memcache->has($this->prefix . md5(microtime(true)));
        $this->assertFalse($resultTwo);
        $this->memcache->disconnect();
        $result = $this->memcache->has($this->prefix . 'abc');
        $this->assertFalse($result);
    }

    public function testRemove()
    {
        if (!class_exists('\Memcache')) {
            $this->markTestSkipped('Memcache is not installed. Skipping.');
            return;
        }
        $this->memcache = MemcacheService::getInstance('localhost');
        $result = $this->memcache->remove($this->prefix . 'abc');
        $this->assertTrue($result);
        $this->memcache->disconnect();
        $resultTwo = $this->memcache->remove($this->prefix . 'abc');
        $this->assertFalse($resultTwo);
    }

    public function testGetRaw()
    {
        if (!class_exists('\Memcache')) {
            $this->markTestSkipped('Memcache is not installed. Skipping.');
            return;
        }
        $this->memcache = MemcacheService::getInstance('localhost');
        $result = $this->memcache->getMemcache();
        $this->assertInstanceOf('\Memcache', $result);
        $this->memcache->disconnect();
        $this->assertFalse($this->memcache->getMemcache());
    }
}
