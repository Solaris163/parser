<?php


use app\engine\Request;

include "../engine/Autoload.php";
include "../config/config.php";

spl_autoload_register([new Autoload(), 'loadClass']);

$request = new Request();//создаем объект $request, который прочитает команды из URL

$controllerName = $request->getControllerName()?: 'show'; //имя контроллера, который будет вызван
$actionName = $request->getActionName()?: 'index'; //имя экшена, который будет вызван внутри контроллера
$params = $request->getParams(); //параметры get - запросов

//Найдем название класса контроллера, который необходимо вызвать
$controllerClass = "app\\controllers\\" . ucfirst($controllerName) . "Controller";

if (class_exists($controllerClass)) {
    $controller = new $controllerClass();
    $controller->runAction($actionName, $params); //передаем имя метода и другие параметры из URL
}else echo "404 контроллер {$controllerName} не существует";