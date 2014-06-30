<?php
/**
 * Pizza E96
 *
 * @author: Paul Melekhov
 */

namespace App\Controllers;

class ErrorController extends AbstractController
{
    private static $_layout = "layouts/normal.phtml";

    /**
     * @var \App\TemplateEngine
     */
    private $_tpl;

    /**
     * @var \App\Http\Response
     */
    private $_response;

    public function __construct(\App\Http\Request $request, \App\Bootstrap $bootstrap)
    {
        parent::__construct($request, $bootstrap);
        $this->_tpl = $this->getResource("TemplateEngine");
    }

    /**
     * Инициализационный метод, вызывается фронт-контроллером перед вызовом Action-метода.
     * Все свойства контроллера, которые способны менять свое состояние в ходе выполнения
     * отдельного экшина, должны инициализироваться (обнуляться) в этом методе. Прочие
     * свойства должны инициализироваться в конструкторе. Это может быть важно при
     * Unit-тестировании.
     *
     * @return void
     */
    public function init()
    {
        $this->_response = new \App\Http\Response();
    }

    public static function setLayout($file)
    {
        self::$_layout = $file;
    }

    public function httpErrorAction()
    {
        /** @var $httpException \App\Http\Exception */
        $httpException = $this->getParam("exception");
        $statusCode = $httpException->getHttpStatusCode();
        $statusMessage = $httpException->getHttpStatusMessage();
        $this->_response->setStatus($statusCode, $statusMessage);

        $hasTemplates = array(400, 403, 404, 405, 500, 503);
        if (in_array($statusCode, $hasTemplates)) {
            $content = $this->_tpl->render("http_errors/".$statusCode.".phtml", array(
                "request" => $this->getRequest(),
                "exception" => $httpException,
            ));
            $body = $this->_tpl->render(self::$_layout, array(
                "content" => $content,
            ));
        } else {
            $body = "{$statusCode} {$statusMessage}";
            $this->_response->setHeader("Content-Type", "text/plain");
        }
        return $this->_response->setBody($body);
    }
}
                    