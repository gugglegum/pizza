<?php
/**
 * Pizza E96
 *
 * @author: Paul Melekhov
 */

namespace App\Models;

/**
 * Таблица с заявками на пиццу
 */
class RequestsTable extends AbstractTable
{
    /**
     * Имя таблицы
     *
     * @var string
     */
    protected $_name = 'requests';

    /**
     * Classname for rowset
     *
     * @var string
     */
    protected $_rowsetClass = '\\App\\Models\\RequestsRowset';

    /**
     * Имя класса-строки
     *
     * @var string
     */
    protected $_rowClass = '\\App\\Models\\RequestsRow';

    /**
     * Первичный ключ
     *
     * @var string
     */
    protected $_primary = 'id';

    public function getOrderPizzas($orderId)
    {
        $db = $this->getAdapter();
        $select = $db->select()
            ->from("pizzas", implode(", ", array(
                "pizzas.id as pizza_id",
                "pizzas.title",
                "pizzas.description",
                "pizzas.url",
                "pizzas.image_url_large",
                "pizzas.image_url_medium",
                "pizzas.image_url_small",
                "pizzas.price",
                "sum(requests.pieces) as total_pieces",
                "if(sum(requests.pieces) % 8 = 0,1,0) as ready"
            )))
            ->joinInner("requests", "requests.pizza_id = pizzas.id", "")
            ->where("requests.order_id = ?", $orderId)
            ->group("pizzas.id")
            ->order("ready DESC");
        $pizzas = $db->fetchAssoc($select);
        return $pizzas;
    }
}
