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
 */
class OrdersRow extends AbstractRow
{
}
