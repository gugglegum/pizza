<?php
/**
 * Pizza E96
 *
 * @author: Paul Melekhov
 */

namespace App\Http;

/**
 * Объект, представляющий URL для HTTP(S)
 *
 */
class Url
{
    private $_scheme;

    private $_host;

    private $_port;

    /**
     * Путь
     *
     * @var string
     */
    private $_path;

    /**
     * Набор GET-параметров в виде строки "var1=value1&var2=value2" (без "?" в начале)
     *
     * @var string
     */
    private $_query;

    /**
     * Ссылка на место на странице
     *
     * @var string
     */
    private $_anchor;

    /**
     * Возвращает экземпляр Url заполненный значениями из переданного URL
     *
     * @static
     * @param $urlString
     * @internal param $url
     * @return Url
     */
    public static function createFrom($urlString)
	{
		$url = new self();
		$url->setFrom($urlString);
		return $url;
	}

    /**
	 * Очищает URL
	 *
	 * @return Url
	 */
	public function clear()
	{
		$this->setScheme(null)
			->setHost(null)
			->setPort(null)
			->setPath(null)
			->setQuery(null)
			->setAnchor(null);
		return $this;
	}

	/**
	 * Устанавливает все части URL на основе заданного в виде строки
	 *
	 * @param $urlString
	 * @return Url
	 * @throws Exception
	 */
	public function setFrom($urlString)
	{
		$this->clear();
		$parts = parse_url($urlString);
		if (!empty($parts["scheme"]) && !empty($parts["host"]) && empty($parts["port"])) {
			if (strcasecmp($parts["scheme"], "http") == 0) {
				$parts["port"] = 80;
			} elseif (strcasecmp($parts["scheme"], "https") == 0) {
				$parts["port"] = 443;
			}
		}
		foreach ($parts as $name => $value) {
			switch ($name) {
				case "scheme" :
					$this->setScheme($value);
					break;
				case "host" :
					$this->setHost($value);
					break;
				case "port" :
					$this->setPort($value);
					break;
				case "user" :
				case "pass" :
					throw new Exception("URL parts 'user' and 'pass' does not supported");
					break;
				case "path" :
					$this->setPath($value);
					break;
				case "query" :
					$this->setQuery($value);
					break;
				case "fragment" :
					$this->setAnchor($value);
					break;
				default :
					throw new Exception("Unknown URL part: '{$name}'");
			}
		}
		return $this;
	}

    /**
     * Возвращает схему
     *
     * @throws Exception
     * @return string
     */
    public function getScheme()
    {
        if (! $this->hasScheme()) {
            throw new Exception("URL scheme has not been set");
        }
        return $this->_scheme;
    }

    public function hasScheme()
    {
        return $this->_scheme != "";
    }

    /**
     * Устанавливает схему
     *
     * @param $scheme
     * @return Url
     */
    public function setScheme($scheme)
    {
        $this->_scheme = $scheme;
        return $this;
    }

    /**
     * Возвращает хост
     *
     * @throws Exception
     * @return string
     */
    public function getHost()
    {
        if (! $this->hasHost()) {
            throw new Exception("URL host has not been set");
        }
        return $this->_host;
    }

    public function hasHost()
    {
        return $this->_host != "";
    }

    /**
     * Устанавливает хост
     *
     * @param $host
     * @return Url
     */
    public function setHost($host)
    {
        $this->_host = $host;
        return $this;
    }

    /**
     * Возвращает порт
     *
     * @throws Exception
     * @return string
     */
    public function getPort()
    {
        if (! $this->hasPort()) {
            throw new Exception("URL port has not been set");
        }
        return $this->_port;
    }

    public function hasPort()
    {
        return $this->_port != "";
    }

    /**
     * Устанавливает порт
     *
     * @param $port
     * @return Url
     */
    public function setPort($port)
    {
        $this->_port = $port;
        return $this;
    }

    /**
     * Возвращает путь URL
     *
     * @throws Exception
     * @return string
     */
    public function getPath()
    {
        if (! $this->hasPath()) {
            throw new Exception("URL path has not been set");
        }
        return $this->_path;
    }

    public function hasPath()
    {
        return $this->_path != "";
    }

    /**
     * Устанавливает путь URL
     * @param $path
     * @return Url
     */
    public function setPath($path)
    {
        $this->_path = $path;
        return $this;
    }

    /**
     * Возвращает Query (то, что в URL после "?")
     *
     * @return string
     */
    public function getQuery()
    {
        return $this->_query;
    }

    /**
     * Устанавливает Query (то, что в URL после "?")
     *
     * Если $query == "", то "?" добавляется в конце URL,
     * если $query === null, то нет.
     *
     * @param string|null $query
	 * @return \App\Http\Url
	 */
    public function setQuery($query)
    {
        $this->_query = $query;
		return $this;
    }

    /**
     * Возвращает Anchor (то, что в URL после "#")
     *
     * @return string
     */
    public function getAnchorString()
    {
        return $this->_anchor;
    }

    /**
     * Устанавливает Anchor (то, что в URL после "#")
     * Если $anchor == "", то "#" добавляется в конце URL,
     * если $anchor === null, то нет.
     *
     * @param $anchor
	 * @return Url
	 */
    public function setAnchor($anchor)
    {
        $this->_anchor = $anchor;
		return $this;
	}

    /**
     * Возвращает Query в виде ассоциативного массива
     *
     * @return array
     */
    public function getQueryAsArray()
    {
        parse_str($this->_query, $assoc);
        return $assoc;
    }

    /**
     * Устанавливает Query из ассоциативного массива
     *
     * @param array $assoc
     * @param bool $nullIfEmpty
     * @return Url
     */
    public function setQueryFromArray(array $assoc, $nullIfEmpty = true)
    {
        if ($nullIfEmpty && empty($assoc)) {
            $this->_query = null;
        } else {
            $this->_query = http_build_query($assoc, "", "&");
        }
        return $this;
    }

    public function setQueryParams(array $newParams)
    {
        $params = $this->getQueryAsArray();
        foreach ($newParams as $name => $value) {
            $params[$name] = $value;
        }
        $this->setQueryFromArray($params, true);
        return $this;
    }

    public function removeQueryParams(array $paramNames)
    {
        $params = $this->getQueryAsArray();
        foreach ($paramNames as $paramName) {
            unset($params[$paramName]);
        }
        $this->setQueryFromArray($params, true);
        return $this;
    }

    /**
     * Возвращает URL сервера, на который пришел запрос
     *
     * @return string
     */
    public function getServerUrl()
    {
        $url = $this->getScheme() . "://" . $this->getHost();
        $port = $this->getPort();
        if ($port !== null) {
            if (($this->getScheme() == "http" && $port != 80) ||
                ($this->getScheme() == "https" && $port != 443)) {
                $url .= ":{$port}";
            }
        }
        return $url;
    }

    /**
     * Возвращает относительный URL
     *
     * @return string
     */
    public function getRelativeUrl()
    {
        return $this->getPath() .
            ($this->_query !== null ? "?" : "") . $this->_query .
            ($this->_anchor !== null ? "#" : "") . $this->_anchor;
    }

    /**
     * Возвращает абсолютный URL запроса, включающий в себя схему, хост и порт
     *
     * @return string
     */
    public function getAbsoluteUrl()
    {
        return $this->getServerUrl() . $this->getRelativeUrl();
    }
}
