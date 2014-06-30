<?php
/**
 * Pizza E96
 *
 * @author: Paul Melekhov
 */

namespace App\Helpers;

/**
 * Хэлпер для формирования <title>
 *
 * Вызов хэлпера со строковым аргументом сохраняет этот аргумент,
 * а при вызове без аргументов хэлпер возвращает ранее сохраненный.
 * При выводе требуется дополнительно экранировать заголовок.
 *
 * Применяется это следующим образом: в шаблоне сохраняется значение заголовка,
 * а затем в лайауте оно выводится.
 */
class HeadTitle extends AbstractHelper
{
    /**
     * Сохраненный заголовок страницы
     *
     * @var string
     */
    private $_title;

    /**
     * @param null $title
     * @return string|null
     * @throws \App\Exception
     */
    public function execute($title = null)
    {
        if ($title !== null) {
            if (! is_string($title)) {
                throw new \App\Exception("Helper HeadTitle expected string or nothing");
            }
            $this->_title = $title;
        } else {
            return $this->_title;
        }
        return null;
    }
}
