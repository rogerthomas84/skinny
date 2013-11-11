<?php
namespace Skinny\Tests\Validate;

use Skinny\Validate\EmailAddress;

class EmailAddressTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
    }

    public function testValidEmailAddressWorks()
    {
        $validator = new EmailAddress();
        $this->assertEquals($validator->isValid('joe@bloggs.com'), 'joe@bloggs.com');
    }

    public function testInvalidEmailAddressFails()
    {
        $validator = new EmailAddress();
        $this->assertFalse($validator->isValid('someone@localhost'));
    }
}
