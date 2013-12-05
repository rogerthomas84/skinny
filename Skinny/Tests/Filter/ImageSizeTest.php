<?php
/**
 * Skinny - a straight forward no-nonsense PHP library
 *
 * @author      Roger Thomas <roger.thomas@rogerethomas.com>
 * @copyright   2013 Roger Thomas
 * @link        http://www.rogerethomas.com
 * @license     http://www.rogerethomas.com/license
 * @version     2.0.3
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
namespace Skinny\Tests\Validate;

use Skinny\Filter\ImageSize;

class ImageSizeTest extends \PHPUnit_Framework_TestCase
{
    private $path = null;

    public function setUp()
    {
        $im = imagecreatetruecolor(120, 20);
        $text_color = imagecolorallocate($im, 233, 14, 91);
        imagestring($im, 1, 5, 5,  'A Simple Text String', $text_color);
        $path = sys_get_temp_dir();
        $this->path = $path . DIRECTORY_SEPARATOR . implode('_', explode('\\', __CLASS__)) . '.jpg';
        if (file_exists($this->path)) {
            @unlink($this->path);
        }
        imagejpeg($im, $this->path);
        imagedestroy($im);
    }

    public function testInvalidConstruct()
    {
        try {
            $filter = @new ImageSize('/this/doesnt/exist/anywhere.');
        } catch (\Exception $e) {
            $this->assertInstanceOf('Skinny\Exception', $e);
            return;
        }
        $this->fail('expected exception due to null construct.');
    }

    public function testInit()
    {
        $filter = new ImageSize($this->path);
        $this->assertGreaterThan(1, $filter->getFileInfo());
    }

    public function testWidth()
    {
        $filter = new ImageSize($this->path);
        $filter->toWidth(10);
        $file = sys_get_temp_dir() . DIRECTORY_SEPARATOR . implode('_', explode('\\', __CLASS__));
        $filter->setOutput($file);
        $this->assertFileExists($file);
        @unlink($file);
    }

    public function testHeight()
    {
        $filter = new ImageSize($this->path);
        $filter->toHeight(10);
        $file = sys_get_temp_dir() . DIRECTORY_SEPARATOR . implode('_', explode('\\', __CLASS__));
        $filter->setOutput($file);
        $this->assertFileExists($file);
        @unlink($file);
    }

    public function testPercentage()
    {
        $filter = new ImageSize($this->path);
        $filter->toPercentage(50);
        $file = sys_get_temp_dir() . DIRECTORY_SEPARATOR . implode('_', explode('\\', __CLASS__));
        $filter->setOutput($file);
        $this->assertFileExists($file);
        @unlink($file);
    }
}
