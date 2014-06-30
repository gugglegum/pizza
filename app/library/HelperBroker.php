<?php
/**
 * Pizza E96
 *
 * @author: Paul Melekhov
 */

namespace App;

/**
 * Helper Broker
 *
 * @method \App\Helpers\HeadBlock headBlock() headBlock()    Возвращает экземпляр хэлпера HeadBlock
 * @method string sitePrefixPath() sitePrefixPath()
 * @method string|null headTitle() headTitle()
 * @method string escape() escape()
 * @method string url() url(\string $route, array $params = array())  Строит и возвращает URL по имени маршрута и набору параметров
 * @method mixed configVar() configVar(\string $varName)     Возвращает значение заданной переменной конфига
 */
class HelperBroker
{
    /**
     * @var Bootstrap
     */
    private $_bootstrap;

    private $_helpers = array();

    /**
     * @param Bootstrap $bootstrap
     */
    public function __construct(\App\Bootstrap $bootstrap)
    {
        $this->_bootstrap = $bootstrap;
    }

    /**
     * @param $name
     * @return \App\Helpers\AbstractHelper
     */
    public function getHelper($name)
    {
        $name = ucfirst($name);
        if (!isset($this->_helpers[$name])) {
            $class = "\\App\\Helpers\\{$name}";
            $this->_helpers[$name] = new $class($this);
        }
        return $this->_helpers[$name];
    }

    /**
     * @return Bootstrap
     */
    public function getBootstrap()
    {
        return $this->_bootstrap;
    }

    /**
     * @param $helperName
     * @param array $arguments
     * @return mixed
     */
    public function __call($helperName, array $arguments)
    {
        $helper = $this->getHelper($helperName);
        $result = call_user_func_array(array($helper, "execute"), $arguments);
        return $result;
    }


}
