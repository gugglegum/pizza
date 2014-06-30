<?php
/**
 * Pizza E96
 *
 * @author: Paul Melekhov
 */

namespace App;

class TableManager
{
    /**
     * @var \App\Bootstrap
     */
    private $_bootstrap;

    /**
     * @var array
     */
    private $_tables = array();

    public function __construct(\App\Bootstrap $bootstrap)
    {
        $this->_bootstrap = $bootstrap;
    }

    /**
     * Возвращает модель таблицы
     *
     * @param $name
     * @return \Zend_Db_Table_Abstract
     */
    public function getTable($name)
    {
        if (!isset($this->_tables[$name])) {
            $class = "\\App\\Models\\{$name}Table";
            $table = new $class($this->_bootstrap);
            $this->_tables[$name] = $table;
        }
        return $this->_tables[$name];
    }

}
