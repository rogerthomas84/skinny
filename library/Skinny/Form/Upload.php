<?php
/**
 * Skinny - a straight forward no-nonsense PHP library
 *
 * @author      Roger Thomas <roger.thomas@rogerethomas.com>
 * @copyright   2013 Roger Thomas
 * @link        http://www.rogerethomas.com
 * @license     http://www.rogerethomas.com/license
 * @version     1.0.0
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
 *      array(
 *          new \Skinny\Validate\File\Image()
 *      )
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
class Upload {

    /**
     * @var string
     */
    private $formFieldName = null;

    /**
     * @var string
     */
    private $targetLocationFolder = null;

    /**
     * @var string
     */
    private $uploadedTempName = null;

    /**
     * @var string
     */
    private $uploadedName = null;

    /**
     * @var string
     */
    private $uploadedSize = null;

    /**
     * @var string
     */
    private $uploadedType = null;

    /**
     * @var array
     */
    private $validators = null;

    /**
     * @var string
     */
    private $error = null;

    /**
     * @var boolean
     */
    private $success = false;

    /**
     * @var string
     */
    private $finalLocation = null;

    /**
     * @var string
     */
    private $finalName = null;

    /**
     * @var string
     */
    private $originalName = null;

    /**
     * @var string
     */
    private $renameType = null;

    /**
     * Initialise the upload class
     */
    public function __construct()
    { }

    /**
     * Set the form field name for the upload
     * @param string $formFieldName
     */
    public function setFormFieldName($formFieldName)
    {
        $this->formFieldName = $formFieldName;
    }

    /**
     * Set the target folder location for the upload
     * @param string $targetLocationFolder
     */
    public function setTargetFolder($targetLocationFolder)
    {
        if (substr($targetLocationFolder, (strlen($targetLocationFolder) - 1), 1) == '/' || substr($targetLocationFolder, (strlen($targetLocationFolder) - 1), 1) == '\\') {
            $targetLocationFolder = substr($targetLocationFolder, 0, (strlen($targetLocationFolder) - 1) );
        }
        $this->targetLocationFolder = $targetLocationFolder;
    }

    /**
     * Set an array of validators to use for the validation
     * @param array $array
     */
    public function setValidators($array)
    {
        $this->validators = $array;
    }

    /**
     * Receive the file and return the status of the upload in boolean.
     * If failed, then getError() will return the error message for the user.
     * @return boolean
     */
    public function receive()
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
                    if ($file["size"] == 0) {
                        $file['error'] = 9;
                    }
                    switch((int)$file["error"]) {
                    	case(1):
                    	    $this->error = "Uploaded file exceeds the maximum size of " . ini_get('upload_max_filesize') . ".";
                    	    break;
                    	case(2):
                    	    $this->error = "Uploaded file exceeds the maximum size " . ini_get('upload_max_filesize') . ".";
                    	    break;
                    	case(4):
                    	    $this->error = "Please select a valid file to upload";
                    	    break;
                    	case(9):
                    	    $this->error = "The uploaded file appears to be empty";
                    	    break;
                    	default:
                    	    $this->error = "An error occured while uploading the file.";
                    	    break;
                    }

                	return false;
                } else {
                    $fileName = $file['name'];

                    if (!strstr($fileName, '.')) {
                        $this->error = "Invalid file selected";
                        return false;
                    }

                    $originalFileName = $fileName;
                    $fp = explode('.', $fileName);
                    $fp[0] = substr(md5($fileName . microtime()), 0, 16);
                    $fileName = implode('.', $fp);

                    $testPath = $this->targetLocationFolder . DIRECTORY_SEPARATOR . $fileName;

                    if (file_exists($testPath)) {
                        $fp = explode('.', $originalFileName);
                        $fp[0] = substr(md5($originalFileName . uniqid()), 0, 16);
                        $fileName = implode('.', $fp);
                        $testPath = $this->targetLocationFolder . DIRECTORY_SEPARATOR . $fileName;
                    }

                    if (file_exists($testPath)) {
                        $this->error = "This file already exists";
                        return false;
                    } else {
                        if (!empty($this->validators)) {
                            foreach ($this->validators as $validator) {
    		                    /* @var $validator \Skinny\Validate\AbstractValidator */
                                if (!$validator->isValid($file["tmp_name"])) {
                                    if (property_exists($validator, 'errorMessage')) {
                                        $this->error = $validator->errorMessage;
                                    } else {
                                        $this->error = "Invalid file selected";
                                    }
                                    return false;
                                }
                            }
                        }
                        if (@move_uploaded_file($file["tmp_name"], $testPath)) {
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
                                $this->error = "An unknown error occured while uploading the file.";
                            }
                        } else {
                            $this->error = "An unknown error occured while uploading the file.";
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
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Retrieve the status of the upload.
     * @return boolean status
     */
    public function wasSuccess()
    {
        return $this->success;
    }

    /**
     * Retrieve the final location for the file including path.
     * @example /var/www/vhosts/mysite/uploads/picture.jpg
     * @return string | boolean false for not valid
     */
    public function getFileLocation()
    {
        if (null === $this->finalLocation) {
            return false;
        }

        return $this->finalLocation;
    }

    /**
     * Retrieve the original file name
     * @example picture.jpg
     * @return string | boolean false for not valid
     */
    public function getOriginalFileName()
    {
        if (null === $this->originalName) {
            return false;
        }

        return $this->originalName;
    }

    /**
     * Retrieve the final location for the file including path.
     * @example picture.jpg
     * @return string | boolean false for not valid
     */
    public function getFileName()
    {
        if (null === $this->finalName) {
            return false;
        }

        return $this->finalName;
    }

    /**
     * Append digit onto filename and call self method in a loop
     * until a non existent filename is located.
     *
     * @param string $fileName
     * @return string
     */
    protected function _getForcedIgnoreExisting($fileName)
    {
        $fileName = rand(1,9) . $fileName;
        $targetPath = $this->targetLocationFolder . DIRECTORY_SEPARATOR . $fileName;
        if (file_exists($targetPath)) {
            return $this->_getForcedIgnoreExisting($fileName);
        }

        return $fileName;
    }
}
