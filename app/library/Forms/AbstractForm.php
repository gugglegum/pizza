<?php
/**
 * Pizza E96
 *
 * @author: Paul Melekhov
 */

namespace App\Forms;

abstract class AbstractForm
{
    private $_elements = array();
    private $_formValidateErrors = array();

    public function addElement(\App\Forms\Element $element)
    {
        $this->_elements[$element->getName()] = $element;
    }

    /**
     * @param $name
     * @return Element
     * @throws Exception
     */
    public function getElement($name)
    {
        if (! isset($this->_elements[$name])) {
            throw new Exception("Form element with name {$name} not found");
        }
        return $this->_elements[$name];
    }

    public function getElements()
    {
        return $this->_elements;
    }

    /**
     * @param string $name
     * @return string
     */
    public function getValue($name)
    {
        return $this->getElement($name)->getValue();
    }

    /**
     * @param array $values
     */
    public function setFormValues($values)
    {
        foreach ($values as $name => $value) {
            $this->getElement($name)->setValue($value);
        }
    }

    /**
     * @return array
     */
    public function getFormValues()
    {
        $values = array();
        foreach ($this->_elements as $element) {
            /** @var $element \App\Forms\Element */
            $values[$element->getName()] = $element->getValue();
        }
        return $values;
    }

    /**
     * @return array
     */
    public function getFormElementNames()
    {
        $names = array();
        foreach ($this->_elements as $element) {
            /** @var $element \App\Forms\Element */
            $names[] = $element->getName();
        }
        return $names;
    }

    public function isValid()
    {
        $valid = true;
        /** @var $element \App\Forms\Element */
        foreach ($this->_elements as $element) {
            /** @var $element \App\Forms\Element */
            if (! $element->isValid()) {
                $valid = false;
            }
        }
        return $valid;
    }

    public function getElementsValidateErrors()
    {
        $elementsValidateErrors = array();
        /** @var $element \App\Forms\Element */
        foreach ($this->_elements as $element) {
            /** @var $element \App\Forms\Element */
            $errors = $element->getValidateErrors();
            if (count($errors) > 0) {
                $elementsValidateErrors[$element->getName()] = $errors;
            }
        }
        return $elementsValidateErrors;
    }

    public function getFormValidateErrors()
    {
        return $this->_formValidateErrors;
    }

    public function addFormValidationError($errorCode)
    {
        $this->_formValidateErrors[] = $errorCode;
    }
}
