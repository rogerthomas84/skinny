<?php
/**
 * Skinny - a straight forward no-nonsense PHP library
 *
 * @author      Roger Thomas <roger.thomas@rogerethomas.com>
 * @copyright   2013 Roger Thomas
 * @link        http://www.rogerethomas.com
 * @license     http://www.rogerethomas.com/license
 * @since       2.0.7
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
 * AlphaNumeric
 *
 * Validates an email address is a valid format
 *
 * @package Skinny
 * @author  Roger Thomas <roger.thomas@rogerethomas.com>
 */
class AlphaNumeric extends AbstractValidator
{
    /**
     * @var string
     */
    public $errorMessage = '%s must only contain letters and numbers.';

    /**
     * @var bool
     */
    private $allowSpaces;

    /**
     * Construct, giving the $allowSpaces parameter to indicate
     * whether spaces are acceptable.
     * @param boolean $allowSpaces
     */
    public function __construct($allowSpaces = false)
    {
        if ($allowSpaces) {
            $this->errorMessage = '%s must only contain letters, numbers, and spaces.';
        }

        $this->allowSpaces = $allowSpaces;
    }

    /**
     * Ensure a string is alphanumeric (optionally with spaces as
     * set in the __construct()
     * @param mixed $value
     * @return boolean
     */
    public function isValid($value)
    {
        if (!is_string($value) && !is_integer($value)) {
            return false;
        }

        if (!$this->allowSpaces) {
            return ctype_alnum((string)$value);
        }

        return !preg_match('/[^ 0-9A-Za-z]/', (string)$value);
    }
}
