<?php
/**
 * Pizza E96
 *
 * @author: Paul Melekhov
 */

namespace App;

class Autoloader
{
    /**
     * @var array
     */
    private $_namespaces = array();

    public function autoload($class)
    {
        foreach ($this->_namespaces as $namespace => $path) {
			$classFile = null;
			if ($namespace != "") {
				if (preg_match("/^" . preg_quote($namespace, "/") . "(?:\\\\|_)(.+)/", $class, $matches)) {
					$classFile = $path . "/" . str_replace(array("\\", "_"), array("/", "/"), $matches[1]) . ".php";
				}
			} else {
				$classFile = $path . "/" . str_replace(array("\\", "_"), array("/", "/"), $class) . ".php";
			}

			if ($classFile !== null) {
				if (file_exists($classFile)) {
					require_once($classFile);
					break;
				}
			}
        }
    }

    public function registerNamespace($namespace, $path)
    {
        $this->_namespaces[$namespace] = $path;
    }

    public function registerNamespaces(array $namespaces)
    {
        foreach ($namespaces as $namespace => $path) {
            $this->registerNamespace($namespace, $path);
        }
    }

}
