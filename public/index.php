<?php
/**
 * Pizza E96
 *
 * @author: Paul Melekhov
 */

require_once(__DIR__ . "/../init.php");
/** @var $bootstrap \App\Bootstrap */

$request = App\Http\Request::createFromGlobals();
$frontController = new \App\FrontController($request, $bootstrap);
$frontController->handleRequest();
