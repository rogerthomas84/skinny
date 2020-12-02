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

namespace Skinny\Filter;

use Skinny\BaseException;

/**
 * ImageSize
 *
 * This class extends the basic functionality to interact and manipulate
 * an image size.
 *
 * @package Skinny
 * @author  Roger Thomas <roger.thomas@rogerethomas.com>
 */
class ImageSize
{
    /**
     * Holds the data of the image.
     * @var string
     */
    private $file;

    /**
     * Type of image
     * @var int
     */
    private $type;

    /**
     * Holds array of getimagesize return
     * @var array
     */
    private $fileInfo = array(
        0 => 0,
        1 => 0
    );

    /**
     * Construct giving a filename
     *
     * @param string $filename
     * @throws BaseException
     */
    public function __construct($filename)
    {
        $this->fileInfo = getimagesize($filename);
        if (!is_array($this->fileInfo)) {
            throw new BaseException('Invalid image file provided ' . $filename);
        }
        $this->type = $this->fileInfo[2];
        if ($this->type == IMAGETYPE_PNG) {
            $this->file = imagecreatefrompng($filename);
        } elseif ($this->type == IMAGETYPE_GIF) {
            $this->file = imagecreatefromgif($filename);
        } elseif ($this->type == IMAGETYPE_JPEG) {
            $this->file = imagecreatefromjpeg($filename);
        }
    }

    /**
     * Retrieve the result of getimagesize()
     * @return array
     */
    public function getFileInfo()
    {
        return $this->fileInfo;
    }

    /**
     * Give the target details for the output.
     *
     * @param string $filename
     * @param int $compress
     * @param int|null $chmod
     */
    public function setOutput($filename, $compress = 100, $chmod = null)
    {
        if ($this->type == IMAGETYPE_PNG) {
            imagepng($this->file, $filename);
        } elseif ($this->type == IMAGETYPE_GIF) {
            imagegif($this->file, $filename);
        } elseif ($this->type == IMAGETYPE_JPEG) {
            imagejpeg($this->file, $filename, $compress);
        }

        if ($chmod != null) {
            chmod($filename, $chmod);
        }
    }

    /**
     * Specify a height to resize the image to. Width will be
     * automatically calculated
     *
     * @param int $height
     * @throws BaseException
     */
    public function toHeight($height)
    {
        $ratio = $height / imagesy($this->file);
        $width = imagesx($this->file) * $ratio;
        $this->toDimensions($width, $height);
    }

    /**
     * Specify a width to resize the image to. Height will be
     * automatically calculated
     *
     * @param int $width
     * @throws BaseException
     */
    public function toWidth($width)
    {
        $ratio = $width / imagesx($this->file);
        $height = imagesy($this->file) * $ratio;
        $this->toDimensions($width, $height);
    }

    /**
     * Resize the given image to a percentage of its size.
     *
     * @param int $percent
     * @throws BaseException
     */
    public function toPercentage($percent)
    {
        $width = imagesx($this->file) * $percent / 100;
        $height = imagesy($this->file) * $percent / 100;
        $this->toDimensions(
            $width,
            $height
        );
    }

    /**
     * Resize the given image to a fixed width/height
     *
     * @param int $width
     * @param int $height
     * @throws BaseException
     */
    public function toDimensions($width, $height)
    {
        $blank = imagecreatetruecolor($width, $height);
        $status = imagecopyresampled(
            $blank,
            $this->file,
            0,
            0,
            0,
            0,
            $width,
            $height,
            imagesx($this->file),
            imagesy($this->file)
        );
        if (!$status) {
            // @codeCoverageIgnoreStart
            throw new BaseException('Image creation failed.');
            // @codeCoverageIgnoreEnd
        }
        $this->file = $blank;
    }
}
