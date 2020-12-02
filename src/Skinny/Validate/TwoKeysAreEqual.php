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

namespace Skinny\Validate;

/**
 * TwoKeysAreEqual
 *
 * Validates a given value matches a key from an array
 *
 * @package Skinny
 * @author  Roger Thomas <roger.thomas@rogerethomas.com>
 */
class TwoKeysAreEqual extends AbstractValidator
{
    /**
     * @var string
     */
    public $errorMessage = '%s is not the same.';

    /**
     * @var string
     */
    public $mustMatchKey = null;

    /**
     * Construct, giving the key name of the data that this must match.
     * @param string $mustMatchKey
     */
    public function __construct($mustMatchKey)
    {
        $this->mustMatchKey = $mustMatchKey;
    }

    /**
     * Ensure a value matches the a specified key in the array
     * of data.
     * @param mixed $value
     * @return boolean
     */
    public function isValid($value)
    {
        if (array_key_exists($this->mustMatchKey, $this->data)) {
            if ($this->data[$this->mustMatchKey] === $value) {
                return true;
            }
        }

        return false;
    }
}
