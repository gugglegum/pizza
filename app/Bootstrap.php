<?php
/**
 * Pizza E96
 *
 * @author: Paul Melekhov
 */

namespace App;

class Bootstrap extends BootstrapAbstract
{
    protected function _initConfig()
    {
        return include(APPLICATION_PATH . "/configs/application.php");
    }

    protected function _initRouter()
    {
        $routes = require(APPLICATION_PATH . "/configs/routes.php");
        $router = new Router($routes);
        return $router;
    }

    /**
     * @return \Zend_Db_Adapter_Pdo_Mysql
     */
    protected function _initDb()
    {
        $config = $this->getResource("Config");
        $db = new \Zend_Db_Adapter_Pdo_Mysql(array(
            "dbname"   => $config["db"]["dbname"],
            "username" => $config["db"]["user"],
            "password" => $config["db"]["password"],
            "host"     => $config["db"]["host"],
        ));

        // Инициализация соединения с БД (настройка кодировки и т.п.)
        if (!empty($config["db"]["init"])) {
            foreach ($config["db"]["init"] as $sql) {
                $db->query($sql);
            }
        }

        \Zend_Db_Table::setDefaultAdapter($db);

        return $db;
    }

    protected function _initTableManager()
    {
        $tableManager = new \App\TableManager($this);
        return $tableManager;
    }

    protected function _initTemplateEngine()
    {
        $helperBroker = $this->getResource("HelperBroker");
        $templateEngine = new \App\TemplateEngine(APPLICATION_PATH . "/templates", $helperBroker);
        return $templateEngine;
    }

    protected function _initHelperBroker()
    {
        $helperBroker = new HelperBroker($this);
        return $helperBroker;
    }

    protected function _initEmailSender()
    {
        $config = $this->getResource("Config");
        $tpl = $this->getResource("TemplateEngine");
        return new \App\EmailSender($config, $tpl);
    }
}
