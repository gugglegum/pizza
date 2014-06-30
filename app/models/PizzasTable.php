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
class PizzasTable extends AbstractTable
{
    /**
     * Имя таблицы
     *
     * @var string
     */
    protected $_name = 'pizzas';

    /**
     * Classname for rowset
     *
     * @var string
     */
    protected $_rowsetClass = '\\App\\Models\\PizzasRowset';

    /**
     * Имя класса-строки
     *
     * @var string
     */
    protected $_rowClass = '\\App\\Models\\PizzasRow';

    /**
     * Первичный ключ
     *
     * @var string
     */
    protected $_primary = 'id';

}
