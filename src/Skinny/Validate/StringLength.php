<?php
/**
 * Skinny - a straight forward no-nonsense PHP library
 *
 * @author      Roger Thomas <roger.thomas@rogerethomas.com>
 * @copyright   2013 Roger Thomas
 * @link        http://www.rogerethomas.com
 * @license     http://www.rogerethomas.com/license
 * @since       2.0.4
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
 * StringLength
 *
 * Validates a string is of a given length
 *
 * @package Skinny
 * @author  Roger Thomas <roger.thomas@rogerethomas.com>
 */
class StringLength extends AbstractValidator
{
    /**
     * @var string
     */
    public $errorMessage = '%s is not valid';

    /**
     * @var integer
     */
    public $minimum = 0;

    /**
     * @var integer|null
     */
    public $maximum = null;

    /**
     * Specify the minimum and maximum string lengths
     * @param integer $minimum
     * @param integer|null $maximum
     */
    public function __construct($minimum, $maximum = null)
    {
        if ($maximum == null) {
            $this->errorMessage = '%s must be at least ' . $minimum . ' characters in length';
        } else {
            $this->errorMessage = '%s must be between ' . $minimum . '  and ' . $maximum . ' characters in length';
        }

        $this->maximum = $maximum;
        $this->minimum = $minimum;
    }

    /**
     * Ensure a string is between a given length
     * @param string|integer $value
     * @return boolean
     */
    public function isValid($value)
    {
        if (!is_string($value) && !is_integer($value)) {
            return false;
        }

        if (is_integer($value)) {
            $value = (string)$value;
        }

        $length = mb_strlen(trim($value));
        if ($this->maximum == null) {
            if ($length >= $this->minimum) {
                return true;
            }
            return false;
        }

        if ($length >= $this->minimum && $length <= $this->maximum) {
            return true;
        }

        return false;
    }
}
