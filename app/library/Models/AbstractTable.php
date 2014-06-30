<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pavel
 * Date: 08.09.12
 * Time: 0:02
 * To change this template use File | Settings | File Templates.
 */

namespace App\Models;

class AbstractTable extends \Zend_Db_Table_Abstract
{
    /**
     * @var \App\BootstrapAbstract
     */
    protected $_bootstrap;

    /**
     * @param \App\BootstrapAbstract $bootstrap
     */
    public function __construct(\App\BootstrapAbstract $bootstrap)
    {
        $this->_bootstrap = $bootstrap;
        $db = $this->_bootstrap->getResource("DB");
        $config = array(
            \Zend_Db_Table_Abstract::ADAPTER => $db,
        );
        parent::__construct($config);
    }

    /**
     * @return \App\BootstrapAbstract
     */
    public function getBootstrap()
    {
        return $this->_bootstrap;
    }

    /**
     * Finds single row by primary key value
     *
     * @param  mixed $key The value of the primary key
     * @return null|\Zend_Db_Table_Row Row matching the criteria or null if none matches
     */
    public function findRow($key)
    {
        $rowset = $this->find($key);
        if ($rowset->count() != 0) {
            return $rowset->getRow(0);
        } else {
            return null;
        }
    }

    public function _resolveTableKeyByNumber($indexNum)
    {
        $db = $this->getAdapter();
        $stmt = $db->query("SHOW INDEX FROM " . $db->quoteIdentifier($this->_name));
        $fetchNum = 1;
        while ($row = $stmt->fetch(\Zend_Db::FETCH_ASSOC)) {
            if ($fetchNum == $indexNum) {
                return $row["Key_name"];
            }
            $fetchNum++;
        }
        throw new Exception("Unable to resolve table key with number {$indexNum} into its name (number is bigger than keys amount)");
    }


    /**
     * Возвращает кол-во строк, получаемых данным запросом
     *
     * @param \Zend_Db_Table_Select $select
     * @throws Exception
     * @return int
     */
    public function getRowsCount(\Zend_Db_Table_Select $select)
    {
        $sql = $select->__toString();
        $sql = preg_replace('/^(select)(\s.+\s)(from\s)/iU', '${1} count(*) `count` ${3}', $sql, 1, $count);
        if ($count != 1) {
            throw new Exception("Failed to make 'select count(*)' query");
        }
        $db = $select->getAdapter();
        $row = $db->fetchRow($sql);
        $count = $row["count"];
        return (int) $count;
    }

}
