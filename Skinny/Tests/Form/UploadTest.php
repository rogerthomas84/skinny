<?php
namespace Skinny\Tests\Validate;

use Skinny\Form\Upload;
use Skinny\Validate\File\Image;

class UploadTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Upload|null
     */
    private $form = null;

    public function setUp()
    {
        $this->reset();
    }

    private function reset()
    {
        $this->form = new Upload();
    }

    public function testInvalidVariable()
    {
        $this->backupGlobalsBlacklist[] = $_FILES;
        $_FILES = 'abc';
        $this->reset();
        $this->assertFalse($this->form->receive());
        $this->form->setTargetFolder(sys_get_temp_dir());
        $this->assertFalse($this->form->receive());
    }

    public function testNothingSet()
    {
        $this->reset();
        $this->assertFalse($this->form->receive());
        $this->form->setTargetFolder(sys_get_temp_dir());
        $this->assertFalse($this->form->receive());
    }

    private function makeRandomFile()
    {
        $fileName = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'Skinny_Tests' . time() . '.txt';
        $handle = fopen($fileName, 'w');
        fwrite($handle, __CLASS__);
        @fclose($handle);
        return $fileName;
    }

    public function testNoErrorEmptyFile()
    {
        $this->reset();
        $fileName = $this->makeRandomFile();
        $_FILES = array(
            'abc' => array(
                'error' => 0,
                'size' => 0,
                'name' => basename($fileName),
                'tmp_name' => $fileName,
            )
        );
        $this->form->setFormFieldName('abc');

        $this->form->setTargetFolder(sys_get_temp_dir());
        $this->assertFalse($this->form->receive());
        $this->assertContains('uploaded file appears to be empty', $this->form->getError());
        @unlink($fileName);
    }

    public function testErrorOneFile()
    {
        $this->reset();
        $fileName = $this->makeRandomFile();
        $_FILES = array(
            'abc' => array(
                'error' => 1,
                'size' => 0,
                'name' => basename($fileName),
                'tmp_name' => $fileName,
            )
        );
        $this->form->setFormFieldName('abc');

        $this->form->setTargetFolder(sys_get_temp_dir());
        $this->form->receive();
        $this->assertFalse($this->form->receive());
        $this->assertContains('Uploaded file exceeds the maximum size', $this->form->getError());
        @unlink($fileName);
    }

    public function testErrorTwoFile()
    {
        $this->reset();
        $fileName = $this->makeRandomFile();
        $_FILES = array(
            'abc' => array(
                'error' => 2,
                'size' => 0,
                'name' => basename($fileName),
                'tmp_name' => $fileName,
            )
        );
        $this->form->setFormFieldName('abc');

        $this->form->setTargetFolder(sys_get_temp_dir());
        $this->form->receive();
        $this->assertFalse($this->form->receive());
        $this->assertContains('Uploaded file exceeds the maximum size', $this->form->getError());
        @unlink($fileName);
    }

    public function testErrorThreeFile()
    {
        $this->reset();
        $fileName = $this->makeRandomFile();
        $_FILES = array(
            'abc' => array(
                'error' => 3,
                'size' => 0,
                'name' => basename($fileName),
                'tmp_name' => $fileName,
            )
        );
        $this->form->setFormFieldName('abc');

        $this->form->setTargetFolder(sys_get_temp_dir());
        $this->form->receive();
        $this->assertFalse($this->form->receive());
        $this->assertContains('error occurred while uploading', $this->form->getError());
        @unlink($fileName);
    }

    public function testErrorFourFile()
    {
        $this->reset();
        $fileName = $this->makeRandomFile();
        $_FILES = array(
            'abc' => array(
                'error' => 4,
                'size' => 0,
                'name' => basename($fileName),
                'tmp_name' => $fileName,
            )
        );
        $this->form->setFormFieldName('abc');

        $this->form->setTargetFolder(sys_get_temp_dir());
        $this->form->receive();
        $this->assertFalse($this->form->receive());
        $this->assertContains('select a valid file', $this->form->getError());
        @unlink($fileName);
    }

    public function testInvalidFile()
    {
        $this->reset();
        $fileName = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'Skinny_Tests' . time();
        $handle = fopen($fileName, 'w');
        fwrite($handle, __CLASS__);
        @fclose($handle);
        $_FILES = array(
            'abc' => array(
                'error' => 0,
                'size' => 100,
                'name' => basename($fileName),
                'tmp_name' => $fileName,
            )
        );
        $this->form->setFormFieldName('abc');

        $this->form->setTargetFolder(sys_get_temp_dir());
        $this->assertFalse($this->form->receive());
        @unlink($fileName);
        $this->assertContains('Invalid file selected', $this->form->getError());
    }

    public function testBasic()
    {
        $this->reset();
        $_FILES = array();
        $this->form->setFormFieldName('abc');
        $this->form->setTargetFolder(sys_get_temp_dir());
        $this->form->setValidators(array());
        $this->assertEmpty($this->form->getError());
        $this->assertFalse($this->form->getFileName());
        $this->assertFalse($this->form->wasSuccess());
        $this->assertFalse($this->form->getFileLocation());
        $this->assertFalse($this->form->getOriginalFileName());
        $this->assertFalse($this->form->receive());
    }

    public function testImageValidatorReceive()
    {
        $this->reset();
        $file = $this->makeRandomFile();
        $_FILES = array(
            'abc' => array(
                'error' => 0,
                'size' => 100,
                'name' => basename($file),
                'tmp_name' => $file,
            )
        );
        $this->form->setFormFieldName('abc');
        $this->form->setValidators(array(new Image()));
        $this->form->setTargetFolder(sys_get_temp_dir());
        $this->assertFalse($this->form->receive());
        @unlink($file);
    }

    public function testReceiveInvalidFilesVariable()
    {
        $this->reset();
        $_FILES = (object)$_FILES; // case to object.
        $this->form->setFormFieldName('abc');
        $this->form->setTargetFolder(sys_get_temp_dir());
        $this->assertFalse($this->form->receive());
    }

    public function testFunctionalReceiveDuplicatedName()
    {
        $this->reset();
        $file = $this->makeRandomFile();
        $_FILES = array(
            'abc' => array(
                'error' => 0,
                'size' => 100,
                'name' => basename($file),
                'tmp_name' => $file,
            )
        );
        $this->form->setFormFieldName('abc');
        $this->form->setTargetFolder(dirname($file));
        $this->assertFalse($this->form->receive());
        $this->assertContains('unknown error occurred while uploading', $this->form->getError());
        @unlink($file);
    }
}
