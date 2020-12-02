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
namespace SkinnyTests\Validate\File;

use PHPUnit\Framework\TestCase;
use Skinny\Validate\File\Image;

class ImageTest extends TestCase
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

    public function testValidImage()
    {
        $this->assertFileExists($this->path);
        $validator = new Image();
        $result = $validator->isValid($this->path);
        $this->assertTrue($result);
    }

    public function testInvalidImage()
    {
        $path = sys_get_temp_dir();
        $path .= DIRECTORY_SEPARATOR . implode('_', explode('\\', __CLASS__)) . '.txt';
        if (file_exists($path)) {
            @unlink($path);
        }
        $handle = fopen($path, 'w');
        fwrite($handle, 'abc');
        @fclose($handle);

        $this->assertFileExists($path);
        $validator = new Image();
        $result = $validator->isValid($path);
        $this->assertFalse($result);
    }
}
