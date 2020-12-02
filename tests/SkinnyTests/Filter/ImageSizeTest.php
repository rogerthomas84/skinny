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
use Skinny\Filter\ImageSize;

class ImageSizeTest extends TestCase
{
    private $jpgPath = null;
    private $pngPath = null;
    private $gifPath = null;

    public function setUp()
    {
        $path = sys_get_temp_dir();

        $im = imagecreatetruecolor(120, 20);
        $text_color = imagecolorallocate($im, 233, 14, 91);
        imagestring($im, 1, 5, 5,  'A Simple Text String', $text_color);
        $this->jpgPath = $path . DIRECTORY_SEPARATOR . implode('_', explode('\\', __CLASS__)) . '.jpg';
        if (file_exists($this->jpgPath)) {
            @unlink($this->jpgPath);
        }
        imagejpeg($im, $this->jpgPath);
        imagedestroy($im);

        // Make PNG
        $this->pngPath = $path . DIRECTORY_SEPARATOR . implode('_', explode('\\', __CLASS__)) . '.png';
        if (file_exists($this->pngPath)) {
            @unlink($this->pngPath);
        }
        $png = imagecreatetruecolor(100,100);
        imagesavealpha($png, true);
        $pngColour = imagecolorallocatealpha($png, 0x00, 0x00, 0x00, 127);
        imagefill($png, 0, 0, $pngColour);
        imagepng($png, $this->pngPath);
        imagedestroy($png);

        // Make Gif
        $this->gifPath = $path . DIRECTORY_SEPARATOR . implode('_', explode('\\', __CLASS__)) . '.gif';
        if (file_exists($this->gifPath)) {
            @unlink($this->gifPath);
        }
        $gif = imagecreatetruecolor(100, 100);
        imagefilledrectangle($gif, 0, 0, 99, 99, 0xFFFFFF);
        imagegif($gif, $this->gifPath);
        imagedestroy($gif);
    }

    public function testInvalidConstruct()
    {
        try {
            $filter = @new ImageSize('/this/doesnt/exist/anywhere.');
        } catch (\Exception $e) {
            $this->assertInstanceOf('Skinny\BaseException', $e);
            return;
        }
        $this->fail('expected exception due to null construct.');
    }

    public function testInit()
    {
        $filterJpg = new ImageSize($this->jpgPath);
        $this->assertGreaterThan(1, $filterJpg->getFileInfo());

        $filterPng = new ImageSize($this->pngPath);
        $this->assertGreaterThan(1, $filterPng->getFileInfo());

        $filterGif = new ImageSize($this->gifPath);
        $this->assertGreaterThan(1, $filterGif->getFileInfo());
    }

    public function testWidth()
    {
        $filterJpg = new ImageSize($this->jpgPath);
        $filterJpg->toWidth(10);
        $fileJpg = sys_get_temp_dir() . DIRECTORY_SEPARATOR . implode('_', explode('\\', __CLASS__));
        $filterJpg->setOutput($fileJpg, 60, '777');
        $this->assertFileExists($fileJpg);
        @unlink($fileJpg);

        $filterPng = new ImageSize($this->pngPath);
        $filterPng->toWidth(10);
        $filePng = sys_get_temp_dir() . DIRECTORY_SEPARATOR . implode('_', explode('\\', __CLASS__));
        $filterPng->setOutput($filePng, 60, '777');
        $this->assertFileExists($filePng);
        @unlink($filePng);

        $filterGif = new ImageSize($this->gifPath);
        $filterGif->toWidth(10);
        $fileGif = sys_get_temp_dir() . DIRECTORY_SEPARATOR . implode('_', explode('\\', __CLASS__));
        $filterGif->setOutput($fileGif, 60, '777');
        $this->assertFileExists($fileGif);
        @unlink($fileGif);
    }

    public function testHeight()
    {
        $filterJpg = new ImageSize($this->jpgPath);
        $filterJpg->toHeight(10);
        $fileJpg = sys_get_temp_dir() . DIRECTORY_SEPARATOR . implode('_', explode('\\', __CLASS__));
        $filterJpg->setOutput($fileJpg);
        $this->assertFileExists($fileJpg);
        @unlink($fileJpg);

        $filterPng = new ImageSize($this->pngPath);
        $filterPng->toHeight(10);
        $filePng = sys_get_temp_dir() . DIRECTORY_SEPARATOR . implode('_', explode('\\', __CLASS__));
        $filterPng->setOutput($filePng);
        $this->assertFileExists($filePng);
        @unlink($filePng);

        $filterGif = new ImageSize($this->gifPath);
        $filterGif->toHeight(10);
        $fileGif = sys_get_temp_dir() . DIRECTORY_SEPARATOR . implode('_', explode('\\', __CLASS__));
        $filterGif->setOutput($fileGif);
        $this->assertFileExists($fileGif);
        @unlink($fileGif);
    }

    public function testPercentage()
    {
        $filterJpg = new ImageSize($this->jpgPath);
        $filterJpg->toPercentage(10);
        $fileJpg = sys_get_temp_dir() . DIRECTORY_SEPARATOR . implode('_', explode('\\', __CLASS__));
        $filterJpg->setOutput($fileJpg);
        $this->assertFileExists($fileJpg);
        @unlink($fileJpg);

        $filterPng = new ImageSize($this->pngPath);
        $filterPng->toPercentage(10);
        $filePng = sys_get_temp_dir() . DIRECTORY_SEPARATOR . implode('_', explode('\\', __CLASS__));
        $filterPng->setOutput($filePng);
        $this->assertFileExists($filePng);
        @unlink($filePng);

        $filterGif = new ImageSize($this->gifPath);
        $filterGif->toPercentage(10);
        $fileGif = sys_get_temp_dir() . DIRECTORY_SEPARATOR . implode('_', explode('\\', __CLASS__));
        $filterGif->setOutput($fileGif);
        $this->assertFileExists($fileGif);
        @unlink($fileGif);
    }
}
