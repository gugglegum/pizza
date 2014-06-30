<?php
/**
 * Pizza E96
 *
 * @author: Paul Melekhov
 */

namespace App\Http;

/**
 * Класс исключений HTTP
 */
class Exception extends \App\Exception
{
    /**
     * @var integer
     */
    protected $_httpStatusCode;

    /**
     * @var string
     */
    protected $_httpStatusMessage;

    public function getHttpStatusCode()
    {
        if (! $this->_httpStatusCode) {
            throw new \Exception("The property " . __CLASS__ . "::_httpStatusCode has not been set");
        }
        return $this->_httpStatusCode;
    }

    public function getHttpStatusMessage()
    {
        if (! $this->_httpStatusMessage) {
            throw new \Exception("The property " . __CLASS__ . "::_httpStatusMessage has not been set");
        }
        return $this->_httpStatusMessage;
    }
}
