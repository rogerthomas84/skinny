<?php
namespace Skinny\Tests\Validate;

use Skinny\Validate\Date;

class DateTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
    }

    public function testValidDateWorks()
    {
        $date = new Date('Y-m-d H:i:s');
        $this->assertTrue($date->isValid('2013-01-01 23:59:59'));
    }

    public function testInvalidDateFails()
    {
        $date = new Date('Y-m-d H:i:s');
        $this->assertFalse($date->isValid('13-01-01 23:59:59'));
    }
}
