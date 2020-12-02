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
namespace SkinnyTests\Auth\Adapter;

use Skinny\Auth\AdapterAbstract;
use Skinny\Auth\Service;

class TestCaseAdapter extends AdapterAbstract
{
    /**
     * @var array|boolean false
     */
    private $identity = false;

    /**
     * @var array|boolean false
     */
    private $roles = false;

    /**
     * @var string|null
     */
    private $user = null;

    /**
     * @var boolean
     */
    private $fakeCode = false;

    /**
     * @var string|null
     */
    private $pass = null;

    /**
     * @param string $user
     * @param string $pass
     */
    public function setCredentials($user, $pass)
    {
        $this->user = $user;
        $this->pass = $pass;
    }

    public function getResult()
    {
        // fake a credential - DO NOT COPY THIS CLASS INTO PRODUCTION.
        // SHA! IS NOT SECURE!
        $shad = sha1('password');
        if (sha1($this->pass) == $shad && $this->user == 'test@example.com') {
            $this->identity = array(
                'user' => 'test@example.com',
                'password' => $shad
            );
            $this->roles = array('user', 'admin');
            return Service::SKINNY_AUTH_SERVICE_SUCCESS;
        }

        if (!$this->fakeCode) {
            return Service::SKINNY_AUTH_SERVICE_INVALID;
        }

        return 3; // invalid code, mocked
    }

    public function setReturnFakeCode()
    {
        $this->fakeCode = true;
    }

    public function getIdentity()
    {
        return $this->identity;
    }

    public function getRoles()
    {
        return $this->roles;
    }
}
