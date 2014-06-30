<?php
/**
 * Pizza E96
 *
 * @author: Paul Melekhov
 */

namespace App\Helpers;

/**
 * Хэлпер для экранирования строк для безопасной вставки в HTML.
 * Является по сути более коротким аналогом функции htmlspecialchars()
 */
class Escape extends AbstractHelper
{
    /**
     * @param string $str
     * @return string
     */
    public function execute($str)
    {
        $str = htmlspecialchars($str, ENT_COMPAT, "UTF-8");
        return $str;
    }
}
