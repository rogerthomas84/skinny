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
namespace Skinny;

use Skinny\Validate\AbstractValidator;
use Skinny\Validate\NotEmpty;

/**
 * Form
 *
 * This class provides a simple way of performing form validation at
 * controller level
 *
 * @package Skinny
 * @author  Roger Thomas <roger.thomas@rogerethomas.com>
 */
class Form
{
    /**
     * @var array
     */
    protected $fields = [];

    /**
     * @var array
     */
    protected $validators = [];

    /**
     * @var array
     */
    protected $required = [];

    /**
     * @var array
     */
    protected $errorMessages = [];

    /**
     * @var array
     */
    protected $logicalNames = [];

    /**
     * @var array
     */
    protected $userProvidedData = [];

    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Add an element to the stack
     *
     * @param string $name
     * @param boolean $required
     * @param array $validators
     * @param string $logicalName
     */
    public function addElement($name, $required = false, $validators = [], $logicalName = null)
    {
        if ($required == true) {
            $hasNotEmpty = false;
            foreach ($validators as $validator) {
                if ($validator instanceof NotEmpty) {
                    $hasNotEmpty = true;
                }
            }
            if (!$hasNotEmpty) {
                $validators[] = new NotEmpty();
            }
        }
        $this->fields[] = $name;
        $this->validators[$name] = $validators;
        $this->required[$name] = $required;
        $this->logicalNames[$name] = $logicalName;
    }

    /**
     * Check whether the form is valid. You can use this with PHP by
     * passing in $_POST, or with Zend Framework $request->getPost()
     *
     * @param array $postParams
     * @return boolean
     */
    public function isValid($postParams): bool
    {
        if (!is_array($postParams)) {
            $this->errorMessages = array('Form was invalid.');
            return false;
        }

        $valid = true;
        foreach ($this->fields as $field) {
            /* @var $validator AbstractValidator */
            foreach ($this->validators[$field] as $validator) {
                if (!array_key_exists($field, $postParams)) {
                    $this->addError($field, '%s must be provided.');
                    $valid = false;
                    continue;
                }
                $this->userProvidedData[$field] = $postParams[$field];

                $validator->setData($postParams);

                if (!$validator->isValid($postParams[$field])) {
                    $this->addError($field, $validator->errorMessage);
                    $valid = false;
                }
            }
        }
        return $valid;
    }

    /**
     * Get the posted data for the form. Either all of the data (by passing null) or a specific field.
     *
     * @param string|null $fieldName
     * @return array|string
     */
    public function getProvidedData(?string $fieldName = null)
    {
        if (null !== $fieldName) {
            if (array_key_exists($fieldName, $this->userProvidedData)) {
                return $this->userProvidedData[$fieldName];
            }
            return null;
        }
        return $this->userProvidedData;
    }

    /**
     * Add a message to a given field.
     * @param string $fieldName
     * @param string $message
     */
    protected function addError(string $fieldName, string $message)
    {
        if ($this->logicalNames[$fieldName] == null) {
            return;
        }
        if (!array_key_exists($fieldName, $this->errorMessages)) {
            $this->errorMessages[$fieldName] = [];
        }
        $this->errorMessages[$fieldName][] = sprintf(
            $message,
            $this->logicalNames[$fieldName]
        );
    }

    /**
     * Get an array of error messages back.
     * @return array
     */
    public function getErrors()
    {
        return $this->errorMessages;
    }
}
