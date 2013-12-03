<?php
/**
 * Skinny - a straight forward no-nonsense PHP library
 *
 * @author      Roger Thomas <roger.thomas@rogerethomas.com>
 * @copyright   2013 Roger Thomas
 * @link        http://www.rogerethomas.com
 * @license     http://www.rogerethomas.com/license
 * @version     2.0.1
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
    protected $fields = array();

    /**
     * @var array
     */
    protected $validators = array();

    /**
     * @var array
     */
    protected $required = array();

    /**
     * @var array
     */
    protected $errorMessages = array();

    /**
     * @var array
     */
    protected $logicalNames = array();

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
    public function addElement($name, $required = false, $validators = array(), $logicalName = null)
    {
        if ($required == true) {
            $hasNotEmpty = false;
            foreach ($validators as $validator) {
                if ($validator instanceof \Skinny\Validate\NotEmpty) {
                    $hasNotEmpty = true;
                }
            }
            if (!$hasNotEmpty) {
                $validators[] = new \Skinny\Validate\NotEmpty();
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
    public function isValid($postParams)
    {
        if (!is_array($postParams)) {
            $this->errorMessages = array('Form was invalid.');
            return false;
        }

        $valid = true;
        foreach ($this->fields as $field) {
            /* @var $validator \Skinny\Validate\AbstractValidator */
            foreach ($this->validators[$field] as $validator) {
                if (!array_key_exists($field, $postParams)) {
                    $this->addError($field, '%s must be provided.');
                    $valid = false;
                    continue;
                }

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
     * Add a message to a given field.
     * @param string $fieldName
     * @param string $message
     */
    protected function addError($fieldName, $message)
    {
        if ($this->logicalNames[$fieldName] == null) {
            return;
        }
        if (!array_key_exists($fieldName, $this->errorMessages)) {
            $this->errorMessages[$fieldName] = array();
        }
        $this->errorMessages[$fieldName][] = sprintf(
            $message,
            $this->logicalNames[$fieldName]
        );
        return;
    }

    /**
     * Get an array of error messagas back.
     * @return array
     */
    public function getErrors()
    {
        return $this->errorMessages;
    }
}
