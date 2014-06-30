<?php
/**
 * Pizza E96
 *
 * @author: Paul Melekhov
 */

namespace App\Http;

/**
 * Поднятие этого исключения служит сигналом для FrontController о том,
 * что нужно отобразить страницу с ошибкой HTTP/1.1 405 Method Not Allowed
 */
class MethodNotAllowedException extends Exception
{
    public function __construct($message = "", $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
        $this->_httpStatusCode = 405;
        $this->_httpStatusMessage = "Method Not Allowed";
    }
}
