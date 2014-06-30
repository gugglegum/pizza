<?php
/**
 * Pizza E96
 *
 * @author: Paul Melekhov
 */

namespace App;

abstract class BootstrapAbstract
{
    private $_resources = array();

    public function getResource($name)
    {
        $name = strtolower($name);
        if (!isset($this->_resources[$name])) {
            $initMethod = "_init" . $name;
            if (method_exists($this, $initMethod)) {
                $this->_resources[$name] = $this->{$initMethod}();
            } else {
                throw new \App\Exception("Unknown bootstrap resource '{$name}'");
            }
        }
        return $this->_resources[$name];
    }
}
