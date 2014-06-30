<?php
/**
 * Pizza E96
 *
 * @author: Paul Melekhov
 */

namespace App\Http;

class Response
{
    /**
     * Код статуса HTTP
     *
     * @var int
     */
    private $_statusCode = 200;

    /**
     * Сообщение статуса HTTP
     *
     * @var string
     */
    private $_statusMessage = "OK";

    /**
     * HTTP response headers
     *
     * @var array
     */
    private $_headers = array();

    /**
     * HTTP response body
     *
     * @var string
     */
    private $_body = "";

    /**
     * Возвращает код статуса HTTP
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->_statusCode;
    }

    /**
     * Возвращает сообщение статуса HTTP
     *
     * @return string
     */
    public function getStatusMessage()
    {
        return $this->_statusMessage;
    }

    /**
     * Устанавливает код и сообщение статуса HTTP (Например, 200 и "OK")
     *
     * @param int $statusCode
     * @param string $statusMessage
     * @return Response
     */
    public function setStatus($statusCode, $statusMessage)
    {
        $this->_statusCode = $statusCode;
        $this->_statusMessage = $statusMessage;
        return $this;
    }

    /**
     * Возвращает ранее установленный заголовок. Если их несколько, то по умолчанию
     * первый, для остальных нужно использовать второй аргумент
     *
     * @param string $name
     * @param int $index
     * @return null
     */
    public function getHeader($name, $index = 0)
    {
        return isset($this->_headers[$name][$index]) ? $this->_headers[$name][$index] : null;
    }

    public function getHeadersPlain()
    {
        $plainHeaders = array();
        foreach ($this->_headers as $name => $values) {
            foreach ($values as $value) {
                $plainHeaders[] = "{$name}: {$value}";
            }
        }
        return $plainHeaders;
    }

    /**
     * Возвращает все значения заданного заголовка (некоторые заголовки могут
     * содержаться в ответе несколько раз)
     *
     * @param string $name
     * @return array
     */
    public function getHeaderValues($name)
    {
        return isset($this->_headers[$name]) ? $this->_headers[$name] : array();
    }

    /**
     * Проверяет был ли ранее установлен заголовок
     *
     * @param string $name
     * @return bool
     */
    public function hasHeader($name)
    {
        return isset($this->_headers[$name]);
    }

    /**
     * Позволяет узнать сколько раз был установлен заголовок с заданным именем
     *
     * @param string $name
     * @return int
     */
    public function getHeaderCount($name)
    {
        if ($this->hasHeader($name)) {
            return count($this->_headers[$name]);
        } else {
            return 0;
        }
    }

    /**
     * Добавляет заголовок. Если ранее уже был добавлен заголовок с
     * таким именем, старый не будет перезаписан.
     *
     * @param string $name
     * @param string $value
     * @return Response
     */
    public function addHeader($name, $value)
    {
        return $this->setHeader($name, $value, true);
    }

    /**
     * Устанавливает (добавляет) заголовок
     *
     * @param string $name
     * @param string $value
     * @param bool $append      Если TRUE, то ранее установленный заголовок не будет перезаписан
     * @return Response
     */
    public function setHeader($name, $value, $append = false)
    {
        if ($value !== null) {
            if (! $append) {
                $this->_headers[$name] = array($value);
            } else {
                $this->_headers[$name][] = $value;
            }
        } else {
            $this->unsetHeader($name);
        }
        return $this;
    }

    /**
     * Удаляет ранее установленный заголовок
     *
     * @param string $name
     * @return Response
     */
    public function unsetHeader($name)
    {
        unset($this->_headers[$name]);
        return $this;
    }

    /**
     * Возвращает ранее установленное тело ответа
     *
     * @return string
     */
    public function getBody()
    {
        return $this->_body;
    }

    /**
     * Устанавливает тело ответа (заменяет установленное ранее)
     *
     * @param string $body
     * @return Response
     */
    public function setBody($body)
    {
        $this->_body = $body;
        return $this;
    }

    /**
     * Устанавливает редирект
     *
     * @param string $url
     * @param int $statusCode
     * @param string $statusMessage
     * @return Response
     */
    public function setRedirect($url, $statusCode = 303, $statusMessage = "See Other")
    {
        $this->addHeader("Location", $url)
             ->setStatus($statusCode, $statusMessage);
        return $this;
    }

    /**
     * Отправляет HTTP-ответ сервера
     *
     * @return void
     */
    public function send()
    {
        // Отправка статуса

        // Для корректной отправки статуса проверяем интерфейс, по которому работает PHP
        if (substr(PHP_SAPI, 0, 3) == 'cgi') {
            header("Status: {$this->_statusCode} {$this->_statusMessage}");
        } else {
            header("HTTP/1.1 {$this->_statusCode} {$this->_statusMessage}");
        }

        // Отправка заголовоков

        foreach ($this->_headers as $name => $values) {
            foreach ($values as $value) {
                header("{$name}: {$value}", false);
            }
        }

        // Отправка тела ответа

        echo $this->getBody();
    }

}
