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

namespace SkinnyTests;

use PHPUnit\Framework\TestCase;
use Skinny\Form;
use Skinny\Validate\NotEmpty;

class FormTest extends TestCase
{
    /**
     * @var Form|null
     */
    private ?Form $form = null;

    public function setUp(): void
    {
        $this->reset();
    }

    private function reset()
    {
        $this->form = new Form();
    }

    public function testRequiredWorks()
    {
        $this->reset();
        $this->form->addElement('test1', true);
        $result = $this->form->isValid(['test1' => null]);
        $this->assertFalse($result);
        $this->assertEquals(null, $this->form->getProvidedData('test1'));
    }

    public function testRequiredValidWorks()
    {
        $this->reset();
        $this->form->addElement('test1', true);
        $result = $this->form->isValid(['test1' => '123']);
        $this->assertTrue($result);
        $this->assertEquals('123', $this->form->getProvidedData('test1'));

        $resultFour = $this->form->isValid(['test1' => ['yes', 'its', 'ok']]);
        $this->assertTrue($resultFour);
        $this->assertEquals(['yes', 'its', 'ok'], $this->form->getProvidedData('test1'));
        $this->assertCount(1, $this->form->getProvidedData());
        $this->assertArrayHasKey('test1', $this->form->getProvidedData());
    }

    public function testRequiredEmptyArrayFails()
    {
        $this->reset();
        $this->form->addElement('test1', true);
        $result = $this->form->isValid(['test1' => []]);
        $this->assertFalse($result);
    }

    public function testRequiredFullArrayFails()
    {
        $this->reset();
        $this->form->addElement('test1', true);
        $result = $this->form->isValid(['test1' => ['yes', 'its', 'ok']]);
        $this->assertTrue($result);
    }

    public function testExtraFieldStillPasses()
    {
        $this->reset();
        $this->form->addElement('test1', true);
        $result = $this->form->isValid(['another' => 'joe', 'test1' => ['yes', 'its', 'ok']]);
        $this->assertTrue($result);
    }

    public function testNonArrayParamsFails()
    {
        $this->reset();
        $this->form->addElement('test1', true);
        $result = $this->form->isValid(['I should not be a keyless array']);
        $this->assertFalse($result);
    }

    public function testPostParamMissingKeyPasses()
    {
        $this->reset();
        $this->form->addElement('test1', true);
        $result = $this->form->isValid(['another' => 'joe']);
        $this->assertFalse($result);
    }

    public function testErrorMessageExpected()
    {
        $this->reset();
        $this->form->addElement('test1', true, [new NotEmpty()], 'Test field one');
        $result = $this->form->isValid(['test1' => '']);
        $this->assertFalse($result);
        $errors = $this->form->getErrors();
        $this->assertCount(1, $errors);
        $this->assertArrayHasKey('test1', $errors);
        $this->assertCount(1, $errors['test1']);
    }
}
