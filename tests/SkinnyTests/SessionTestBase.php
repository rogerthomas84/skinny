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

ob_start();
use PHPUnit\Framework\TestCase;

class SessionTestBase extends TestCase
{
    public function setUp()
    {
        $this->path = realpath(dirname(__FILE__));
    }

    protected function initServerVariables()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $_SERVER['REQUEST_URI'] = '/';
        $_SERVER['SERVER_NAME'] = 'localhost';
        $_SERVER['SERVER_PORT'] = '80';
    }

    protected function initLoggedOutSession()
    {
        $_SESSION = array();
        $_SESSION['__Sf_pb'] = array('store' => array('__Sf_Auth' => array()), 'locks' => array());
        $_SESSION['__Sf_pr'] = array('store' => array(), 'locks' => array());
        $_SESSION['__Sf_pb']['store']['__Sf_Auth'] = array('identity' => false, 'roles' => false);
    }

    protected function initLoggedInSession()
    {
        $_SESSION['__Sf_pb'] = array('store' => array('__Sf_Auth' => array()), 'locks' => array('__Sf_Auth' => array()));
        $_SESSION['__Sf_pr'] = array('store' => array(), 'locks' => array('__Sf_Auth' => array()));
        $_SESSION['__Sf_pb']['store']['__Sf_Auth'] = array('identity' => array('name' => 'Joe Bloggs', 'age' => 30), 'roles' => array('admin', 'user'));
    }

    /**
     * @since 2.0.8
     */
    protected function initLoggedInSessionInvalidRoles()
    {
        $_SESSION['__Sf_pb'] = array('store' => array('__Sf_Auth' => array()), 'locks' => array('__Sf_Auth' => array()));
        $_SESSION['__Sf_pr'] = array('store' => array(), 'locks' => array('__Sf_Auth' => array()));
        $_SESSION['__Sf_pb']['store']['__Sf_Auth'] = array('identity' => array('name' => 'Joe Bloggs', 'age' => 30), 'roles' => new \stdClass());
    }


}
