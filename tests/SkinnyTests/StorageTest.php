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
namespace SkinnyTests;

use Skinny\Storage;

class StorageTest extends SessionTestBase
{
    private function reset()
    {
        return new Storage();
    }

    public function testLocks()
    {
        $_SESSION = array();
        $storage = $this->reset();
        $this->assertFalse($storage->isLocked());
        $storage->lock();
        $this->assertTrue($storage->isLocked());
    }

    public function testSetWithLocks()
    {
        $_SESSION = array();
        $storage = $this->reset();
        $storage->lock();
        $this->assertFalse($storage->set('abc', '123'));
        $storage->unlock();
        $this->assertTrue($storage->set('abc', '123'));
    }

    public function testGet()
    {
        $_SESSION = array();
        $storage = $this->reset();
        $storage->unlock();
        $this->assertFalse($storage->get('abc'));
        $storage->set('abc', '123');
        $this->assertEquals('123', $storage->get('abc'));
    }

    public function testGetAll()
    {
        $_SESSION = array();
        $storage = $this->reset();
        $storage->unlock();
        $i = 0;
        while ($i < 10) {
            $i++;
            $storage->set('key' . $i, 'value:' . $i);
            $this->assertCount($i, $storage->getAll());
        }
        $storage->removeAll();
        $this->assertEmpty($storage->getAll());
    }

    public function testGetAllNotStarted()
    {
        $_SESSION = array();
        $storage = $this->reset();
        $storage->destroy();
        $this->assertFalse($storage->getAll());
    }

    public function testRemoveExists()
    {
        $_SESSION = array();
        $storage = $this->reset();
        $storage->set('abc', '123');
        $this->assertTrue($storage->remove('abc'));
        $this->assertTrue($storage->remove('def')); // doesnt exist
        $storage->set('abc', '123');
        $storage->lock();
        $this->assertFalse($storage->remove('abc')); // false as locked.
    }

    public function testRemoveAll()
    {
        $_SESSION = array();
        $storage = $this->reset();
        $storage->set('abc', '123');
        $this->assertTrue($storage->removeAll());
        $_SESSION = array();
        $storage = $this->reset();
        $storage->set('abc', '123');
        $storage->lock();
        $this->assertFalse($storage->removeAll());
    }

    public function testDestroyAll()
    {
        $_SESSION = array();
        $storage = $this->reset();
        $storage->set('abc', '123');
        $this->assertTrue($storage->destroy());
        $_SESSION = array();
        $storage = $this->reset();
        $storage->set('abc', '123');
        $storage->lock();
        $this->assertFalse($storage->destroy());
    }

    public function testReConstruct()
    {
        $_SESSION = array();
        $storage = $this->reset();
        $storage->__construct();
        $_SESSION['__Sf_pr']['locks'] = array();
        $storage->__construct();
        $_SESSION['__Sf_pr'] = false;
        $storage->__construct();
        $_SESSION['__Sf_pr']['locks'] = array('Default' => '12345');

        $storage = $this->reset();
        $_SESSION['__Sf_pb'] = array();
        $storage->__construct();
        $_SESSION['__Sf_pb']['store'] = array();
        $storage->__construct();
    }

    public function testRemoveSession()
    {
        $_SESSION = array();
        $storage = $this->reset();
        unset($_SESSION);
        try {
            $storage->get('abc');
        } catch (\Exception $e) {
            $this->assertContains('may not be started', $e->getMessage());
            return;
        }
        $this->fail('exception was expected');
    }

    public function testNoSessionName()
    {
        $_SESSION = array();
        try {
            $storage = new Storage('');
        } catch (\Exception $e) {
            $this->assertEquals('Namespace name cannot be empty', $e->getMessage());
            return;
        }

        $this->fail('exception was expected');
    }

    public function testUnderscoreSessionName()
    {
        $_SESSION = array();
        try {
            $storage = new Storage('_MySession');
        } catch (\Exception $e) {
            $this->assertEquals('Namespace name cannot start with an underscore.', $e->getMessage());
            return;
        }

        $this->fail('exception was expected');
    }

    public function testNumberSessionName()
    {
        $_SESSION = array();
        try {
            $storage = new Storage('1Session');
        } catch (\Exception $e) {
            $this->assertEquals('Namespace name cannot start with a number', $e->getMessage());
            return;
        }

        $this->fail('exception was expected');
    }
}
