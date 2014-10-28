<?php
/**
 * Created by JetBrains PhpStorm.
 * User: paul
 * Date: 09.09.12
 * Time: 20:55
 * To change this template use File | Settings | File Templates.
 */

namespace App\Models;

class AbstractRow extends \Zend_Db_Table_Row_Abstract
{
    /**
     * Возвращает бутстрап
     *
     * @return \App\BootstrapAbstract
     * @throws Exception
     * @throws \Zend_Db_Table_Row_Exception
     */
    protected function _getBootstrap()
    {
        /** @var $table \App\Models\AbstractTable */
        $table = $this->_getTable();
        if ($table instanceof \App\Models\AbstractTable) {
            return $table->getBootstrap();
        } else {
            throw new \App\Models\Exception("Table of row " . get_class($this) . " must be instance of \\App\\Models\\AbstractTable");
        }
    }
}
