<?php
/**
 * Pizza E96
 *
 * @author: Paul Melekhov
 */

namespace App\Validators;

class RegexpMatch extends AbstractValidator
{
    const REGEXP_DOES_NOT_MATCH = "regexpDoesNotMatch";

    private $_pattern;

    public function __construct($pattern)
    {
        $this->_pattern = $pattern;
    }

    public function isValid($value)
    {
        if (! preg_match($this->_pattern, $value)) {
            $this->_lastErrorCode = self::REGEXP_DOES_NOT_MATCH;
            return false;
        }
        return true;
    }

}
