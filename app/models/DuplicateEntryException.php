<?php
/**
 * Pizza E96
 *
 * @author: Paul Melekhov
 */

namespace App\Models;

/**
 * Исключение для ситуаций, когда сохранение записи в базу данных привело к ошибке
 * нарушения уникальности по какому-либо ключу.
 */
class DuplicateEntryException extends \App\Exception
{
    /**
     * Имя ключа, на котором произошла ошибка Duplicate Entry
     *
     * @var string
     */
    private $_key;

    /**
     * @var \Zend_Db_Table_Abstract
     */
    private $_table;

    /**
     * @param string $key
     * @param \Zend_Db_Table_Abstract $table
     * @param \Exception $previous
     */
    public function __construct($key, \Zend_Db_Table_Abstract $table, \Exception $previous = null)
    {
        $tableName = $table->info(\Zend_Db_Table_Abstract::NAME);
        parent::__construct("Duplicated entry for key '{$key}' in table '{$tableName}'", 0, $previous);
        $this->_key = $key;
        $this->_table = $table;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->_key;
    }

    /**
     * @return \Zend_Db_Table_Abstract
     */
    public function getTable()
    {
        return $this->_table;
    }
}
