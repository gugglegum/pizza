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
class OrderController extends AbstractController
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

    public function orderAction()
    {
        if (! $this->_user instanceof \App\Models\UsersRow) {
            return $this->_response->setRedirect($this->_helper->url("login") . "?next=" . $this->getRequest()->getRequestUri());
        }

        $orderId = $this->getParam("orderId");
        if (! $orderId) {
            throw new \App\Http\BadRequestException("Parameter 'orderId' was not passed to controller");
        }

        /** @var \App\Models\OrdersTable $ordersTable */
        $ordersTable = $this->_tm->getTable("Orders");
        /** @var OrdersRow $order */
        $order = $ordersTable->findRow($orderId);
        if (! $order) {
            throw new \HttpException("No such order #{$orderId}");
        }

        /** @var \App\Models\RequestsTable $requestsTable */
        $requestsTable = $this->_tm->getTable("Requests");
        $myRequests = $requestsTable->fetchAll($requestsTable->select()
                ->where("order_id = ?", $order->id)
                ->where("user_id = ?", $this->_user->id)
                ->order("pieces DESC")
        );

        $orderPrice = 0;
        $orderPizzas = $requestsTable->getOrderPizzas($order->id);
        foreach ($orderPizzas as &$orderPizza)
        {
            $orderPizza["requests"] = $requestsTable->fetchAll($requestsTable->select()
                    ->where("order_id = ?", $order->id)
                    ->where("pizza_id = ?", $orderPizza["pizza_id"])
                    ->order("pieces DESC")
            );
            if ($orderPizza["ready"]) {
                $orderPrice += $orderPizza["price"] * $orderPizza["total_pieces"] / 8;
            }
        }

        $content = $this->_tpl->render("order.phtml", array(
            "myRequests" => $myRequests,
            "orderPizzas" => $orderPizzas,
            "order" => $order,
            "orderPrice" => $orderPrice,
        ));
        $body = $this->_tpl->render("layouts/normal.phtml", array(
            "content" => $content,
            "user" => $this->_user,
        ));
        return $this->_response->setBody($body);
    }

    public function selectPizzaAction()
    {
        if (! $this->_user instanceof \App\Models\UsersRow) {
            return $this->_response->setRedirect($this->_helper->url("login") . "?next=" . $this->getRequest()->getRequestUri());
        }

        $orderId = $this->getParam("orderId");
        if (! $orderId) {
            throw new \App\Http\BadRequestException("Parameter 'orderId' was not passed to controller");
        }

        /** @var \App\Models\OrdersTable $ordersTable */
        $ordersTable = $this->_tm->getTable("Orders");
        /** @var OrdersRow $order */
        $order = $ordersTable->findRow($orderId);
        if (! $order) {
            throw new \HttpException("No such order #{$orderId}");
        }

        if ($order->status != OrdersRow::STATUS_ACTIVE) {
            throw new Exception("Can't modify inactive order");
        }

        /** @var $pizzasTable \App\Models\PizzasTable */
        $pizzasTable = $this->_tm->getTable("Pizzas");
        $pizzasRowset = $pizzasTable->fetchAll($pizzasTable->select()->order('price ASC'));

        $content = $this->_tpl->render("select_pizza.phtml", array(
            'pizzas' => $pizzasRowset,
            "orderId" => $order->id,
        ));
        $body = $this->_tpl->render("layouts/normal.phtml", array(
            "content" => $content,
            "user" => $this->_user,
        ));
        return $this->_response->setBody($body);
    }

    public function addPizzaAction()
    {
        if (! $this->_user instanceof \App\Models\UsersRow) {
            return $this->_response->setRedirect($this->_helper->url("login") . "?next=" . $this->getRequest()->getRequestUri());
        }

        $orderId = $this->getParam("orderId");
        if (! $orderId) {
            throw new \App\Http\BadRequestException("Parameter 'orderId' was not passed to controller");
        }

        $pizzaId = $this->getParam("pizzaId");
        if (! $pizzaId) {
            throw new \App\Http\BadRequestException("Parameter 'pizzaId' was not passed to controller");
        }

        /** @var \App\Models\OrdersTable $ordersTable */
        $ordersTable = $this->_tm->getTable("Orders");
        /** @var OrdersRow $order */
        $order = $ordersTable->findRow($orderId);
        if (! $order) {
            throw new Exception("No active order");
        }

        /** @var $pizzasTable \App\Models\PizzasTable */
        $pizzasTable = $this->_tm->getTable("Pizzas");
        $pizzasRow = $pizzasTable->fetchRow($pizzasTable->select()->where("id = ?", $pizzaId));
        if (! $pizzasRow instanceof PizzasRow) {
            throw new \App\Http\NotFoundException("Pizza not found");
        }

        $form = new \App\Forms\AddPizzaForm();

        if ($this->getRequest()->isPost()) {
            if ($order->status != OrdersRow::STATUS_ACTIVE) {
                throw new Exception("Can't modify inactive order");
            }
            $values = $this->getRequest()->getPostParams();
            $form->setFormValues($values);
            if ($form->isValid()) {
                $requestsTable = $this->_tm->getTable("Requests");
                $requestsRow = $requestsTable->createRow(array(
                    "order_id" => $order->id,
                    "user_id" => $this->_user->id,
                    "pizza_id" => $pizzaId,
                    "pieces" => $form->getElement("pieces")->getValue(),
                ));
                $requestsRow->save();
                return $this->_response->setRedirect($this->_helper->url("order", [ "orderId" => $order->id ]));
            }
        }

        $content = $this->_tpl->render("add_pizza.phtml", array(
            "form" => $form,
            "pizza" => $pizzasRow,
            "order" => $order,
        ));
        $body = $this->_tpl->render("layouts/normal.phtml", array(
            "content" => $content,
            "user" => $this->_user,
        ));
        return $this->_response->setBody($body);
    }

    public function deletePizzaAction()
    {
        if (! $this->_user instanceof \App\Models\UsersRow) {
            return $this->_response->setRedirect($this->_helper->url("login") . "?next=" . $this->getRequest()->getRequestUri());
        }

        $orderId = $this->getParam("orderId");
        if (! $orderId) {
            throw new \App\Http\BadRequestException("Parameter 'orderId' was not passed to controller");
        }

        $requestId = $this->getParam("requestId");
        if (! $requestId) {
            throw new \App\Http\BadRequestException("Parameter 'requestId' was not passed to controller");
        }

        /** @var \App\Models\OrdersTable $ordersTable */
        $ordersTable = $this->_tm->getTable("Orders");
        /** @var OrdersRow $order */
        $order = $ordersTable->findRow($orderId);
        if (! $order) {
            throw new \HttpException("No such order #{$orderId}");
        }

        if ($order->status != OrdersRow::STATUS_ACTIVE) {
            throw new Exception("Can't modify inactive order");
        }

        if ($this->getRequest()->isPost()) {
            $requestsTable = $this->_tm->getTable("Requests");
            $requestsTable->delete(array(
                "user_id = " . (int) $this->_user->id,
                "id = " . (int) $requestId,
            ));
            return $this->_response->setRedirect($this->_helper->url("order", [ "orderId" => $order->id ]));
        } else {
            throw new \App\Http\MethodNotAllowedException("This URL allows only POST requests");
        }
    }
}
