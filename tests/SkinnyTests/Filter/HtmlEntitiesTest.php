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
namespace SkinnyTests\Filter;

use PHPUnit\Framework\TestCase;
use Skinny\Filter\HtmlEntities;

class HtmlEntitiesTest extends TestCase
{
    public function setUp()
    {
    }

    public function testBasicSetup()
    {
        $valuesExpected = array(
            'string' => 'string',
            '<' => '&lt;',
            '>' => '&gt;',
            '\'' => '\'',
            '"' => '&quot;',
            '&' => '&amp;'
        );
        foreach ($valuesExpected as $input => $output) {
            $filter = new HtmlEntities();
            $this->assertEquals(
                $output,
                $filter->filter($input)
            );
        }
    }

    public function testConstruct()
    {
        $filter = new HtmlEntities(array('charset' => 'UTF-8'));
        $this->assertEquals(
            'UTF-8',
            $filter->getCharSet()
        );
    }

    public function testHexadecimal()
    {
        $filter = new HtmlEntities(array('charset' => 'UTF-8'));
        $this->assertEquals(
            '154',
            $filter->filter(hexdec('9a'))
        );
    }

    public function testGettersAndSetters()
    {
        $filter = new HtmlEntities();
        $filter->setCharSet('UTF-8');
        $this->assertEquals(
            'UTF-8',
            $filter->getCharSet()
        );

        $filter->setDoubleQuote(true);
        $this->assertTrue($filter->getDoubleQuote());

        $filter->setQuoteStyle(ENT_COMPAT);
        $this->assertEquals($filter->getQuoteStyle(), ENT_COMPAT);
    }

    public function testAmpersand()
    {
        $filter = new HtmlEntities();
        $this->assertEquals(
            'Bill &amp; Ben',
            $filter->filter('Bill & Ben')
        );
    }

    public function testEncodedQuotes()
    {
        $filter = new HtmlEntities();
        $filter->setQuoteStyle(ENT_QUOTES);
        $filter->setDoubleQuote(true);
        $this->assertEquals(
            'One &quot;day&quot;.',
            $filter->filter('One "day".')
        );
        $this->assertEquals(
            'One &#039;day&#039;.',
            $filter->filter('One \'day\'.')
        );
    }

    public function testEncodedDoubleQuotes()
    {
        $filter = new HtmlEntities();
        $filter->setQuoteStyle(ENT_COMPAT);
        $filter->setDoubleQuote(false);
        $this->assertEquals(
            'One &quot;day&quot;.',
            $filter->filter('One "day".')
        );
    }

    public function testDash()
    {
        $filter = new HtmlEntities();
        $this->assertEquals(
            '&mdash;',
            $filter->filter('—')
        );
    }
}
