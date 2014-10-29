<?php
/**
 * Pizza E96
 *
 * @author: Paul Melekhov
 */

$siteRoutes = array(
    // Страница приветствия (главная)
    "start" => array(
        "pattern" => "#^/$#",
        "reverse" => "/",
        "params" => array(
            "controller" => "Start",
            "action" => "start",
        ),
    ),

    // Страница регистрации
    "register" => array(
        "pattern" => "#^/register$#",
        "reverse" => "/register",
        "params" => array(
            "controller" => "Auth",
            "action" => "register",
        ),
    ),

    // Страница входа
    "login" => array(
        "pattern" => "#^/login$#",
        "reverse" => "/login",
        "params" => array(
            "controller" => "Auth",
            "action" => "login",
        ),
    ),

    // Обработчик выхода
    "logout" => array(
        "pattern" => "#^/logout$#",
        "reverse" => "/logout",
        "params" => array(
            "controller" => "Auth",
            "action" => "logout",
        ),
    ),

    // Заказ
    "order" => array(
        "pattern" => "#^/order/(\\d+)$#",
        "reverse" => "/order/#orderId#",
        "params" => array(
            "controller" => "Order",
            "action" => "order",
            "orderId" => "#1#",
        ),
    ),

    // Создание заказа
    "createOrder" => array(
        "pattern" => "#^/order/create$#",
        "reverse" => "/order/create",
        "params" => array(
            "controller" => "Order",
            "action" => "createOrder",
        ),
    ),

    // Изменение заказа
    "editOrder" => array(
        "pattern" => "#^/order/(\\d+)/edit#",
        "reverse" => "/order/#orderId#/edit",
        "params" => array(
            "controller" => "Order",
            "action" => "editOrder",
            "orderId" => "#1#",
        ),
    ),

    // Изменение статуса заказа
    "statusOrder" => array(
        "pattern" => "#^/order/(\\d+)/status#",
        "reverse" => "/order/#orderId#/status",
        "params" => array(
            "controller" => "Order",
            "action" => "changeStatus",
            "orderId" => "#1#",
        ),
    ),

    // Выбор пиццы
    "selectPizza" => array(
        "pattern" => "#^/order/(\\d+)/select-pizza$#",
        "reverse" => "/order/#orderId#/select-pizza",
        "params" => array(
            "controller" => "Order",
            "action" => "selectPizza",
            "orderId" => "#1#",
        ),
    ),

    // Добавление заявки на пиццу
    "addPizza" => array(
        "pattern" => "#^/order/(\\d+)/add-pizza/(\\d+)$#",
        "reverse" => "/order/#orderId#/add-pizza/#pizzaId#",
        "params" => array(
            "controller" => "Order",
            "action" => "addPizza",
            "orderId" => "#1#",
            "pizzaId" => "#2#",
        ),
    ),

    // Добавление заявки на пиццу
    "deletePizza" => array(
        "pattern" => "#^/order/(\\d+)/delete-pizza/(\\d+)$#",
        "reverse" => "/order/#orderId#/delete-pizza/#requestId#",
        "params" => array(
            "controller" => "Order",
            "action" => "deletePizza",
            "orderId" => "#1#",
            "requestId" => "#2#",
        ),
    ),

    // Отмечание сдачи денег на пиццу
    "collectMoney" => array(
        "pattern" => "#^/ajax/order/(\\d+)/collect-money$#",
        "reverse" => "/ajax/order/#orderId#/collect-money",
        "params" => array(
            "controller" => "Order",
            "action" => "ajaxCollectMoney",
            "orderId" => "#1#",
        ),
    ),
);

$finalRoutes = array(
    "slashTail" => array(
        "pattern" => "#./$#",
        "reverse" => "",
        "params" => array(
            "controller" => "Start",
            "action" => "removeSlashTail",
        ),
    ),
);

$routes = array_merge(
    $siteRoutes,
    $finalRoutes
);

return $routes;
