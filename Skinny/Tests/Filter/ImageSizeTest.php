<?php
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
