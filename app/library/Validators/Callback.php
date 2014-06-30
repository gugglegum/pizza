<?php
/**
 * Pizza E96
 *
 * @author: Paul Melekhov
 */

namespace App\Validators;

class Callback extends AbstractValidator
{
    const CALLBACK_RETURN_ERROR = "callbackReturnError";

    private $_callback;

    public function __construct($callback)
    {
        $this->_callback = $callback;
    }

    public function isValid($value)
    {
        if (! call_user_func($this->_callback, $value)) {
            $this->_lastErrorCode = self::CALLBACK_RETURN_ERROR;
            return false;
        }
        return true;
    }

}
