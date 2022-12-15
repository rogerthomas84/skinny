<?php /** @noinspection PhpComposerExtensionStubsInspection */
/** @noinspection PhpUnused */

/**
 * Skinny - a straight forward no-nonsense PHP library
 *
 * @author      Roger Thomas <roger.thomas@rogerethomas.com>
 * @copyright   2013 Roger Thomas
 * @link        http://www.rogerethomas.com
 * @license     http://www.rogerethomas.com/license
 * @version     2.0.3
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
namespace Skinny\Cache;

use Memcache;
use Skinny\Cache;

class MemcacheService implements Cache
{
    /**
     * How long the Memcache should last for
     *
     * @var int
     */
    const DEFAULT_EXPIRATION = 86400;

    /**
     * @var MemcacheService|null
     */
    protected static ?MemcacheService $cache = null;

    /**
     * Instance of Memcache
     * @var Memcache|null
     */
    private ?Memcache $memcache = null;

    /**
     * Is memcache connected?
     * @var bool
     */
    private bool $connected;

    /**
     * Singleton instance which means this is redundant
     * @param string $host
     * @param int|null $port
     * @param int|null $timeout
     */
    protected function __construct(string $host, int $port = null, int $timeout = null)
    {
        if (class_exists('\Memcache')) {
            $this->memcache = new Memcache();
            $this->connected = $this->memcache->connect($host, $port, $timeout);
        } else {
            // @codeCoverageIgnoreStart
            $this->connected = false;
            // @codeCoverageIgnoreEnd
        }
    }

    /**
     * Return instance of MemcacheService
     *
     * @param string $host
     *   Point to the host where memcached is listening
     *   for connections. This parameter may also specify other transports like
     *   unix:///path/to/memcached.sock to use UNIX domain sockets, in this
     *   case port must also be set to 0.
     * @param int|null $port
     *   Point to the port where memcached is listening for connections.
     *   Set this parameter to 0 when using UNIX domain sockets. Please note:
     *   port defaults to memcache.default_port if not specified. For this
     *   reason it is wise to specify the port explicitly in this method call.
     * @param int|null $timeout
     *   Value in seconds which will be used for connecting to the daemon.
     *   Think twice before changing the default value of 1 second - you
     *   can lose all the advantages of caching if your connection is too slow.
     * @return MemcacheService|null
     */
    public static function getInstance(string $host, int $port = null, int $timeout = null): ?MemcacheService
    {
        if (null === self::$cache) {
            self::$cache = new self($host, $port, $timeout);
        }

        if (self::$cache->connected === false) {
            self::$cache = new self($host, $port, $timeout);
        }

        return self::$cache;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        if (!$this->connected) {
            return false;
        }

        if ($this->get($key)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $key
     * @return array|bool|false|mixed|string
     * @noinspection PhpMixedReturnTypeCanBeReducedInspection
     */
    public function get(string $key): mixed
    {
        if (!$this->connected) {
            return false;
        }

        return $this->memcache->get($key);
    }

    /**
     * Get the raw Memcache object if connected
     * @return bool|Memcache|null
     */
    public function getMemcache(): bool|Memcache|null
    {
        if (!$this->connected) {
            return false;
        }

        return $this->memcache;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function remove(string $key): bool
    {
        if (!$this->connected) {
            return false;
        }

        return $this->memcache->delete($key);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param int $timeout
     * @return bool
     */
    public function set(string $key, mixed $value, int $timeout): bool
    {
        if (!$this->connected) {
            return false;
        }

        $compression = null;
        if (!is_bool($value) && !is_int($value) && !is_float($value)) {
            $compression = MEMCACHE_COMPRESSED;
        }

        return $this->memcache->set($key, $value, $compression, $timeout);
    }

    /**
     * Disconnect from Memcache
     */
    public function disconnect()
    {
        if ($this->connected) {
            $this->memcache->close();
        }
        $this->connected = false;
    }
}
