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

    // Выбор пиццы
    "selectPizza" => array(
        "pattern" => "#^/select-pizza$#",
        "reverse" => "/select-pizza",
        "params" => array(
            "controller" => "Start",
            "action" => "selectPizza",
        ),
    ),

    // Добавление заявки на пиццу
    "addPizza" => array(
        "pattern" => "#^/add-pizza/(\\d+)$#",
        "reverse" => "/add-pizza/#pizzaId#",
        "params" => array(
            "controller" => "Start",
            "action" => "addPizza",
            "pizzaId" => "#1#",
        ),
    ),

    // Добавление заявки на пиццу
    "deletePizza" => array(
        "pattern" => "#^/delete-pizza/(\\d+)$#",
        "reverse" => "/delete-pizza/#requestId#",
        "params" => array(
            "controller" => "Start",
            "action" => "deletePizza",
            "requestId" => "#1#",
        ),
    ),

    // Детали заказа
    "order" => array(
        "pattern" => "#^/order/(\\d+)$#",
        "reverse" => "/order/#orderId#",
        "params" => array(
            "controller" => "Start",
            "action" => "order",
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
