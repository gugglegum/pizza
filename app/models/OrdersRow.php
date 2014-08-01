<?php
/**
 * Pizza E96
 *
 * @author: Paul Melekhov
 */

namespace App\Models;

/**
 * Пиццы на сайте
 *
 * @property $id int                ID заказа
 * @property $delivery              Дата-время доставки в формате MySQL DATETIME
 * @property $created_ts            UNIX-time создания заказа
 * @property $is_active             Признак активного в данный момент заказа
 * @property $discount              Фиксированная скидка на заказ
 * @property $discount_percent      Процентная скидка на заказ
 */
class OrdersRow extends AbstractRow
{
}
