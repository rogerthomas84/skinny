<?php
namespace Skinny\Tests\Validate;

use Skinny\Cache\MemcacheService;

class MemcacheTest extends \PHPUnit_Framework_TestCase
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
        $this->memcache = MemcacheService::getInstance('localhost');
        $result = $this->memcache->set($this->prefix . 'abc', '123', 10);
        $this->assertTrue($result);
    }

    public function testHas()
    {
        $this->memcache = MemcacheService::getInstance('localhost');
        $result = $this->memcache->has($this->prefix . 'abc');
        $this->assertTrue($result);
    }

    public function testRemove()
    {
        $this->memcache = MemcacheService::getInstance('localhost');
        $result = $this->memcache->remove($this->prefix . 'abc');
        $this->assertTrue($result);
    }

    public function testGetRaw()
    {
        $this->memcache = MemcacheService::getInstance('localhost');
        $result = $this->memcache->getMemcache();
        $this->assertInstanceOf('\Memcache', $result);
    }
}
