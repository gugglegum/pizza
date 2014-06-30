<?php
/**
 * Pizza E96
 *
 * @author: Paul Melekhov
 */

namespace App\Filters;

/**
 * Удаляет все пробелы из строки
 */
class RemoveAllSpaces extends AbstractFilter
{
    public function filter($value)
    {
        $value = preg_replace("/\\s+/", "", $value);
        return $value;
    }

}
