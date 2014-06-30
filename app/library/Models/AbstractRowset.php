<?php
/**
 * Created by JetBrains PhpStorm.
 * User: paul
 * Date: 09.09.12
 * Time: 20:55
 * To change this template use File | Settings | File Templates.
 */
namespace App\Models;

class AbstractRowset  extends \Zend_Db_Table_Rowset_Abstract
{
    /**
     * Возвращает ассоциативный массив из строк в Rowset'е
     * с ключами равными колонке $key (обычно "id")
     *
     * @param $key
     * @return array
     */
    public function toAssoc($key)
    {
        $assoc = array();
        foreach ($this as $row) {
            $assoc[$row->$key] = $row;
        }
        return $assoc;
    }
}
