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

namespace Skinny\Form;

use Skinny\Validate\AbstractValidator;

/**
 * Upload
 *
 * This class provides a simple method to upload files to a given folder
 * on upload from a form.
 *
 * @example
 * <pre>
 * $class = new \Skinny\Form\Upload();
 * $class->setFormFieldName('upload_file');
 * $class->setTargetFolder("/tmp");
 * $class->setValidators(
 *      [
 *          new \Skinny\Validate\File\Image()
 *      ]
 * );
 * if (!$class->receive()) {
 *     print_r($class->getError());
 * } else {
 *     print_r($class->getFileLocation());
 *     print_r($class->getFileName());
 * }
 * </pre>
 * @package Skinny
 * @author  Roger Thomas <roger.thomas@rogerethomas.com>
 */
class Upload
{
    /**
     * @var string|null
     */
    protected ?string $formFieldName = null;

    /**
     * @var string|null
     */
    protected ?string $targetLocationFolder = null;

    /**
     * @var string|null
     */
    protected ?string $uploadedTempName = null;

    /**
     * @var string|null
     */
    protected ?string $uploadedName = null;

    /**
     * @var string|null
     */
    protected ?string $uploadedSize = null;

    /**
     * @var string|null
     */
    protected ?string $uploadedType = null;

    /**
     * @var array
     */
    protected array $validators = [];

    /**
     * @var string|null
     */
    protected ?string $error = null;

    /**
     * @var bool
     */
    protected bool $success = false;

    /**
     * @var string|null
     */
    protected ?string $finalLocation = null;

    /**
     * @var string|null
     */
    protected ?string $finalName = null;

    /**
     * @var string|null
     */
    protected ?string $originalName = null;

    /**
     * Initialise the upload class
     */
    public function __construct()
    {
    }

    /**
     * Set the form field name for the upload
     * @param string $formFieldName
     * @return Upload
     */
    public function setFormFieldName(string $formFieldName): static
    {
        $this->formFieldName = $formFieldName;
        return $this;
    }

    /**
     * Set the target folder location for the upload
     * @param string $targetLocationFolder
     * @return Upload
     */
    public function setTargetFolder(string $targetLocationFolder): static
    {
        if (substr($targetLocationFolder, (strlen($targetLocationFolder) - 1), 1) == '/' || substr($targetLocationFolder, (strlen($targetLocationFolder) - 1), 1) == '\\') {
            $targetLocationFolder = substr($targetLocationFolder, 0, (strlen($targetLocationFolder) - 1));
        }
        $this->targetLocationFolder = $targetLocationFolder;
        return $this;
    }

    /**
     * Set an array of validators to use for the validation
     * @param array $array
     * @return Upload
     */
    public function setValidators(array $array): static
    {
        $this->validators = $array;
        return $this;
    }

    /**
     * Receive the file and return the status of the upload in bool.
     * If failed, then getError() will return the error message for the user.
     * @return bool
     */
    public function receive(): bool
    {
        $this->success = false;

        if (null === $this->targetLocationFolder) {
            return false;
        }

        if (null === $this->formFieldName) {
            return false;
        }

        if (isset($_FILES) && is_array($_FILES)) {
            if (array_key_exists($this->formFieldName, $_FILES)) {
                $file = $_FILES[$this->formFieldName];
                if ($file["error"] > 0 || $file["size"] == 0) {
                    if ($file["error"] == 0 && $file["size"] == 0) {
                        $file['error'] = 9;
                    }
                    $this->error = match ((int)$file["error"]) {
                        1 => "Uploaded file exceeds the maximum size of " . ini_get('upload_max_filesize') . ".",
                        2 => "Uploaded file exceeds the maximum size " . ini_get('upload_max_filesize') . ".",
                        4 => "Please select a valid file to upload",
                        9 => "The uploaded file appears to be empty",
                        default => "An error occurred while uploading the file.",
                    };

                    return false;
                } else {
                    $fileName = $file['name'];

                    if (!str_contains($fileName, '.')) {
                        $this->error = "Invalid file selected";
                        return false;
                    }

                    $originalFileName = $fileName;
                    $fp = explode('.', $fileName);
                    $fp[0] = substr(md5($fileName . microtime()), 0, 16);
                    $fileName = implode('.', $fp);

                    $testPath = $this->targetLocationFolder . DIRECTORY_SEPARATOR . $fileName;

                    // @codeCoverageIgnoreStart
                    if (file_exists($testPath)) {
                        $fp = explode('.', $originalFileName);
                        $fp[0] = substr(md5($originalFileName . uniqid()), 0, 16);
                        $fileName = implode('.', $fp);
                        $testPath = $this->targetLocationFolder . DIRECTORY_SEPARATOR . $fileName;
                    }
                    // @codeCoverageIgnoreEnd

                    if (file_exists($testPath)) {
                        // @codeCoverageIgnoreStart
                        $this->error = "This file already exists";
                        return false;
                        // @codeCoverageIgnoreEnd
                    } else {
                        if (!empty($this->validators)) {
                            foreach ($this->validators as $validator) {
                                /* @var $validator AbstractValidator */
                                if (!$validator->isValid($file["tmp_name"])) {
                                    if (property_exists($validator, 'errorMessage')) {
                                        $this->error = $validator->errorMessage;
                                    } else {
                                        // @codeCoverageIgnoreStart
                                        $this->error = "Invalid file selected";
                                        // @codeCoverageIgnoreEnd
                                    }
                                    return false;
                                }
                                // @codeCoverageIgnoreStart
                            }
                        }
                        // @codeCoverageIgnoreEnd
                        if (@move_uploaded_file($file["tmp_name"], $testPath)) {
                            // @codeCoverageIgnoreStart
                            if (file_exists($testPath)) {
                                $this->originalName = $originalFileName;
                                $this->finalLocation = $testPath;
                                $this->finalName = $fileName;
                                $this->success = true;
                                $this->uploadedName = $file['name'];
                                $this->uploadedTempName = $file['tmp_name'];
                                $this->uploadedType = $file['type'];
                                $this->uploadedSize = $file['size'];
                            } else {
                                $this->error = "An unknown error occurred while uploading the file.";
                            }
                        } else {
                            // @codeCoverageIgnoreEnd
                            $this->error = "An unknown error occurred while uploading the file.";
                        }
                    }
                }
            } else {
                $this->error = "Please choose a valid file to upload";
            }
        } else {
            $this->error = "Please choose a valid file to upload";
        }

        return $this->success;
    }

    /**
     * Retrieve the last error or null for none.
     * @return string|null
     */
    public function getError(): ?string
    {
        return $this->error;
    }

    /**
     * Retrieve the status of the upload.
     * @return bool status
     */
    public function wasSuccess(): bool
    {
        return $this->success;
    }

    /**
     * Retrieve the final location for the file including path.
     * @return bool|string|null (false for not valid)
     * @example /var/www/vhosts/my-site/uploads/picture.jpg
     */
    public function getFileLocation(): bool|string|null
    {
        if (null === $this->finalLocation) {
            return false;
        }
        // @codeCoverageIgnoreStart
        return $this->finalLocation;
        // @codeCoverageIgnoreEnd
    }

    /**
     * Retrieve the original file name
     * @return bool|string|null (false for not valid)
     * @example picture.jpg
     */
    public function getOriginalFileName(): bool|string|null
    {
        if (null === $this->originalName) {
            return false;
        }
        // @codeCoverageIgnoreStart
        return $this->originalName;
        // @codeCoverageIgnoreEnd
    }

    /**
     * Retrieve the final location for the file including path.
     * @return bool|string|null (false for not valid)
     * @example picture.jpg
     */
    public function getFileName(): bool|string|null
    {
        if (null === $this->finalName) {
            return false;
        }
        // @codeCoverageIgnoreStart
        return $this->finalName;
        // @codeCoverageIgnoreEnd
    }
}
