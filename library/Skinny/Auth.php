<?php
/**
 * Skinny - a straight forward no-nonsense PHP library
 *
 * @author      Roger Thomas <roger.thomas@rogerethomas.com>
 * @copyright   2013 Roger Thomas
 * @link        http://www.rogerethomas.com
 * @license     http://www.rogerethomas.com/license
 * @version     1.0.0
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

use Skinny\Storage;

/**
 * Auth
 *
 * This class extends the basic functionality of the \Skinny\Storage to
 * provide a basic method of storing and retrieving a users authentication
 * status.
 *
 * Calling login() and logout() provides the core of the class
 *
 * @package Skinny
 * @author  Roger Thomas <roger.thomas@rogerethomas.com>
 */
class Auth {

    /**
     * Instance of this class
     *
     * @var \Skinny\Auth
     */
    protected static $_instance = null;

    /**
     * @var \Skinny\Storage
     */
    private $instance = false;

    /**
     * Protected __construct()
     *
     * @throws \Exception
     */
    final protected function __construct()
    {
        $this->setup();
    }

    /**
     * Retrieve an instance of \Skinny\Auth
     *
     * @return \Skinny\Auth
     */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Destroy an authentication session.
     *
     * @param mixed $identity - the users account details.
     * @return boolean
     */
    public function login($identity)
    {
    	if ($this->instance instanceof \Skinny\Storage) {
    		if ($this->instance->isLocked()) {
    			$this->instance->unlock();
    		}
			$this->instance->set('identity', $identity);
			$this->instance->lock();
			return true;
    	}

    	return false;
    }

    /**
     * Retrieve the users identity as set by login
     *
     * @see \Skinny\Auth::login
     * @return mixed|boolean false for no identity
     */
    public function getIdentity()
    {
    	if ($this->isLoggedIn()) {
    		return $this->instance->get('identity');
    	}

    	return false;
    }

    /**
     * Check if a user is logged in or not.
     *
     * @return boolean
     */
    public function isLoggedIn()
    {
    	if ($this->instance instanceof \Skinny\Storage) {
			if ($this->instance->get('identity') != false) {
				return true;
			}
    	}

    	return false;
    }

    /**
     * Destroy an authentication session.
     *
     * @return boolean
     */
    public function logout()
    {
    	if ($this->instance instanceof \Skinny\Storage) {
    		if ($this->instance->isLocked()) {
    			$this->instance->unlock();
    		}
			$this->instance->destroy();
    	}

    	return false;
    }

    /**
     * Setup the namespace object
     */
    protected function setup()
    {
        $this->instance = new \Skinny\Storage('__Sf_Auth');
       	$this->instance->lock();
    }

}