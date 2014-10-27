<?php
/**
 * Pizza E96
 *
 * @author: Paul Melekhov
 */

namespace App;

/**
 * Движок шаблонов
 *
 * @method \App\Helpers\HeadBlock headBlock() headBlock()    Возвращает экземпляр хэлпера HeadBlock
 * @method string sitePrefixPath() sitePrefixPath()
 * @method string|null headTitle() headTitle()
 * @method string escape() escape()
 * @method string url() url(\string $route, array $params = array())  Строит и возвращает URL по имени маршрута и набору параметров
 * @method mixed configVar() configVar(\string $varName)     Возвращает значение заданной переменной конфига
 * @method string partial() partial(\string $template, array $params)   Парсит шаблон и возвращает результат
 * @method array hiddenInputs() hiddenInputs(array $formData)       Возвращает массив из <input type="hidden" ... />
 * @method string plural() plural(\int $number, \string $one, \string $two, \string $five)    Возвращает числительное в нужном склонении
 */
class TemplateEngine
{
    /**
     * @var string
     */
    private $_viewPath;

    /**
     * @var HelperBroker
     */
    private $_helperBroker;

    /**
     * @param $viewPath
     * @param HelperBroker $helperBroker
     */
    public function __construct($viewPath, HelperBroker $helperBroker)
    {
        $this->_viewPath = $viewPath;
        $this->_helperBroker = $helperBroker;
    }

    public function render($template, array $params = array())
    {
        extract($params);
        ob_start();
        $file = $this->_viewPath . DIRECTORY_SEPARATOR . $template;
        if (!file_exists($file)) {
            throw new \App\Exception("Failed to render template {$template}: file not found");
        }
        require($file);
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

    /**
     * @param string $helperName
     * @param array $arguments
     * @return mixed
     */
    public function __call($helperName, array $arguments)
    {
        $helper = $this->_helperBroker->getHelper($helperName);
        $result = call_user_func_array(array($helper, "execute"), $arguments);
        return $result;
    }
}
