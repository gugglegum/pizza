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
 * @property $order_id int          ID заказа
 * @property $user_id int           ID пользователя
 * @property $amount int            Сумма (в рублях)
 * @property $created string        Дата-время создания записи
 */
class OrdersMoneyRow extends AbstractRow
{
    /**
     * Возвращает заказ
     *
     * @return OrdersRow
     */
    public function getOrder()
    {
        /** @var $tm \App\TableManager */
        $tm = $this->_getBootstrap()->getResource("TableManager");
        /** @var $usersTable \App\Models\UsersTable */
        $ordersTable = $tm->getTable("Orders");
        $order = $ordersTable->find($this->order_id)->getRow(0);
        return $order;
    }

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

}
