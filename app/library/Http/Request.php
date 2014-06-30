<?php
/**
 * Pizza E96
 *
 * @author: Paul Melekhov
 */

namespace App\Http;

/**
 * Объект, представляющий HTTP-запрос
 *
 * Объект соответствующий текущему запросу можно получить через статический
 * метод createFromGlobals(). Произвольный объект (например, при Unit-тестировании)
 * можно получить через вызовы setter'ов.
 */
class Request
{
    /**
     * HTTP-метод запроса
     *
     * @var string
     */
    private $_method;

    /**
     * @var Url
     */
    private $_url;

    /**
     * GET-параметры запроса, переданные через "?var1=value1&var2=value2"
     *
     * @var array
     */
    private $_getParams = array();

    /**
     * POST-параметры запроса
     *
     * @var array
     */
    private $_postParams = array();

    /**
     * Куки запроса
     *
     * @var array
     */
    private $_cookies = array();

    /**
     * Загруженные файлы
     *
     * @var array
     */
    private $_files = array();

    public function __construct()
    {
        $this->_url = new Url();
    }

    /**
     * Создает экземпляр объекта запроса, соответствующий текущему запросу
     * на веб-сервере.
     *
     * @static
     * @return Request
     */
    public static function createFromGlobals()
    {
        $request = new self();
        $request->setMethod($_SERVER["REQUEST_METHOD"])
            ->setRequestUri($_SERVER['REQUEST_URI'])
            ->setGetParams($_GET)
            ->setPostParams($_POST)
            ->setCookies($_COOKIE)
            ->setFiles($_FILES);
        $request->_url
            ->setScheme(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? "https" : "http")
            ->setHost($_SERVER["HTTP_HOST"])
            ->setPort($_SERVER["SERVER_PORT"]);
        return $request;
    }

    /**
     * Устанавливает HTTP-метод запроса
     *
     * @param $method
     * @return Request
     */
    public function setMethod($method)
    {
        $this->_method = $method;
        return $this;
    }

    /**
     * Возвращает HTTP-метод запроса
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->_method;
    }

    public function isPost()
    {
        return $this->getMethod() === "POST";
    }

    public function isGetOrHead()
    {
        return $this->getMethod() === "GET" || $this->getMethod() === "HEAD";
    }

    /**
     * Устанавливает Request URI
     *
     * @param $requestUri
     * @return Request
     */
    public function setRequestUri($requestUri)
    {
        $parts = parse_url($requestUri);
        $this->_url->setPath($parts["path"]);
        if (isset($parts["query"])) {
            $this->_url->setQuery($parts["query"]);
        } else {
            // Такая сложная обработка нужна из-за того, что parse_url игнорирует
            // знак вопроса в конце пути, если за ним нет query, а мы хотим
            // различать URL "/path?" и "/path", т.к. они действительно разные.
            $this->_url->setQuery(substr($requestUri, -1) == "?" ? "" : null);
        }
        return $this;
    }

    /**
     * Возвращает Request URI
     *
     * @return string
     */
    public function getRequestUri()
    {
        return $this->_url->getRelativeUrl();
    }

    /**
     * Возвращает путь из Request URI
     *
     * @return string
     */
    public function getRequestPath()
    {
        return $this->_url->getPath();
    }

    /**
     * Устанавливает GET-параметры
     *
     * @param $getParams
     * @return Request
     */
    public function setGetParams($getParams)
    {
        $this->_getParams = $getParams;
        return $this;
    }

    /**
     * Возвращает GET-параметры
     *
     * @return array
     */
    public function getGetParams()
    {
        return $this->_getParams;
    }

    /**
     * Возвращает GET-параметр по имени (NULL если не найден)
     *
     * @param string $name
     * @return string|null
     */
    public function getGetParam($name)
    {
        return $this->hasGetParam($name) ? $this->_getParams[$name] : null;
    }

    /**
     * Возвращает TRUE, если GET-параметр $name есть
     *
     * @param $name
     * @return bool
     */
    public function hasGetParam($name)
    {
        return array_key_exists($name, $this->_getParams);
    }

    /**
     * Устанавливает POST-параметры
     *
     * @param $postParams
     * @return Request
     */
    public function setPostParams($postParams)
    {
        $this->_postParams = $postParams;
        return $this;
    }

    /**
     * Возвращает POST-параметры
     *
     * @return array
     */
    public function getPostParams()
    {
        return $this->_postParams;
    }

    /**
     * Возвращает POST-параметр по имени (NULL если не найден)
     *
     * @param string $name
     * @return string|null
     */
    public function getPostParam($name)
    {
        return $this->hasPostParam($name) ? $this->_postParams[$name] : null;
    }

    /**
     * Возвращает TRUE, если POST-параметр $name есть
     *
     * @param $name
     * @return bool
     */
    public function hasPostParam($name)
    {
        return array_key_exists($name, $this->_postParams);
    }

    /**
     * Устанавливает куки
     *
     * @param $cookies
     * @return Request
     */
    public function setCookies($cookies)
    {
        $this->_cookies = $cookies;
        return $this;
    }

    /**
     * Возвращает куки
     *
     * @return array
     */
    public function getCookies()
    {
        return $this->_cookies;
    }

    /**
     * Возвращает cookie по имени (NULL если не найдена)
     *
     * @param string $name
     * @return string|null
     */
    public function getCookie($name)
    {
        return $this->hasCookie($name) ? $this->_cookies[$name] : null;
    }

    /**
     * Возвращает TRUE, если cookie $name есть
     *
     * @param $name
     * @return bool
     */
    public function hasCookie($name)
    {
        return array_key_exists($name, $this->_cookies);
    }

    /**
     * Устанавливает файлы
     *
     * @param $files
     * @return Request
     */
    public function setFiles($files)
    {
        $this->_files = $files;
        return $this;
    }

    /**
     * Возвращает файлы
     *
     * @return array
     */
    public function getFiles()
    {
        return $this->_files;
    }

    /**
     * Возвращает объект URL, соответствующий данному запросу
     *
     * @return Url
     */
    public function getUrl()
    {
        return clone $this->_url;
    }
}
