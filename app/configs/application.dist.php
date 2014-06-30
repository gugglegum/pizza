<?php
/**
 * Pizza E96
 *
 * @author: Paul Melekhov
 */

$config = array(
    "db" => array(
        "host" => "localhost",
        "user" => "root",
        "password" => "svpodchdd",
        "dbname" => "pizza",
        "init" => array(
            "SET NAMES UTF8",
        ),
    ),
    "serverUrl" => "http://localhost",
    "serverHost" => "localhost",
    "sitePrefixPath" => "/pizza",
    "email" => array(
        "fromEmail" => "info@pizza.dev",
        "fromName" => "Пицца E96",
    ),
    // Параметры для APP_ENVIRONMENT=development
    "development" => array(
        "email" => "gugglgum@gmail.com",
        "phone" => "+79122887715",
    ),
    // Параметры отчётов об ошибках
    "errorReports" => array(
        "shutdownErrors" => true,
        "reportExceptions" => true,
        "outboxPath" => __DIR__ . "/../../outbox/errors",
        "emails" => array(
            "gugglegum@gmail.com",
        ),
    ),
    "fileSystemCharset" => "cp1251",
);

return $config;
