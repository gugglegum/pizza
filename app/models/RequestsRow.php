<?php
/**
 * Pizza E96
 *
 * @author: Paul Melekhov
 */

namespace App\Models;

/**
 * Заявки на пиццу
 *
 * @property $id int                ID заявки
 * @property $order_id int          ID заказа
 * @property $user_id int           ID пользователя
 * @property $pizza_id int          ID пиццы
 * @property $pieces int            Кол-во кусков
 */
class RequestsRow extends AbstractRow
{
    /**
     * Возвращает пользователя
     *
     * @return UsersRow
     */
    public function getUser()
    {
        /** @var $tm \App\TableManager */
        $tm = $this->_getBootstrap()->getResource("TableManager");
        /** @var $usersTable \App\Models\UsersTable */
        $usersTable = $tm->getTable("Users");
        $user = $usersTable->find($this->user_id)->getRow(0);
        return $user;
    }

    /**
     * Возвращает пиццу
     *
     * @return PizzasRow
     */
    public function getPizza()
    {
        /** @var $tm \App\TableManager */
        $tm = $this->_getBootstrap()->getResource("TableManager");
        /** @var $pizzasTable \App\Models\PizzasTable */
        $pizzasTable = $tm->getTable("Pizzas");
        $pizza = $pizzasTable->find($this->pizza_id)->getRow(0);
        return $pizza;
    }

}
