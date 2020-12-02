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
 * Interface Cache
 * @package Skinny
 */
interface Cache
{
    /**
     * Get instance of the cache class
     * @param string $host
     * @param integer|null $port
     * @param integer|null $timeout
     * @return self
     */
    static function getInstance($host, $port = null, $timeout = null);

    /**
     * Get a single Key
     * @param string $key
     * @return mixed
     */
    function get($key);

    /**
     * Check if a key exists in the cache
     * @param string $key
     * @return boolean
     */
    function has($key);

    /**
     * Set a cached data set
     * @param string $key
     * @param mixed $value
     * @param integer $timeout
     * @return boolean
     */
    function set($key, $value, $timeout);

    /**
     * Remove a single value from cache
     * @param string $key
     * @return boolean
     */
    function remove($key);
}
