<?php
/**
 * Pizza E96
 *
 * @author: Paul Melekhov
 */

namespace App\Helpers;

/**
 * Хэлпер, который позволяет вызывать под-шаблон внутри шаблона
 */
class Partial extends AbstractHelper
{
    /**
     * @param $template
     * @param array $params
     * @return string
     */
    public function execute($template, array $params = array())
    {
        /** @var $tpl \App\TemplateEngine */
        $tpl = $this->getResource("TemplateEngine");
        $content = $tpl->render($template, $params);
        return $content;
    }
}
