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
namespace SkinnyTests\Validate;

use PHPUnit\Framework\TestCase;
use Skinny\Validate\StringLength;

class StringLengthTest extends TestCase
{
    public function setUp()
    {
    }

    public function testValidStringLength()
    {
        $validator = new StringLength(1,4);
        $this->assertTrue($validator->isValid('a'));
        $this->assertTrue($validator->isValid('ab'));
        $this->assertTrue($validator->isValid('abc'));
        $this->assertTrue($validator->isValid('abcd'));
    }

    public function testInvalidStringLength()
    {
        $validator = new StringLength(1,4);
        $this->assertFalse($validator->isValid(''));
        $this->assertFalse($validator->isValid('abcde'));
    }

    public function testValidStringLengthNullMaximum()
    {
        $validator = new StringLength(1);
        $this->assertTrue($validator->isValid('a'));
        $this->assertTrue($validator->isValid('ab'));
        $this->assertTrue($validator->isValid('abc'));
        $this->assertTrue($validator->isValid('abcd'));
    }

    public function testInvalidStringLengthNullMaximum()
    {
        $validator = new StringLength(1);
        $this->assertFalse($validator->isValid(''));
    }

    public function testNonStrings()
    {
        $validator = new StringLength(1, 2);
        $this->assertFalse($validator->isValid(900));
        $this->assertTrue($validator->isValid(0));
        $this->assertFalse($validator->isValid(array('this' => 'is not valid')));
    }
}
