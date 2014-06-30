<?php
/**
 * Pizza E96
 *
 * @author: Paul Melekhov
 */

namespace App;

class Router
{
    /**
     * Маршруты из app/configs/routes.php
     *
     * @var array
     */
    private $_routes;

    /**
     * @param array $routes
     */
    public function __construct(array $routes)
    {
        $this->_routes = $routes;
    }

    /**
     * @param $requestPath
     * @return array|bool
     */
    public function route($requestPath)
    {
        foreach ($this->_routes as $route => $info) {
            if ($info["pattern"] && preg_match($info["pattern"], $requestPath, $matches)) {
                $needles = array();
                foreach (array_keys($matches) as $matchIndex) {
                    $needles[$matchIndex] = "#{$matchIndex}#";
                }

                $params = array();
                foreach ($info["params"] as $key => $value) {
                    $params[$key] = str_replace($needles, $matches, $info["params"][$key]);
                }

                return array(
                    "controller" => $info["params"]["controller"],
                    "action" => $info["params"]["action"],
                    "class" => "\\App\\Controllers\\" . $info["params"]["controller"] . "Controller",
                    "method" => $info["params"]["action"] . "Action",
                    "params" => $params,
                    "route" => $route,
                );
            }
        }
        return false;
    }

    /**
     * @param $route
     * @param array $params
     * @return mixed
     * @throws Exception
     */
    public function assemble($route, array $params = array())
    {
        if (! isset($this->_routes[$route])) {
            throw new \App\Exception("Failed to assemble URL: no such route '{$route}'");
        }
        $needles = array();
        $replacements = array();
        foreach ($params as $name => $value) {
            $needles[] = "#{$name}#";
            $replacements[] = $value;
        }
        return str_replace($needles, $replacements, $this->_routes[$route]["reverse"]);
    }
}
