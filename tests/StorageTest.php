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

use Exception;
use Skinny\BaseException;
use Skinny\Storage;

class StorageTest extends SessionTestBase
{
    private function reset(): Storage
    {
        return new Storage();
    }

    /**
     * @throws BaseException
     */
    public function testLocks()
    {
        $_SESSION = [];
        $storage = $this->reset();
        $this->assertFalse($storage->isLocked());
        $storage->lock();
        $this->assertTrue($storage->isLocked());
    }

    /**
     * @throws BaseException
     */
    public function testSetWithLocks()
    {
        $_SESSION = [];
        $storage = $this->reset();
        $storage->lock();
        $this->assertFalse($storage->set('abc', '123'));
        $storage->unlock();
        $this->assertTrue($storage->set('abc', '123'));
    }

    /**
     * @throws BaseException
     */
    public function testGet()
    {
        $_SESSION = [];
        $storage = $this->reset();
        $storage->unlock();
        $this->assertFalse($storage->get('abc'));
        $storage->set('abc', '123');
        $this->assertEquals('123', $storage->get('abc'));
    }

    /**
     * @throws BaseException
     */
    public function testGetAll()
    {
        $_SESSION = [];
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

    /**
     * @throws BaseException
     */
    public function testGetAllNotStarted()
    {
        $_SESSION = [];
        $storage = $this->reset();
        $storage->destroy();
        $this->assertFalse($storage->getAll());
    }

    /**
     * @throws BaseException
     */
    public function testRemoveExists()
    {
        $_SESSION = [];
        $storage = $this->reset();
        $storage->set('abc', '123');
        $this->assertTrue($storage->remove('abc'));
        $this->assertTrue($storage->remove('def')); // doesnt exist
        $storage->set('abc', '123');
        $storage->lock();
        $this->assertFalse($storage->remove('abc')); // false as locked.
    }

    /**
     * @throws BaseException
     */
    public function testRemoveAll()
    {
        $_SESSION = [];
        $storage = $this->reset();
        $storage->set('abc', '123');
        $this->assertTrue($storage->removeAll());
        $_SESSION = [];
        $storage = $this->reset();
        $storage->set('abc', '123');
        $storage->lock();
        $this->assertFalse($storage->removeAll());
    }

    /**
     * @throws BaseException
     */
    public function testDestroyAll()
    {
        $_SESSION = [];
        $storage = $this->reset();
        $storage->set('abc', '123');
        $this->assertTrue($storage->destroy());
        $_SESSION = [];
        $storage = $this->reset();
        $storage->set('abc', '123');
        $storage->lock();
        $this->assertFalse($storage->destroy());
    }

    /**
     * @throws BaseException
     * @noinspection PhpArrayIndexImmediatelyRewrittenInspection
     */
    public function testReConstruct()
    {
        $_SESSION = [];
        $storage = $this->reset();
        $storage->__construct();
        $_SESSION['__Sf_pr']['locks'] = [];
        $storage->__construct();
        $_SESSION['__Sf_pr'] = false;
        $storage->__construct();
        $_SESSION['__Sf_pr']['locks'] = ['Default' => '12345'];

        $storage = $this->reset();
        $_SESSION['__Sf_pb'] = [];
        $storage->__construct();
        $_SESSION['__Sf_pb']['store'] = [];
        $storage->__construct();
        $this->assertEmpty($storage->getAll());
    }

    public function testRemoveSession()
    {
        $_SESSION = [];
        $storage = $this->reset();
        unset($_SESSION);
        try {
            $storage->get('abc');
        } catch (Exception $e) {
            $this->assertStringContainsString('may not be started', $e->getMessage());
            return;
        }
        $this->fail('exception was expected');
    }

    public function testNoSessionName()
    {
        $_SESSION = [];
        try {
            new Storage('');
        } catch (Exception $e) {
            $this->assertEquals('Namespace name cannot be empty', $e->getMessage());
            return;
        }

        $this->fail('exception was expected');
    }

    public function testUnderscoreSessionName()
    {
        $_SESSION = [];
        try {
            new Storage('_MySession');
        } catch (Exception $e) {
            $this->assertEquals('Namespace name cannot start with an underscore.', $e->getMessage());
            return;
        }

        $this->fail('exception was expected');
    }

    public function testNumberSessionName()
    {
        $_SESSION = [];
        try {
            new Storage('1Session');
        } catch (Exception $e) {
            $this->assertEquals('Namespace name cannot start with a number', $e->getMessage());
            return;
        }

        $this->fail('exception was expected');
    }
}
