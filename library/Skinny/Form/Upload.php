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
 * $class->setForceUploadRename(true);
 * $class->setRenameMethod($class::RENAME_PREPEND_TIMESTAMP);
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
     *
     * @var string
     */
    const RENAME_PREPEND_TIMESTAMP = "RENAME_PREPEND_TIMESTAMP";

    /**
     *
     * @var string
     */
    const RENAME_PREPEND_RANDOM = "RENAME_PREPEND_RANDOM";

    /**
     *
     * @var string
     */
    private $formFieldName = null;

    /**
     *
     * @var string
     */
    private $targetLocationFolder = null;

    /**
     *
     * @var string
     */
    private $uploadedTempName = null;

    /**
     *
     * @var string
     */
    private $uploadedName = null;

    /**
     *
     * @var string
     */
    private $uploadedSize = null;

    /**
     *
     * @var string
     */
    private $uploadedType = null;

    /**
     *
     * @var string
     */
    private $error = null;

    /**
     *
     * @var boolean
     */
    private $success = false;

    /**
     *
     * @var string
     */
    private $finalLocation = null;

    /**
     *
     * @var string
     */
    private $finalName = null;

    /**
     *
     * @var boolean
     */
    private $forceRenameSuccess = false;

    /**
     *
     * @var string
     */
    private $renameType = null;

    /**
     *
     * @var string
     */
    private $renameString = null;

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
     * If set to true then the file will be renamed to hopefully
     * force a succesful upload.
     */
    public function setForceUploadRename($boolean)
    {
        if ($boolean === true) {
            $this->forceRenameSuccess = true;
            return;
        }
        $this->forceRenameSuccess = false;
    }

    /**
     * Set the rename method to either RENAME_PREPEND_RANDOM or RENAME_PREPEND_TIMESTAMP
     */
    public function setRenameMethod($method = self::RENAME_PREPEND_RANDOM)
    {
        if ($method == self::RENAME_PREPEND_RANDOM) {
            $this->renameType = self::RENAME_PREPEND_RANDOM;
            $this->renameString = rand(1,9) . rand(1,9) . rand(1,9) . rand(1,9) . rand(1,9) . "-";
        } else if ($method == self::RENAME_PREPEND_TIMESTAMP) {
            $this->renameType = self::RENAME_PREPEND_TIMESTAMP;
            $this->renameString = time() . "-";
        }
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
            //trigger_error("targetLocationFolder not set");
            return false;
        }

        if (null === $this->formFieldName) {
            //trigger_error("formFieldName not set");
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
                    	    //trigger_error("The uploaded file exceeds the upload_max_filesize directive in php.ini.");
                    	    $this->error = "Uploaded file exceeds the maximum size.";
                    	    break;
                    	case(2):
                    	    //trigger_error("The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.");
                    	    $this->error = "Uploaded file exceeds the maximum size.";
                    	    break;
                    	case(3):
                    	    //trigger_error("The uploaded file was only partially uploaded.");
                    	    $this->error = "An error occured while uploading the file.";
                    	    break;
                    	case(4):
                    	    //trigger_error("No file was uploaded.");
                    	    $this->error = "Please select a valid file to upload";
                    	    break;
                    	case(6):
                    	    //trigger_error("Missing a temporary folder. Introduced in PHP 4.3.10 and PHP 5.0.3.");
                    	    $this->error = "An error occured while uploading the file.";
                    	    break;
                    	case(7):
                    	    //trigger_error("Failed to write file to disk. Introduced in PHP 5.1.0.");
                    	    $this->error = "An error occured while uploading the file.";
                    	    break;
                    	case(8):
                    	    //trigger_error("A PHP extension stopped the file upload. PHP does not provide a way to ascertain which extension caused the file upload to stop; examining the list of loaded extensions with phpinfo() may help. Introduced in PHP 5.2.0.");
                    	    $this->error = "An error occured while uploading the file.";
                    	    break;
                    	case(9):
                    	    //trigger_error("File size was empty");
                    	    $this->error = "The uploaded file appears to be empty";
                    	    break;
                    	default:
                    	    //trigger_error("File upload failed with error code of " . $file['error']);
                    	    $this->error = "An error occured while uploading the file.";
                    	    break;
                    }

                	return false;
                } else {
                    $fileName = $file['name'];
                    if ($this->renameType != null && $this->renameString != null) {
                        $fileName = $this->renameString . $fileName;
                    }

                    $targetPath = $this->targetLocationFolder . DIRECTORY_SEPARATOR . $fileName;
                    if (file_exists($targetPath)) {
                        if ($this->forceRenameSuccess) {
                            $fileName = $this->_getForcedIgnoreExisting('-' . $fileName);
                            $targetPath = $this->targetLocationFolder . DIRECTORY_SEPARATOR . $fileName;
                        } else {
                        	//trigger_error("File already exists at target location");
                        	$this->error = "This file already exists";
                        	return false;
                        }
                    }

                    if (file_exists($targetPath)) {
                        //trigger_error("File already exists at target location");
                        $this->error = "This file already exists";
                        return false;
                    } else {
                        if (@move_uploaded_file($file["tmp_name"],$targetPath)) {
                            if (file_exists($targetPath)) {
                                $this->finalLocation = $targetPath;
                                $this->finalName = $fileName;
                                $this->success = true;
								$this->uploadedName = $file['name'];
	                    		$this->uploadedTempName = $file['tmp_name'];
	                    		$this->uploadedType = $file['type'];
	                    		$this->uploadedSize = $file['size'];
                            } else {
                                //trigger_error("File upload failed as file already exists.");
                                $this->error = "An unknown error occured while uploading the file.";
                            }
                        } else {
                            //trigger_error("Unable to move file to target location.");
                            $this->error = "An unknown error occured while uploading the file.";
                        }
                    }
                }
            } else {
                //trigger_error("Target form field name not found.");
                $this->error = "Please choose a valid file to upload";
            }
        } else {
            //trigger_error("No file was selected or files array was empty.");
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
