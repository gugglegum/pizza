<?php
/**
 * Pizza E96
 *
 * @author: Paul Melekhov
 */

namespace App\Models;

/**
 * Таблица с пиццами
 */
class OrdersTable extends AbstractTable
{
    /**
     * Имя таблицы
     *
     * @var string
     */
    protected $_name = 'orders';

    /**
     * Classname for rowset
     *
     * @var string
     */
    protected $_rowsetClass = '\\App\\Models\\OrdersRowset';

    /**
     * Имя класса-строки
     *
     * @var string
     */
    protected $_rowClass = '\\App\\Models\\OrdersRow';

    /**
     * Первичный ключ
     *
     * @var string
     */
    protected $_primary = 'id';
}
