<?php
namespace Skinny\Tests\Validate;

use Skinny\Cache\MemcacheService;

class MemcacheInvalidTest extends \PHPUnit_Framework_TestCase
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

    public function testSetInvalid()
    {
        unset($this->memcache);
        $this->memcache = MemcacheService::getInstance('127.0.0.1', 80);
        $result = $this->memcache->set($this->prefix . 'abc', '123', 10);
        $this->assertTrue($result);
    }

    public function testSetInvaliad()
    {
        unset($this->memcache);
        $this->memcache = MemcacheService::getInstance('127.0.0.1', 80);
        $result = $this->memcache->get($this->prefix . 'abc', '123', 10);
        $this->assertFalse($result);
    }
}
