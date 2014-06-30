<?php
/**
 * Pizza E96
 *
 * @author: Paul Melekhov
 */

namespace App\Helpers;

/**
 * Возвращает префикс пути сайта. Используется в том
 * случае, если сайт установлен не в корень document_root.
 * Просто возвращает значение опции конфига "sitePrefixPath"
 */
class SitePrefixPath extends AbstractHelper
{
    /**
     * @return string
     */
    public function execute()
    {
        $config = $this->getResource("Config");
        return $config["sitePrefixPath"];
    }
}
