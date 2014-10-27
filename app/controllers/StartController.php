<?php
/**
 * Pizza E96
 *
 * @author: Paul Melekhov
 */

namespace App\Controllers;
use App\Exception;
use App\Models\OrdersRow;
use App\Models\PizzasRow;

/**
 * Контроллер стартовой страницы
 *
 */
class StartController extends AbstractController
{
    /**
     * @var \App\TemplateEngine
     */
    private $_tpl;

    /**
     * @var \App\TableManager
     */
    private $_tm;

    /**
     * @var \App\Http\Response
     */
    private $_response;

    /**
     * @var \App\HelperBroker
     */
    private $_helper;

    /**
     * @var \App\Models\UsersRow
     */
    private $_user;

    public function __construct(\App\Http\Request $request, \App\Bootstrap $bootstrap)
    {
        parent::__construct($request, $bootstrap);
        $this->_tpl = $this->getResource("TemplateEngine");
        $this->_tm = $this->getResource("TableManager");
        $this->_helper = $this->getResource("HelperBroker");
    }

    public function init()
    {
        $this->_response = new \App\Http\Response();
        $this->_user = $this->getParam("user");
    }

    public function startAction()
    {
        /** @var \App\Models\OrdersTable $ordersTable */
        $ordersTable = $this->_tm->getTable("Orders");
        /** @var OrdersRow $order */
        $activeOrders = $ordersTable->fetchAll(
            $ordersTable->select()
                ->where("status = ?", OrdersRow::STATUS_ACTIVE)
                ->order('id DESC')
        );
        $historyOrders = $ordersTable->fetchAll(
            $ordersTable->select()
                ->where("status != ?", OrdersRow::STATUS_ACTIVE)
                ->where("status != ?", OrdersRow::STATUS_CANCELLED)
                ->order('id DESC')
        );

        $content = $this->_tpl->render("start.phtml", array(
            "activeOrders" => $activeOrders,
            "historyOrders" => $historyOrders,
        ));
        $body = $this->_tpl->render("layouts/normal.phtml", array(
            "content" => $content,
            "user" => $this->_user,
        ));
        return $this->_response->setBody($body);
    }

    public function removeSlashTailAction()
    {
        $request = $this->getRequest();
        $url = $request->getUrl();
        $url->setPath(rtrim($url->getPath(), "/"));
        return $this->_response->setRedirect($url->getAbsoluteUrl());
    }
}
