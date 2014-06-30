<?php
/**
 * Pizza E96
 *
 * @author: Paul Melekhov
 */

namespace App;

class FrontController
{
    /**
     * @var Http\Request
     */
    private $_request;

    /**
     * @var Router
     */
    private $_router;

    /**
     * @var Bootstrap
     */
    private $_bootstrap;

    /**
     * @param Http\Request $request
     * @param BootstrapAbstract $bootstrap
     */
    public function __construct(Http\Request $request, \App\BootstrapAbstract $bootstrap)
    {
        $this->_request = $request;
        $this->_bootstrap = $bootstrap;
        $this->_router = $this->_bootstrap->getResource("Router");
    }

    /**
     * @throws \Exception
     */
    public function handleRequest()
    {
        try {
            $requestPath = $this->_request->getRequestPath();
            $config = $this->_bootstrap->getResource("Config");
            if ($config["sitePrefixPath"] !== "") {
                if (strpos($requestPath, $config["sitePrefixPath"]) !== 0) {
                    throw new \App\Exception("Invalid site configuration: wrong sitePrefixPath ('{$config["sitePrefixPath"]}')");
                }
                $requestPath = substr($requestPath, strlen($config["sitePrefixPath"]));
            }

            $data = $this->_router->route($requestPath);

            if ($data) {

                session_start();
                if (isset($_SESSION["user_id"])) {
                    /** @var $tm \App\TableManager */
                    $tm = $this->_bootstrap->getResource("TableManager");
                    /** @var $usersTable \App\Models\UsersTable */
                    $usersTable = $tm->getTable("Users");
                    $usersRowset = $usersTable->find($_SESSION["user_id"]);
                    if ($usersRowset->count() > 0) {
                        $user = $usersRowset->getRow(0);
                    } else {
                        unset($_SESSION["user_id"]);
                        $user = null;
                    }
                } else {
                    $user = null;
                }
                $data["params"]["user"] = $user;

                /** @var $controller \App\Controllers\AbstractController */
                $controller = new $data["class"]($this->_request, $this->_bootstrap);
                if (! $controller instanceof \App\Controllers\AbstractController) {
                    throw new \Exception("Controller class {$data["class"]} must be a child of App\\Controllers\\AbstractController class");
                }
                $controller->setParams($data["params"]);
                /** @var $response \App\Http\Response */
                if (! is_callable(array($controller, $data["method"]))) {
                    throw new \App\Exception("Controller ".get_class($controller)." has no callable method " . $data["method"]);
                }
                $controller->init();
                $response = $controller->{$data["method"]}();
                if (! $response instanceof \App\Http\Response) {
                    throw new \App\Exception("Action method ".get_class($controller)."::" . $data["method"] . "() has return not \\App\\Http\\Response object");
                }
                $response->send();
            } else {
                throw new \App\Http\NotFoundException();
            }
        } catch (\App\Http\Exception $e) {
            $this->_handleHttpError($e);
        } catch (\Exception $e) {
            if (APPLICATION_ENV == "development") {
                throw $e;
            } else {
                $this->_handleHttpError(new \App\Http\InternalServerErrorException($e));
            }
        }
    }

    /**
     * Обработка ошибок HTTP
     *
     * @param Http\Exception $e
     * @throws Exception
     */
    private function _handleHttpError(\App\Http\Exception $e)
    {
        $controller = new Controllers\ErrorController($this->_request, $this->_bootstrap);
        $controller->setParams(array(
            "exception" => $e,
        ));
        $controller->init();

        /** @var $response \App\Http\Response */
        $response = $controller->httpErrorAction();
        if (! $response instanceof \App\Http\Response) {
            throw new \App\Exception("Result from " . get_class($controller) . "::httpErrorAction() is not instance of \\App\\Http\\Response");
        }
        $response->send();
    }

    /**
     * Возвращает объект HTTP-запроса
     *
     * @return Http\Request
     */
    public function getRequest()
    {
        return $this->_request;
    }

    /**
     * Возвращает роутер
     *
     * @return Router
     */
    public function getRouter()
    {
        return $this->_router;
    }

    /**
     * Возвращает объект бутстрапа
     *
     * @return Bootstrap
     */
    public function getBootstrap()
    {
        return $this->_bootstrap;
    }
}
