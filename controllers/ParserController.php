<?php


namespace app\controllers;

use app\engine\Parser;
use app\models\MatchList;

/**
 * Class ParserController
 * @package app\controllers
 * Класс получает страницы со списком матчей и передает код страниц в модель для обработки
 */
class ParserController extends Controller
{
    /**
     * @var Parser
     */
    public $parser;
    /**
     * @var MatchList
     */
    public $matchList;

    /**
     * ParserController constructor.
     */
    public function __construct()
    {
        $this->parser = new Parser(); //создадим объект класса Parser для получения html кода страницы
        $this->matchList = new MatchList(); //создадим объект класса MatchList для получения списка матчей из кода html
    }

    /**
     * Экшен запускает пасинг сайта
     */
    public function actionRun(){
        ini_set('max_execution_time', 3000);
        $this->getMatchesList(); //получим список матчей
        $this->getMatches(); //получим контент каждого матча
    }

    /**
     * Метод выбирает URL страницы для парсинга и передает этот URL в метод parseCategoryPage()
     */
    public function getMatchesList(){
        $url = LIST_URL; //найдем адрес первой страницы
        //Передадим адрес в метод parsePage(), который вернет false если на странице не будет матчей
        $isItLastPage = $this->parseCategoryPage($url, true);
        $i = 0;
        while ($isItLastPage === false){ //находим адреса следующих страниц и передаем их в метод parseNextPage
            $url = LIST_URL . '&' . GET_REQUEST_NEXT_PAGE_START . (NEXT_PAGE + $i) . '&' . GET_REQUEST_NEXT_PAGE_END;
            $isItLastPage = $this->parseCategoryPage($url);
            $i++;
        }
    }

    /**
     * Метод парсит страницу со списком категорий (турниров) и матчей
     * Метод получает код страницы и передает его методу getMatchList() объекта класса MatchList
     * Метод возвращает false, если на странице будут найдены матчи, и true, если не будут найдены
     * @param string $url
     * @param bool $isItFirstPage
     * @return bool
     */
    public function parseCategoryPage($url, $isItFirstPage = false){
        $result = $this->parser->parse($url);
        if ($isItFirstPage === false){ //проверяем, какая страница парсится, если не первая, то убираем экранирование
            $result = str_ireplace('\\"', '"', $result); //убираем экранирование из ответа сервера
        }
        //Передадим результат в метод getMatchList(), который вернет false если на странице не будет матчей
        $isItLastPage = $this->matchList->getMatchList($result);
        return $isItLastPage;
    }

    /**
     * Метод получает из базы массив матчей и последовательно передает id и ссылки матчей в метод parseMatchPage()
     */
    public function getMatches(){
        $matchesArr = $this->matchList->getMatchesArrFromDb();
        foreach ($matchesArr as $match){
            $this->parseMatchPage($match['link'], $match['id']);
            usleep(300); //задержка, чтобы не заблокировали
        }

    }

    /**
     * Метод получает html - код со страницы матча, передает его методу getMatchContent() класса MatchList()
     * получает обратно html - код с контентом матча и передает его методу saveHtml() класса Match
     * @param string $url Адрес страницы с матчем
     * @param int $matchId Id матча
     */
    public function parseMatchPage($url, $matchId){
        $result = $this->parser->parse($url); //парсим страницу матча
        $html = $this->matchList->getMatchContent($result); //выделяем из html - кода контент матча
        $this->matchList->matchModel->saveHtml($html, $matchId); //сохраняем контент в базу данных
    }
}