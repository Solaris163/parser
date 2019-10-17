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
     */
    public function __construct()
    {
        $this->render = new Render(); //создадим экземпляр класса Render для рендеринга страниц
        $this->matchListModel = new MatchList(); //создадим экземпляр класса MatchList
    }

    /**
     * Метод показывает главную страницу со списком матчей
     */
    public function actionIndex(){
        //Подготовим массив $content с категориями (турнирами) и матчами внутри категорий
        //Получим массив с элементами вида: ['Чемпионат Европы' => ['Милан - ЦСКА', 'Спартак - Манчестер']]
        $content = [];
        $matches = $this->matchListModel->getAllMatches(); //массив матчей, спарсенных за последний запуск приложения
        foreach ($matches as $match){ //переберем массив с матчами заполним массив $content
            $content[$match['categoryName']]["{$match['matchId']}"] = $match['matchName'];
        }
        echo $this->render->renderPage('index.php', ['content' => $content]); //отобразим страницу
    }

    /**
     * Метод показывает страницу с содержимым матча
     * @param array $params Параметры get-запроса из url-адреса страницы
     */
    public function actionMatch($params){
        $matchId = $params['id']; //найдем id матча из get-запроса
        $content = $this->matchListModel->matchModel->getMatchContentFromDb($matchId); //найдем содержимое матча
        echo $this->render->renderPage('match.php', ['content' => $content]); //отобразим страницу
    }

}