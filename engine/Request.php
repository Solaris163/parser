<?php


namespace app\engine;


/**
 * Класс обрабатывает все запросы из URL, возвращает имя controller, имя action и массив параметров
 * Class Request
 * @package app\engine
 */
class Request
{
    /**
     * @var string адрес URL
     */
    protected $requestString;
    /**
     * @var string Название контроллера
     */
    protected $controllerName;
    /**
     * @var string Название экшена
     */
    protected $actionName;
    /**
     * @var array параметры Get-запроса
     */
    protected $params;

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->requestString = $_SERVER['REQUEST_URI'];
        $this->parseRequest();
    }

    /**
     * Метод получает название контроллера, название экшена и параметры из URL
     */
    private function parseRequest() {
        $url =  explode('/', $this->requestString);
        $this->controllerName = $url[1];
        $this->actionName = $url[2];
        $this->params = $_REQUEST;
    }

    /**
     * @return string
     */
    public function getControllerName()
    {
        return $this->controllerName;
    }

    /**
     * @return string
     */
    public function getActionName()
    {
        return $this->actionName;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }
}