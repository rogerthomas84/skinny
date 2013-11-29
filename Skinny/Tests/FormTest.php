<?php
namespace Skinny\Tests;

use Skinny\Form;
use Skinny\Validate\NotEmpty;

class FormTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var array
     */
    private $routes = array();

    /**
     * @var Form|null
     */
    private $form = null;

    public function setUp()
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
        $result = $this->form->isValid(array('test1' => null));
        $this->assertFalse($result);
    }

    public function testRequiredValidWorks()
    {
        $this->reset();
        $this->form->addElement('test1', true);
        $result = $this->form->isValid(array('test1' => '123'));
        $this->assertTrue($result);

        $resultFour = $this->form->isValid(array('test1' => array('yes', 'its', 'ok')));
        $this->assertTrue($resultFour);
    }

    public function testRequiredEmptyArrayFails()
    {
        $this->reset();
        $this->form->addElement('test1', true);
        $result = $this->form->isValid(array('test1' => array()));
        $this->assertFalse($result);
    }

    public function testRequiredFullArrayFails()
    {
        $this->reset();
        $this->form->addElement('test1', true);
        $result = $this->form->isValid(array('test1' => array('yes', 'its', 'ok')));
        $this->assertTrue($result);
    }

    public function testExtraFieldStillPasses()
    {
        $this->reset();
        $this->form->addElement('test1', true);
        $result = $this->form->isValid(array('another' => 'joe', 'test1' => array('yes', 'its', 'ok')));
        $this->assertTrue($result);
    }

    public function testNonArrayParamsFails()
    {
        $this->reset();
        $this->form->addElement('test1', true);
        $result = $this->form->isValid('I should not be a string');
        $this->assertFalse($result);
    }

    public function testPostParamMissingKeyPasses()
    {
        $this->reset();
        $this->form->addElement('test1', true);
        $result = $this->form->isValid(array('another' => 'joe'));
        $this->assertFalse($result);
    }

    public function testErrorMessageExpected()
    {
        $this->reset();
        $this->form->addElement('test1', true, array(new NotEmpty()), 'Test field one');
        $result = $this->form->isValid(array('test1' => ''));
        $this->assertFalse($result);
        $errors = $this->form->getErrors();
        $this->assertCount(1, $errors);
        $this->assertArrayHasKey('test1', $errors);
        $this->assertCount(1, $errors['test1']);
    }
}
