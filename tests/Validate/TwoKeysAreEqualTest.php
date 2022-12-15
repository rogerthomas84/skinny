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
namespace SkinnyTests\Validate;

use PHPUnit\Framework\TestCase;
use Skinny\Validate\TwoKeysAreEqual;

class TwoKeysAreEqualTest extends TestCase
{
    /**
     * @var TwoKeysAreEqual|null
     */
    private ?TwoKeysAreEqual $validator = null;

    public function setUp(): void
    {
        $this->validator = new TwoKeysAreEqual('repeatPassword');
        $this->validator->setData(
            [
                'repeatPassword' => 'joebloggs12345'
            ]
        );
    }

    public function testValidatorValid()
    {
        /** @noinspection SpellCheckingInspection */
        $this->assertTrue($this->validator->isValid('joebloggs12345'));
    }

    public function testValidatorInvalid()
    {
        /** @noinspection SpellCheckingInspection */
        $this->assertFalse($this->validator->isValid('thisisnotthesame'));
    }

    public function testValidatorVariableTypesWork()
    {
        $this->assertFalse($this->validator->isValid(0));
        $this->validator->setData(
            [
                'repeatPassword' => '0'
            ]
        );
        $this->assertFalse($this->validator->isValid(0));
        $this->assertTrue($this->validator->isValid('0'));
    }
}
