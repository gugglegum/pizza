<?php
/**
 * Pizza E96
 *
 * @author: Paul Melekhov
 */

namespace App\Helpers;

class Url extends AbstractHelper
{
    public function execute($route, array $params = array())
    {
        /** @var $router \App\Router */
        $router = $this->getResource("Router");
        $config = $this->getResource("Config");
        $url = $config["sitePrefixPath"] . $router->assemble($route, $params);
        return $url;
    }
}
