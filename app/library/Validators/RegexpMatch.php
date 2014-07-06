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

    /**
     * @var string Шаблон регулярного выражения
     */
    private $_pattern;

    /**
     * @param string $pattern      Шаблон регулярного выражения
     */
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

    public function getPattern()
    {
        return $this->_pattern;
    }
}
