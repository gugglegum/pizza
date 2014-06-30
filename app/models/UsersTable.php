<?php
/**
 * Pizza E96
 *
 * @author: Paul Melekhov
 */

namespace App\Models;

/**
 * @method \App\Models\UsersRow createRow() createRow(array $data) Создает нового пользователя
 * @method \App\Models\UsersRow fetchRow() fetchRow(\Zend_Db_Table_Select $select) Возвращает пользователя
 */
class UsersTable extends AbstractTable
{
    /**
     * Имя таблицы
     *
     * @var string
     */
    protected $_name = 'users';

    /**
     * Classname for rowset
     *
     * @var string
     */
    protected $_rowsetClass = '\\App\\Models\\UsersRowset';

    /**
     * Имя класса-строки
     *
     * @var string
     */
    protected $_rowClass = '\\App\\Models\\UsersRow';

    /**
     * Первичный ключ
     *
     * @var string
     */
    protected $_primary = 'id';

}
