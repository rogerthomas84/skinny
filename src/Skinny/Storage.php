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
 * Storage
 *
 * This class provides a Session Namespace approach to storing data for a
 * users session.
 *
 * There are no restrictions, and storage buckets can be constructed and
 * locked to protect the data from accidental damage.
 *
 * @package Skinny
 * @author  Roger Thomas <roger.thomas@rogerethomas.com>
 */
class Storage
{
    /**
     * Name of the secret storage within the session
     *
     * @var string
     */
    public $secretStorage = "__Sf_pr";

    /**
     * Name of the public storage within the session
     *
     * @var string
     */
    public $publicStorage = "__Sf_pb";

    /**
     * The name of this namespace.
     *
     * @var string
     */
    private $_namespaceName = null;

    /**
     * Initiate a new or existing namespace
     *
     * @param string $name defaults to 'Default'
     * @throws BaseException
     */
    public function __construct($name = 'Default')
    {
        if (headers_sent($filename, $linenum)) {
            // @codeCoverageIgnoreStart
            throw new BaseException('Headers already sent in ' . $filename . '::' . $linenum);
            // @codeCoverageIgnoreEnd
        } else {
            if ($name === '') {
                throw new BaseException('Namespace name cannot be empty');
            } else if ($name[0] == "_" && substr($name, 0, 5) != "__Sf_") {
                throw new BaseException('Namespace name cannot start with an underscore.');
            } else if (preg_match('#(^[0-9])#i', $name[0])) {
                throw new BaseException('Namespace name cannot start with a number');
            } else {
                $this->_namespaceName = $name;
                @session_start();
                $this->setup();
            }
        }
    }

    /**
     * Lock the namespace, this will prevent removal of keys
     *
     * @return boolean
     * @throws BaseException
     */
    public function lock()
    {
        $this->_validate();
        $_SESSION[$this->secretStorage]['locks'][$this->_namespaceName] = true;
        return true;
    }

    /**
     * Unlock the namespace, this will allow removal of keys
     *
     * @return boolean
     * @throws BaseException
     */
    public function unlock()
    {
        $this->_validate();
        $_SESSION[$this->secretStorage]['locks'][$this->_namespaceName] = false;
        return true;
    }

    /**
     * Check if a namespace is currently locked.
     *
     * @return boolean
     * @throws BaseException
     */
    public function isLocked()
    {
        $this->_validate();
        if ($_SESSION[$this->secretStorage]['locks'][$this->_namespaceName] == true) {
            return true;
        }
        return false;
    }

    /**
     * Set a value in the current namespace
     *
     * @param string $name
     * @param mixed $value
     * @return boolean result of save
     * @throws BaseException
     */
    public function set($name, $value)
    {
        if (!$this->isLocked()) {
            $_SESSION[$this->publicStorage]['store'][$this->_namespaceName][$name] = $value;
            return true;
        }
        return false;
    }

    /**
     * Retrieve a single value from the namespace
     *
     * @param string $name
     * @return mixed
     * @throws BaseException
     */
    public function get($name)
    {
        $this->_validate();
        if (array_key_exists($name, $_SESSION[$this->publicStorage]['store'][$this->_namespaceName])) {
            return $_SESSION[$this->publicStorage]['store'][$this->_namespaceName][$name];
        }
        return false;
    }

    /**
     * Retrieve the entire namespace
     *
     * @return array|false
     * @throws BaseException
     */
    public function getAll()
    {
        $this->_validate();
        if (array_key_exists($this->_namespaceName, $_SESSION[$this->publicStorage]['store'])) {
            return $_SESSION[$this->publicStorage]['store'][$this->_namespaceName];
        }
        return false;
    }

    /**
     * Remove an key from the namespace
     *
     * @param string $name
     * @return boolean result of removal
     * @throws BaseException
     */
    public function remove($name)
    {
        $this->_validate();
        if (!$this->get($name)) {
            return true;
        }
        if (!$this->isLocked()) {
            unset($_SESSION[$this->publicStorage]['store'][$this->_namespaceName][$name]);
            return true;
        }
        return false;
    }

    /**
     * Clear all values currently held in this namespace
     *
     * @return boolean status of removal
     * @throws BaseException
     */
    public function removeAll()
    {
        $this->_validate();
        if (!$this->isLocked()) {
            $_SESSION[$this->publicStorage]['store'][$this->_namespaceName] = array();
            return true;
        }
        return false;
    }

    /**
     * Destroy this entire namespace. After calling this
     * the namespace will no longer be held in session
     *
     * @return boolean
     * @throws BaseException
     */
    public function destroy()
    {
        $this->_validate();
        if (!$this->isLocked()) {
            unset($_SESSION[$this->publicStorage]['store'][$this->_namespaceName]);
            return true;
        }
        return false;
    }

    /**
     * Ensure the session contains the data we expect to see.
     *
     * @return boolean
     */
    protected function setup()
    {
        if (array_key_exists($this->secretStorage, $_SESSION)) {
            if (!is_array($_SESSION[$this->secretStorage])) {
                $_SESSION[$this->secretStorage] = array('locks' => array($this->_namespaceName => false));
            } else {
                if (!array_key_exists($this->_namespaceName, $_SESSION[$this->secretStorage]['locks'])) {
                    $_SESSION[$this->secretStorage]['locks'][$this->_namespaceName] = false;
                } else {
                    if (!is_bool($_SESSION[$this->secretStorage]['locks'][$this->_namespaceName])) {
                        $_SESSION[$this->secretStorage]['locks'][$this->_namespaceName] = false;
                    }
                }
            }
        } else {
            $_SESSION[$this->secretStorage] = array('locks' => array($this->_namespaceName => false));
        }

        if (array_key_exists($this->publicStorage, $_SESSION)) {
            if (array_key_exists('store', $_SESSION[$this->publicStorage])) {
                if (!array_key_exists($this->_namespaceName, $_SESSION[$this->publicStorage]['store'])) {
                    $_SESSION[$this->publicStorage]['store'][$this->_namespaceName] = array();
                }
            } else {
                $_SESSION[$this->publicStorage]['store'] = array($this->_namespaceName => array());
            }
        } else {
            $_SESSION[$this->publicStorage] = array('store' => array($this->_namespaceName => array()));
        }

        return true;
    }

    /**
     * Validate if a session exists.
     *
     * @throws BaseException
     */
    protected function _validate()
    {
        if (!isset($_SESSION)) {
            throw new BaseException('Session may not be started.');
        }
        return;
    }
}
