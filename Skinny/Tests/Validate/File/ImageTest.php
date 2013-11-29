<?php
namespace Skinny\Tests\Validate\File;

use Skinny\Validate\File\Image;

class ImageTest extends \PHPUnit_Framework_TestCase
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
