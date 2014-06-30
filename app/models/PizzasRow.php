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
 * @property $id int                ID пиццы
 * @property $title string          Название
 * @property $description string    Описание
 * @property $url string            URL пиццы
 * @property $image_url_large string    Картинка пиццы (большая)
 * @property $image_url_medium string   Картинка пиццы (средняя)
 * @property $image_url_small string    Картинка пиццы (маленькая)
 * @property $price int
 */
class PizzasRow extends AbstractRow
{
}
