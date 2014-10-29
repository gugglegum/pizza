<?php
/**
 * Pizza E96
 *
 * @author: Paul Melekhov
 */

namespace App\Controllers;
use App\Exception;
use App\Http\BadRequestException;
use App\Http\ForbiddenException;
use App\Http\MethodNotAllowedException;
use App\Http\NotFoundException;
use App\Models\OrdersMoneyRow;
use App\Models\OrdersMoneyTable;
use App\Models\OrdersRow;
use App\Models\OrdersTable;
use App\Models\PizzasRow;
use App\Models\RequestsRow;
use App\Models\UsersRow;

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
        $orderId = $this->getParam("orderId");
        if (! $orderId) {
            throw new BadRequestException("Parameter 'orderId' was not passed to controller");
        }

        /** @var \App\Models\OrdersTable $ordersTable */
        $ordersTable = $this->_tm->getTable("Orders");
        /** @var OrdersRow $order */
        $order = $ordersTable->findRow($orderId);
        if (! $order) {
            throw new NotFoundException("No such order #{$orderId}");
        }

        /** @var \App\Models\RequestsTable $requestsTable */
        $requestsTable = $this->_tm->getTable("Requests");
        $myRequests = $this->_user ? $requestsTable->fetchAll($requestsTable->select()
                ->where("order_id = ?", $order->id)
                ->where("user_id = ?", $this->_user->id)
                ->order("pieces DESC")
        ) : null;

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
            "canEdit" => $this->_user && $order->creator == $this->_user->id,
        ));
        $body = $this->_tpl->render("layouts/normal.phtml", array(
            "content" => $content,
            "user" => $this->_user,
        ));
        return $this->_response->setBody($body);
    }

    public function selectPizzaAction()
    {
        if (! $this->_user instanceof UsersRow) {
            return $this->_response->setRedirect($this->_helper->url("login") . "?next=" . $this->getRequest()->getRequestUri());
        }

        $orderId = $this->getParam("orderId");
        if (! $orderId) {
            throw new BadRequestException("Parameter 'orderId' was not passed to controller");
        }

        /** @var \App\Models\OrdersTable $ordersTable */
        $ordersTable = $this->_tm->getTable("Orders");
        /** @var OrdersRow $order */
        $order = $ordersTable->findRow($orderId);
        if (! $order) {
            throw new NotFoundException("No such order #{$orderId}");
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
        if (! $this->_user instanceof UsersRow) {
            return $this->_response->setRedirect($this->_helper->url("login") . "?next=" . $this->getRequest()->getRequestUri());
        }

        $orderId = $this->getParam("orderId");
        if (! $orderId) {
            throw new BadRequestException("Parameter 'orderId' was not passed to controller");
        }

        $pizzaId = $this->getParam("pizzaId");
        if (! $pizzaId) {
            throw new BadRequestException("Parameter 'pizzaId' was not passed to controller");
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

                /** @var RequestsRow $requestsRow */
                $requestsRow = $requestsTable->fetchRow($requestsTable->select()
                    ->where("order_id = ?", $order->id)
                    ->where("user_id = ?", $this->_user->id)
                    ->where("pizza_id = ?", $pizzaId)
                );

                if (! $requestsRow) {
                    $requestsRow = $requestsTable->createRow(array(
                        "order_id" => $order->id,
                        "user_id" => $this->_user->id,
                        "pizza_id" => $pizzaId,
                        "pieces" => $form->getElement("pieces")->getValue(),
                    ));
                } else {
                    $requestsRow->pieces += $form->getElement("pieces")->getValue();
                }
                $requestsRow->save();
                return $this->_response->setRedirect($this->_helper->url("order", [ "orderId" => $order->id ]));
            }
        } else {
            $form->setFormValues([
                "pieces" => 1,
            ]);
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
        if (! $this->_user instanceof UsersRow) {
            return $this->_response->setRedirect($this->_helper->url("login") . "?next=" . $this->getRequest()->getRequestUri());
        }

        $orderId = $this->getParam("orderId");
        if (! $orderId) {
            throw new BadRequestException("Parameter 'orderId' was not passed to controller");
        }

        $requestId = $this->getParam("requestId");
        if (! $requestId) {
            throw new BadRequestException("Parameter 'requestId' was not passed to controller");
        }

        /** @var \App\Models\OrdersTable $ordersTable */
        $ordersTable = $this->_tm->getTable("Orders");
        /** @var OrdersRow $order */
        $order = $ordersTable->findRow($orderId);
        if (! $order) {
            throw new NotFoundException("No such order #{$orderId}");
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
            throw new MethodNotAllowedException("This URL allows only POST requests");
        }
    }

    public function createOrderAction()
    {
        if (! $this->_user instanceof UsersRow) {
            return $this->_response->setRedirect($this->_helper->url("login") . "?next=" . $this->getRequest()->getRequestUri());
        }

        $form = new \App\Forms\CreateOrderForm();

        if ($this->getRequest()->isPost()) {
            $values = $this->getRequest()->getPostParams();
            $form->setFormValues($values);
            if ($form->isValid()) {
                /** @var OrdersTable $ordersTable */
                $ordersTable = $this->_tm->getTable("Orders");

                $discountAbsolute = $form->getElement("discount_absolute")->getValue();
                $discountPercent = $form->getElement("discount_percent")->getValue();
                $note = $form->getElement("note")->getValue();

                /** @var OrdersRow $order */
                $order = $ordersTable->createRow(array(
                    "delivery" => $form->getElement("delivery")->getValue(),
                    "created_ts" => time(),
                    "status" => OrdersRow::STATUS_ACTIVE,
                    "creator" => $this->_user->id,
                    "discount_absolute" => $discountAbsolute != 0 ? $discountAbsolute : null,
                    "discount_percent" => $discountPercent != 0 ? $discountPercent : null,
                    "note" => $note != "" ? $note : null,
                ));
                $order->save();
                return $this->_response->setRedirect($this->_helper->url("order", [ "orderId" => $order->id ]));
            }
        } else {
            $form->setFormValues([
                "delivery" => date('Y-m-d') . " 17:00:00",
            ]);
        }

        $content = $this->_tpl->render("create_order.phtml", array(
            "form" => $form,
            "order" => null,
        ));
        $body = $this->_tpl->render("layouts/normal.phtml", array(
            "content" => $content,
            "user" => $this->_user,
        ));
        return $this->_response->setBody($body);
    }

    public function editOrderAction()
    {
        if (! $this->_user instanceof UsersRow) {
            return $this->_response->setRedirect($this->_helper->url("login") . "?next=" . $this->getRequest()->getRequestUri());
        }

        $orderId = $this->getParam("orderId");
        if (! $orderId) {
            throw new BadRequestException("Parameter 'orderId' was not passed to controller");
        }

        /** @var \App\Models\OrdersTable $ordersTable */
        $ordersTable = $this->_tm->getTable("Orders");
        /** @var OrdersRow $order */
        $order = $ordersTable->findRow($orderId);
        if (! $order) {
            throw new NotFoundException("No such order #{$orderId}");
        }

        if ($order->creator != $this->_user->id) {
            throw new ForbiddenException("Can't edit order which is not owned");
        }

        $form = new \App\Forms\CreateOrderForm();

        if ($this->getRequest()->isPost()) {
            $values = $this->getRequest()->getPostParams();
            $form->setFormValues($values);
            if ($form->isValid()) {
                $discountAbsolute = $form->getElement("discount_absolute")->getValue();
                $discountPercent = $form->getElement("discount_percent")->getValue();
                $note = $form->getElement("note")->getValue();
                $order->setFromArray([
                    "delivery" => $form->getElement("delivery")->getValue(),
                    "discount_absolute" => $discountAbsolute != 0 ? $discountAbsolute : null,
                    "discount_percent" => $discountPercent != 0 ? $discountPercent : null,
                    "note" => $note != "" ? $note : null,
                ]);
                $order->save();
                return $this->_response->setRedirect($this->_helper->url("order", [ "orderId" => $order->id ]));
            }
        } else {
            $form->setFormValues([
                "delivery" => $order->delivery,
                "discount_absolute" => $order->discount_absolute,
                "discount_percent" => $order->discount_percent,
                "note" => $order->note,
            ]);
        }

        $content = $this->_tpl->render("create_order.phtml", array(
            "form" => $form,
            "order" => $order,
        ));
        $body = $this->_tpl->render("layouts/normal.phtml", array(
            "content" => $content,
            "user" => $this->_user,
        ));
        return $this->_response->setBody($body);
    }

    public function changeStatusAction()
    {
        if (! $this->_user instanceof UsersRow) {
            return $this->_response->setRedirect($this->_helper->url("login") . "?next=" . $this->getRequest()->getRequestUri());
        }

        $orderId = $this->getParam("orderId");
        if (! $orderId) {
            throw new BadRequestException("Parameter 'orderId' was not passed to controller");
        }

        /** @var \App\Models\OrdersTable $ordersTable */
        $ordersTable = $this->_tm->getTable("Orders");
        /** @var OrdersRow $order */
        $order = $ordersTable->findRow($orderId);
        if (! $order) {
            throw new NotFoundException("No such order #{$orderId}");
        }

        if ($order->creator != $this->_user->id) {
            throw new ForbiddenException("Can't edit order which is not owned");
        }

        if ($this->getRequest()->isPost()) {
            $status = $this->getRequest()->getPostParam("status");
            if ($status === null) {
                throw new BadRequestException("Missing POST parameter 'status'");
            }
            $order->status = $status;
            $order->save();
            return $this->_response->setRedirect($this->_helper->url("order", [ "orderId" => $order->id ]));
        } else {
            throw new MethodNotAllowedException("This URL allows only POST requests");
        }
    }

    /**
     * [AJAX] Сохранение записи о сдаче денег на пиццу кем-то
     *
     * @throws BadRequestException
     * @throws Exception
     * @throws ForbiddenException
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     */
    public function ajaxCollectMoneyAction()
    {
        if (! $this->_user instanceof UsersRow) {
            return $this->_response->setRedirect($this->_helper->url("login") . "?next=" . $this->getRequest()->getRequestUri());
        }

        $orderId = $this->getParam("orderId");
        if (! $orderId) {
            throw new BadRequestException("Parameter 'orderId' was not passed to controller");
        }

        /** @var \App\Models\OrdersTable $ordersTable */
        $ordersTable = $this->_tm->getTable("Orders");
        /** @var OrdersRow $order */
        $order = $ordersTable->findRow($orderId);
        if (! $order) {
            throw new NotFoundException("No such order #{$orderId}");
        }

        if ($order->creator != $this->_user->id) {
            throw new ForbiddenException("Can't edit order which is not owned");
        }

        if ($this->getRequest()->isPost()) {
            $userId = $this->getRequest()->getPostParam("userId");
            $amount = $this->getRequest()->getPostParam("amount");

            /** @var OrdersMoneyTable $ordersMoneyTable */
            $ordersMoneyTable = $this->_tm->getTable("OrdersMoney");

            if ($amount > 0) {
                $ordersMoney = $ordersMoneyTable->fetchRow(
                    $ordersMoneyTable->select()
                        ->where("order_id = ?", $orderId)
                        ->where("user_id = ?", $userId)
                );

                if ($ordersMoney instanceof OrdersMoneyRow) {
                    $ordersMoney->amount = $amount;
                } else {
                    $ordersMoney = $ordersMoneyTable->createRow([
                        "order_id" => $orderId,
                        "user_id" => $userId,
                        "amount" => $amount,
                    ]);
                }
                $ordersMoney->save();
            } else {
                $ordersMoney = $ordersMoneyTable->fetchRow(
                    $ordersMoneyTable->select()
                        ->where("order_id = ?", $orderId)
                        ->where("user_id = ?", $userId)
                );
                if ($ordersMoney instanceof OrdersMoneyRow) {
                    $ordersMoney->delete();
                }
            }
            return $this->_response;
        } else {
            throw new MethodNotAllowedException("This URL allows only POST requests");
        }

    }
}
