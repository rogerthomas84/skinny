<?php
namespace Skinny\Tests\Validate;

use Skinny\Filter\HtmlEntities;

class HtmlEntitiesTest extends \PHPUnit_Framework_TestCase
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
