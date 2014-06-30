<?php
/**
 * ВидеоВесточка.ру
 *
 * @author: Paul Melekhov
 */

namespace App\Filters;

abstract class AbstractFilter
{
    abstract public function filter($value);
}
