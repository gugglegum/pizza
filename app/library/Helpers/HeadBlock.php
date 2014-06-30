<?php

namespace App\Helpers;

/**
 * Хэлпер для вставки произвольного куска HTML-кода в <head>-секцию. Подходит
 * для динамического подключения стилей и скриптов, которые таким образом могут
 * подключаться только тогда, когда они действительно требуются.
 *
 * (для того, чтобы это работало, в лайауте в <head>...</head>
 * должен содержаться вызов $this->headBlock()->getHtml();
 *
 * User: pavel
 * Date: 25.08.12
 * Time: 0:45
 * To change this template use File | Settings | File Templates.
 */
class HeadBlock extends AbstractHelper
{
    private $_blocks = array();

    /**
     * @return HeadBlock
     */
    public function execute()
    {
        return $this;
    }

    public function addBlock($html, $id = null, $priority = 100)
    {
        $data = array(
            "html" => $html,
            "priority" => $priority,
        );
        if ($id !== null) {
            $this->_blocks[$id] = $data;
        } else {
            $this->_blocks[] = $data;
        }
        return $this;
    }

    public function getBlocks()
    {
        usort($this->_blocks, function($block1, $block2) { return $block1["priority"] - $block2["priority"]; });
        $blocks = array();
        foreach ($this->_blocks as $data) {
            $blocks[] = $data["html"];
        }
        return $blocks;
    }

    public function getHtml($separator = "")
    {
        return implode($separator, $this->getBlocks());
    }

    public function __toString()
    {
        return $this->getHtml();
    }
}
