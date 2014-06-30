<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pavel
 * Date: 14.08.12
 * Time: 18:10
 * To change this template use File | Settings | File Templates.
 */

namespace App\Controllers;

abstract class AbstractController
{
    /**
     * @var \App\Http\Request
     */
    private $_request;

    /**
     * @var \App\Bootstrap
     */
    private $_bootstrap;

    /**
     * @var array
     */
    private $_params;

    public function __construct(\App\Http\Request $request, \App\Bootstrap $bootstrap)
    {
        $this->_request = $request;
        $this->_bootstrap = $bootstrap;
    }

    /**
     * Инициализационный метод, вызывается фронт-контроллером перед вызовом Action-метода.
     * Все свойства контроллера, которые способны менять свое состояние в ходе выполнения
     * отдельного экшина, должны инициализироваться (обнуляться) в этом методе. Прочие
     * свойства должны инициализироваться в конструкторе. Это может быть важно при
     * Unit-тестировании.
     *
     * @return void
     */
    public function init() {}

	/**
	 * @return \App\Http\Request
	 */
	public function getRequest()
    {
        return $this->_request;
    }

	/**
	 * @return array
	 */
	public function getParams()
    {
        return $this->_params;
    }

    /**
     * Возвращает параметр контроллера
     *
     * @param $name
     * @throws Exception
     * @return string|null
     */
    public function getParam($name)
    {
        if (! array_key_exists($name, $this->_params)) {
            throw new Exception("Parameter '{$name}' was not passed to controller " . get_class($this));
        }
        return $this->_params[$name];
    }

    public function setParams(array $params)
    {
        $this->_params = $params;
        return $this;
    }

    public function getResource($name)
    {
        return $this->_bootstrap->getResource($name);
    }
}
