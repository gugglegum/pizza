<?php
/**
 * Pizza E96
 *
 * @author: Paul Melekhov
 */

namespace App\Validators;

class Email extends AbstractValidator
{
    const INVALID_EMAIL = "invalidEmail";

    public function isValid($value)
    {
        if (! preg_match('/^[a-z0-9\.\-\_]+@([?:a-z0-9\-]+\.)+[a-z]+$/i', $value)) {
            $this->_lastErrorCode = self::INVALID_EMAIL;
            return false;
        }
        return true;
    }

}
