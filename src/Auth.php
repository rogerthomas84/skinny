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

use Exception;

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
class Auth
{
    /**
     * Instance of this class
     *
     * @var Auth|null
     */
    protected static ?Auth $_instance = null;

    /**
     * @var null|Storage
     */
    private null|Storage $instance = null;

    /**
     * Protected __construct()
     *
     * @throws Exception
     */
    final protected function __construct()
    {
        $this->setup();
    }

    /**
     * Retrieve an instance of \Skinny\Auth
     *
     * @return Auth
     */
    public static function getInstance(): Auth
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Set roles for a user
     *
     * @param array $roles (optional) - the users roles
     * @return bool
     * @throws BaseException
     */
    public function setRoles(array $roles = []): bool
    {
        if ($this->instance instanceof Storage) {
            if ($this->instance->isLocked()) {
                $this->instance->unlock();
            }
            $this->instance->set('roles', $roles);
            $this->instance->lock();
            return true;
        }

        return false;
    }

    /**
     * Add to the identity. This performs an array merge on the current
     * identity, so you can override anything you need to
     *
     * @param array $array
     * @return bool
     * @throws BaseException
     */
    public function addToIdentity(array $array): bool
    {
        if ($this->instance instanceof Storage) {
            if ($this->instance->isLocked()) {
                $this->instance->unlock();
            }
            $temp = $this->instance->get('identity');
            if (!$temp) {
                $temp = [];
            }
            $newIdentity = array_merge($temp, $array);
            $this->instance->set('identity', $newIdentity);
            $this->instance->lock();
            return true;
        }

        return false;
    }

    /**
     * Add a single role to a user
     *
     * @param string $role - a role identifier
     * @return Auth
     * @throws BaseException
     */
    public function addRole(string $role): self
    {
        if ($this->instance instanceof Storage) {
            if ($this->hasRole($role)) {
                return $this;
            }
            if ($this->instance->isLocked()) {
                $this->instance->unlock();
            }
            $roles = $this->instance->get('roles');
            if (is_array($roles)) {
                $this->instance->set(
                    'roles', array_merge($roles, [$role]));
            } else {
                $this->instance->set('roles', [$role]);
            }
            $this->instance->lock();
            return $this;
        }

        return $this;
    }

    /**
     * Remove a single role from a user
     *
     * @param string $role - a role identifier
     * @return bool
     * @throws BaseException
     */
    public function removeRole(string $role): bool
    {
        if ($this->instance instanceof Storage) {
            if (!$this->hasRole($role)) {
                return false;
            }
            if ($this->instance->isLocked()) {
                $this->instance->unlock();
            }
            $roles = $this->instance->get('roles');
            if (is_array($roles)) {
                $new = [];
                foreach ($roles as $old) {
                    if ($old != $role) {
                        $new[] = $old;
                    }
                }
                $this->instance->set('roles', $new);
            }

            $this->instance->lock();
            return true;
        }

        return false;
    }

    /**
     * Check if a user has a given role
     *
     * @param string|array $mixed
     * @return bool
     * @throws BaseException
     * @since 2.0.8
     */
    public function hasRole(mixed $mixed): bool
    {
        if ($this->instance instanceof Storage) {
            $roles = $this->instance->get('roles');
            if (!is_array($roles)) {
                return false;
            }

            /** @noinspection PhpConditionCheckedByNextConditionInspection */
            if (!is_array($mixed) && (is_string($mixed) || is_integer($mixed)) && in_array($mixed, $roles)) {
                return true;
            }

            if (is_array($mixed)) {
                foreach ($mixed as $potential) {
                    if (in_array($potential, $roles)) {
                        return true;
                    }
                }
            } // @codeCoverageIgnore
        }

        return false;
    }

    /**
     * Destroy an authentication session.
     *
     * @param array $identity (optional) - the users account details.
     * @return bool
     * @throws BaseException
     */
    public function login(array $identity = []): bool
    {
        if ($this->instance instanceof Storage) {
            if ($this->instance->isLocked()) {
                $this->instance->unlock();
            }
            $this->instance->set('identity', $identity);
            $this->instance->set('roles', []);
            $this->instance->lock();
            return true;
        }

        return false;
    }

    /**
     * Retrieve the users identity as set by login
     *
     * @return array|bool false for no identity
     * @throws BaseException
     * @see \Skinny\Auth::login
     */
    public function getIdentity(): bool|array
    {
        if ($this->isLoggedIn()) {
            return $this->instance->get('identity');
        }

        return false;
    }

    /**
     * Retrieve the users roles as set by setRoles()
     *
     * @return array|bool false for failure
     * @throws BaseException
     */
    public function getRoles(): bool|array
    {
        if ($this->isLoggedIn()) {
            return $this->instance->get('roles');
        }

        return false;
    }

    /**
     * Check if a user is logged in or not.
     *
     * @return bool
     * @throws BaseException
     */
    public function isLoggedIn(): bool
    {
        if ($this->instance instanceof Storage) {
            if ($this->instance->get('identity')) {
                return true;
            }
        }

        return false;
    }

    /**
     * Destroy an authentication session.
     *
     * @return bool
     * @throws BaseException
     */
    public function logout(): bool
    {
        if ($this->instance instanceof Storage) {
            if ($this->instance->isLocked()) {
                $this->instance->unlock();
            }
            $this->instance->destroy();
        }

        return false;
    }

    /**
     * Get the storage object
     * @return Storage|null
     */
    public function getStorage(): ?Storage
    {
        if ($this->instance instanceof Storage) {
            return $this->instance;
        }

        return null;
    }

    /**
     * Destroy the instance of Storage
     * @throws BaseException
     */
    public function destroy(): void
    {
        if ($this->instance instanceof Storage) {
            $this->instance->destroy();
        }
        $this->instance = null;
    }

    /**
     * Set up the namespace object
     * @throws BaseException
     */
    public function setup(): void
    {
        $this->instance = new Storage('__Sf_Auth');
        $this->instance->lock();
    }
}
