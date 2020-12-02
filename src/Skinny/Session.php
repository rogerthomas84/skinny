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

namespace Skinny;

/**
 * Session
 *
 * This class controls the basic Session functionality that's needed for
 * applications of any size.
 *
 * The primary goal of this class is to provide a simplistic interface to
 * interact with session data.
 *
 * @package Skinny
 * @author  Roger Thomas <roger.thomas@rogerethomas.com>
 */
class Session
{
    /**
     * Instance of this class
     *
     * @var Session
     */
    protected static $_instance = null;

    /**
     * @var Storage
     */
    private $instance = false;

    /**
     * Protected __construct()
     *
     * @throws BaseException
     */
    final protected function __construct()
    {
        if (headers_sent($filename, $linenum)) {
            // @codeCoverageIgnoreStart
            throw new BaseException('Headers already sent in ' . $filename . '::' . $linenum);
            // @codeCoverageIgnoreEnd
        } else {
            $this->setup();
        }
    }

    /**
     * Retrieve an instance of \Skinny\Session
     *
     * @return Session
     */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Set a key, value in the standard session
     *
     * @param string $name
     * @param mixed $value
     * @return boolean result of set
     * @throws BaseException
     */
    public function set($name, $value)
    {
        $this->instance->unlock();
        $result = $this->instance->set($name, $value);
        $this->instance->lock();

        return $result;
    }

    /**
     * Remove a single value from the session
     *
     * @param string $name
     * @return boolean
     * @throws BaseException
     */
    public function remove($name)
    {
        $this->instance->unlock();
        $result = $this->instance->remove($name);
        $this->instance->lock();
        return $result;
    }

    /**
     * Clear all session values outside of the namespace.
     *
     * @return boolean
     * @throws BaseException
     */
    public function removeAll()
    {
        $this->instance->unlock();
        $result = $this->instance->removeAll();
        $this->instance->lock();
        return $result;
    }

    /**
     * Destroy the session and optionally specify $regenerate = true
     * to regenerate a new session id.
     *
     * @param boolean $regenerate
     * @return boolean
     * @throws BaseException
     */
    public function destroy($regenerate = false)
    {
        $this->instance->unlock();
        $result = $this->instance->destroy();
        if ($regenerate == true) {
            session_regenerate_id(true);
        }
        return $result;
    }

    /**
     * Retrieve a value from the session
     *
     * @param string $name
     * @return mixed|false for failure
     * @throws BaseException
     */
    public function get($name)
    {
        return $this->instance->get($name);
    }

    /**
     * Setup the namespace object
     */
    protected function setup()
    {
        $this->instance = new Storage('__Sf_');
    }
}
