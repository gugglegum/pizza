<?php
/**
 * Pizza E96
 *
 * @author: Paul Melekhov
 */

namespace App\Validators;

abstract class AbstractValidator
{
    protected $_lastErrorCode;

    abstract public function isValid($value);

    public function getLastErrorCode()
    {
        return $this->_lastErrorCode;
    }

}
