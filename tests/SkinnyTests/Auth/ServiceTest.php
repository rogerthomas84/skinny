<?php
/**
 * Skinny - a straight forward no-nonsense PHP library
 *
 * @author      Roger Thomas <roger.thomas@rogerethomas.com>
 * @copyright   2013 Roger Thomas
 * @link        http://www.rogerethomas.com
 * @license     http://www.rogerethomas.com/license
 * @since       2.0.6
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
namespace SkinnyTests\Auth;

use SkinnyTests\Auth\Adapter\TestCaseAdapter;
use Skinny\Auth\Service;
use Skinny\Auth;
use SkinnyTests\SessionTestBase;

class ServiceTest extends SessionTestBase
{
    public function testServiceValidCredentials()
    {
        $this->initLoggedOutSession();
        $adapter = new TestCaseAdapter();
        $adapter->setCredentials('test@example.com', 'password');
        $service = new Service($adapter);
        $result = $service->login();
        $this->assertTrue($result);
        $auth = Auth::getInstance();
        $identity = $auth->getIdentity();
        $roles = $auth->getRoles();
        $this->assertCount(2, $identity);
        $this->assertCount(2, $roles);
        $this->assertContains('admin', $roles);
    }

    public function testServiceInvalidCredentials()
    {
        $this->initLoggedOutSession();
        $adapter = new TestCaseAdapter();
        $adapter->setCredentials('joe@example.com', 'notjoespassword');
        $service = new Service($adapter);
        try {
            $result = $service->login();
        } catch (\Exception $e) {
            $this->assertInstanceOf('\Skinny\Auth\Exception\InvalidCredentialsException', $e);
            return;
        }
        $this->fail('expected exception');
    }

    public function testServiceInvalidReturnCode()
    {
        $this->initLoggedOutSession();
        $adapter = new TestCaseAdapter();
        $adapter->setCredentials('jane@example.com', 'notjanespassword');
        $adapter->setReturnFakeCode();
        $service = new Service($adapter);
        try {
            $result = $service->login();
        } catch (\Exception $e) {
            $this->assertInstanceOf('\Skinny\Auth\Exception\UnrecognisedAuthenticationResultException', $e);
            return;
        }
        $this->fail('expected exception');
    }

}
