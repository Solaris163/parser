<?php


namespace app\controllers;


use app\engine\Render;
use app\models\MatchList;

/**
 * Класс отвечает за отображение страниц сайта
 * Class ShowController
 * @package app\controllers
 */
class ShowController extends Controller
{
    /**
     * @var Render
     */
    public $render;
    /**
     *
     * @var MatchList
     */
    public $matchListModel;

    /**
     * ShowController constructor.
     * @param $rander
     */
    public function __construct()
    {
        $this->render = new Render(); //создадим экземпляр класса Render для рендеринга страниц
        $this->matchListModel = new MatchList(); //создадим экземпляр класса MatchList
    }

    /**
     *
     */
    public function actionIndex(){
        $this->render->renderPage('index', []);
    }

    /**
     * Метод показывает страницу с содержимым матча
     * @param array $params Параметры get-запроса из url-адреса страницы
     */
    public function actionMatch($params){
        $matchId = $params['id']; //найдем id матча из get-запроса
        $html = $this->matchListModel->matchModel->getMatchContentFromDb($matchId); //найдем содержимое матча
        echo $this->render->renderPage('match.php', ['html' => $html]); //отобразим страницу
    }

}