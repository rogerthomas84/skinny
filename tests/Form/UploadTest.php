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
namespace SkinnyTests\Form;

use PHPUnit\Framework\TestCase;
use Skinny\Form\Upload;
use Skinny\Validate\File\Image;

class UploadTest extends TestCase
{
    /**
     * @var Upload|null
     */
    private ?Upload $form = null;

    public function setUp(): void
    {
        $this->reset();
    }

    private function reset()
    {
        $this->form = new Upload();
    }

    public function testInvalidVariable()
    {
        $this->backupGlobalsExcludeList[] = $_FILES;
        $_FILES = 'abc';
        $this->reset();
        $this->assertFalse($this->form->receive());
        $this->form->setTargetFolder(sys_get_temp_dir());
        $this->assertFalse($this->form->receive());
    }

    public function testInvalidPath()
    {
        $this->reset();
        $this->form->setTargetFolder('\\');
        $this->assertFalse($this->form->getFileLocation());
    }

    public function testNothingSet()
    {
        $this->reset();
        $this->assertFalse($this->form->receive());
        $this->form->setTargetFolder(sys_get_temp_dir());
        $this->assertFalse($this->form->receive());
    }

    private function makeRandomFile(): string
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
        /** @noinspection PhpArrayWriteIsNotUsedInspection */
        $_FILES = [
            'abc' => [
                'error' => 0,
                'size' => 0,
                'name' => basename($fileName),
                'tmp_name' => $fileName,
            ]
        ];
        $this->form->setFormFieldName('abc');

        $this->form->setTargetFolder(sys_get_temp_dir());
        $this->assertFalse($this->form->receive());
        $this->assertStringContainsString(
            'uploaded file appears to be empty', $this->form->getError()
        );
        @unlink($fileName);
    }

    public function testErrorOneFile()
    {
        $this->reset();
        $fileName = $this->makeRandomFile();
        /** @noinspection PhpArrayWriteIsNotUsedInspection */
        $_FILES = [
            'abc' => [
                'error' => 1,
                'size' => 0,
                'name' => basename($fileName),
                'tmp_name' => $fileName,
            ]
        ];
        $this->form->setFormFieldName('abc');

        $this->form->setTargetFolder(sys_get_temp_dir());
        $this->form->receive();
        $this->assertFalse($this->form->receive());
        $this->assertStringContainsString('Uploaded file exceeds the maximum size', $this->form->getError());
        @unlink($fileName);
    }

    public function testErrorTwoFile()
    {
        $this->reset();
        $fileName = $this->makeRandomFile();
        /** @noinspection PhpArrayWriteIsNotUsedInspection */
        $_FILES = [
            'abc' => [
                'error' => 2,
                'size' => 0,
                'name' => basename($fileName),
                'tmp_name' => $fileName,
            ]
        ];
        $this->form->setFormFieldName('abc');

        $this->form->setTargetFolder(sys_get_temp_dir());
        $this->form->receive();
        $this->assertFalse($this->form->receive());
        $this->assertStringContainsString('Uploaded file exceeds the maximum size', $this->form->getError());
        @unlink($fileName);
    }

    public function testErrorThreeFile()
    {
        $this->reset();
        $fileName = $this->makeRandomFile();
        /** @noinspection PhpArrayWriteIsNotUsedInspection */
        $_FILES = [
            'abc' => [
                'error' => 3,
                'size' => 0,
                'name' => basename($fileName),
                'tmp_name' => $fileName,
            ]
        ];
        $this->form->setFormFieldName('abc');

        $this->form->setTargetFolder(sys_get_temp_dir());
        $this->form->receive();
        $this->assertFalse($this->form->receive());
        $this->assertStringContainsString('error occurred while uploading', $this->form->getError());
        @unlink($fileName);
    }

    public function testErrorFourFile()
    {
        $this->reset();
        $fileName = $this->makeRandomFile();
        /** @noinspection PhpArrayWriteIsNotUsedInspection */
        $_FILES = [
            'abc' => [
                'error' => 4,
                'size' => 0,
                'name' => basename($fileName),
                'tmp_name' => $fileName,
            ]
        ];
        $this->form->setFormFieldName('abc');

        $this->form->setTargetFolder(sys_get_temp_dir());
        $this->form->receive();
        $this->assertFalse($this->form->receive());
        $this->assertStringContainsString('select a valid file', $this->form->getError());
        @unlink($fileName);
    }

    public function testInvalidFile()
    {
        $this->reset();
        $fileName = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'Skinny_Tests' . time();
        $handle = fopen($fileName, 'w');
        fwrite($handle, __CLASS__);
        @fclose($handle);
        /** @noinspection PhpArrayWriteIsNotUsedInspection */
        $_FILES = [
            'abc' => [
                'error' => 0,
                'size' => 100,
                'name' => basename($fileName),
                'tmp_name' => $fileName,
            ]
        ];
        $this->form->setFormFieldName('abc');

        $this->form->setTargetFolder(sys_get_temp_dir());
        $this->assertFalse($this->form->receive());
        @unlink($fileName);
        $this->assertStringContainsString('Invalid file selected', $this->form->getError());
    }

    public function testBasic()
    {
        $this->reset();
        $_FILES = [];
        $this->form->setFormFieldName('abc');
        $this->form->setTargetFolder(sys_get_temp_dir());
        $this->form->setValidators([]);
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
        /** @noinspection PhpArrayWriteIsNotUsedInspection */
        $_FILES = [
            'abc' => [
                'error' => 0,
                'size' => 100,
                'name' => basename($file),
                'tmp_name' => $file,
            ]
        ];
        $this->form->setFormFieldName('abc');
        $this->form->setValidators([new Image()]);
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
        /** @noinspection PhpArrayWriteIsNotUsedInspection */
        $_FILES = [
            'abc' => [
                'error' => 0,
                'size' => 100,
                'name' => basename($file),
                'tmp_name' => $file,
            ]
        ];
        $this->form->setFormFieldName('abc');
        $this->form->setTargetFolder(dirname($file));
        $this->assertFalse($this->form->receive());
        $this->assertStringContainsString('unknown error occurred while uploading', $this->form->getError());
        @unlink($file);
    }
}
