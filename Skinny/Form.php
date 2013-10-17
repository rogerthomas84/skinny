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
class Form {

    /**
     * @var array()
     */
    protected $fields = array();

    /**
     * @var array()
     */
    protected $validators = array();

    /**
     * @var array()
     */
    protected $required = array();

    /**
     * Constructor
     */
    public function __construct() {

    }

    /**
     * Add an element to the stack
     *
     * @param string $name
     * @param boolean $required
     * @param array $validators
     */
    public function addElement($name, $required = false, $validators = array()) {
        if ($required == true) {
            $validators[] = new \Skinny\Validate\NotEmpty();
        }
        $this->fields[] = $name;
        $this->validators[$name] = $validators;
        $this->required[$name] = $required;
    }

    /**
     * Check whether the form is valid. You can use this with PHP by
     * passing in $_POST, or with Zend Framework $request->getPost()
     *
     * @param array $postParams
     * @return boolean
     */
    public function isValid($postParams) {
        if (!is_array($postParams)) {
            return false;
        }

        foreach ($this->fields as $field) {
            /* @var $validator \Skinny\Validate\AbstractValidator */
            foreach ($this->validators[$field] as $validator) {
                if (!array_key_exists($field, $postParams)) {
                    return false;
                }
                if (!$validator->isValid($postParams[$field])) {
                    return false;
                }
            }
        }
        return true;
    }
}
