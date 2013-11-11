<?php
namespace Skinny\Tests\Validate;

use Skinny\Validate\NotEmpty;

class NotEmptyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var NotEmpty|null
     */
    private $validator = null;

    public function setUp()
    {
        $this->validator = new NotEmpty();
    }

    public function testNotEmptyVariablesWork()
    {
        $this->assertTrue($this->validator->isValid('abc'));
    }

    public function testEmptyVariablesFail()
    {
        $this->assertFalse($this->validator->isValid(''));
        $this->assertFalse($this->validator->isValid(null));
        $this->assertFalse($this->validator->isValid(false));
        $this->assertFalse($this->validator->isValid(array()));
    }
}
