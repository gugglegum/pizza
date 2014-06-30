<?php
/**
 * Pizza E96
 *
 * @author: Paul Melekhov
 */

namespace App\Helpers;

class ConfigVar extends AbstractHelper
{
    /**
     * Возвращает значение заданной переменной конфига
     *
     * @param string $varName
     * @return mixed
     */
    public function execute($varName)
    {
        /** @var $router \App\Router */
        $config = $this->getResource("Config");
        $var = $config[$varName];
        return $var;
    }
}
