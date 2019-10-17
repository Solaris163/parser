<?php

namespace app\controllers;

/**
 * Class Controller
 * @package app\controllers
 * Класс является родительским для всех классов типа контроллер
 */
class Controller
{
    /**
     * @var string
     */
    private $action;
    /**
     * @var string
     */
    private $defaultAction = "index";

    /**
     * Метод выбирает какой action запустить
     * @param string $action
     * @param string $params
     */
    public function runAction($action = null, $params = null) {
        $this->action = $action?: $this->defaultAction; //если $action не существует, то $this->action = defaultAction
        $method = "action" . ucfirst($this->action); //название метода = 'action' + $this->action с заглавной буквы

        if(method_exists($this, $method)) { //вызов метода, если он существует, и передача в него аргумента $params.
            $this->$method($params);
        }else {
            echo '404 метод не существует';
        }
    }
}