<?php


namespace app\models;


use app\engine\Db;

/**
 * Класс отвечает за получение списка категорий (турниров) и матчей
 * Класс содержит методы для обработки спарсенных страниц и методы для работы с моделями категории и матча
 */
class MatchList
{
    /**
     * @var string Дата
     */
    public $date;
    /**
     * @var array Массив с матчами для добавления в базу данных
     */
    public $matches = []; //массив с матчами
    /**
     * @var string Html-код полученный со страницы с матчем
     */
    public $html;
    /**
     * @var Category Модель категории (турнира) для внесения категорий в базу
     */
    public $categoryModel;
    /**
     * @var Match Модель матча для внесения матчей в базу
     */
    public $matchModel;

    /**
     * MatchList constructor.
     */
    public function __construct()
    {
        $this->date = date('Y-m-d G:i:s');
        $this->categoryModel = new Category();
        $this->matchModel = new Match();
        require_once '../phpQuery/phpQuery/phpQuery.php';
    }

    /**
     * Метод заносит в базу данных категории (турниры) матчей и матчи в каждой категории
     * Метод возвращает true, если на странице не обнаружено матчей или категорий
     * @param string $html Html - код страницы
     * @return bool
     */
    public function getMatchList($html){
        $doc = \phpQuery::newDocument($html);
        $categories = $doc->find('.category-container'); //набор всех категорий с матчами на странице
        $isItLastPage = true; //переменная для проверки, нужно ли парсить следующую страницу
        foreach ($categories as $category) { //переберем набор категорий и найдем матчи в каждой категории
            $pqCategory = pq($category);
            $categoryName = $pqCategory->find('.category-label-link')->text(); //название категории
            $categoryName = trim($categoryName, " \n"); //обрежем пробелы и символы ввода
            //Передадим $pqCategory в метод getMatches(), который добавит матчи в модель $this->matchModel
            $isItLastPage = $this->getMatches($pqCategory); //метод getMatches() вернет true, если матчей нет
            if ($isItLastPage === false){ //если матчи обнаружены, сохраним категорию базу данных
                $isSave = $this->saveCategory($categoryName);
                if ($isSave){ //если категория сохранена, то сохраним мачти этой категории в базу данных
                    $this->matchModel->save($this->categoryModel->id);
                }
            }
        }
        return $isItLastPage; //вернем true, если на странице не обнаружены категории матчей
    }

    /**
     * Метод сохраняет категорию (турнир) в базу данных
     * Метод возвращает true, если сохранение прошло успешно
     * @param string $categoryName Имя категории
     * @return bool
     */
    public function saveCategory($categoryName){
        $this->categoryModel->setName($categoryName);
        $this->categoryModel->setDate($this->date);
        $result = $this->categoryModel->save(); //сохраним категорию в базу данных
        $this->categoryModel->id = Db::getInstance()->getLastID();
        return $result;
    }

    /**
     * Метод ищет матчи внутри категории
     * Метод возвращает true, если на в категории не найдено матчей
     * @param object $category Объект PHPQuery, содержащий категорию с матчами
     * @return bool
     */
    public function getMatches($category){
        $matches = $category->find('.coupon-row'); //найдем набор матчей одной категории
        if($matches->length == 0) return true; //вернем true, что означает, что это последняя страница
        foreach ($matches as $match) {
            $match = pq($match);
            $matchName = $match->attr('data-event-name'); //имя матча
            //$this->matchModel->name = $matchName;
            $this->matchModel->setName($matchName); //запишем в модель название матча
            $matchLink = SITE_URL . $match->find('.member-link')->attr('href'); //ссылка на страницу матча
            $this->matchModel->setLink($matchLink);
            $this->matchModel->addMatch(); //добавим матч в массив для последующей записи в базу данных.
        }
        return false; //вернем false, что означает, что это не последняя страница
    }

    /**
     * Метод получает из базы и возвращает массив с id и ссылками матчей, спасренных за текущий запуск приложения
     * @return array
     */
    public function getMatchesArrFromDb(){
        $matchesTable = $this->matchModel->getTableName(); //название таблицы с матчами
        $categoriesTable = $this->categoryModel->getTableName(); //название таблицы с категориями
        $columnLink = $this->matchModel->getColumnLink(); //название поля со ссылками на страницы матчей
        $columnCategoryId = $this->matchModel->getColumnCategoryId(); //название поля с id категории
        $columnDate = $this->categoryModel->getColumnDate(); //название поля с датой парсинга

        $sql = "SELECT {$matchesTable}.id, {$columnLink} FROM {$matchesTable}
        INNER JOIN {$categoriesTable} ON {$matchesTable}.{$columnCategoryId} = {$categoriesTable}.id
        WHERE {$columnDate} = '{$this->date}'";
        return Db::getInstance()->queryAll($sql);
    }

    /**
     * Метод находит в коде спарсенной страницы матча таблицу с данными матча и возвращает ее в виде html - кода
     * @param string $html
     * @return string
     */
    public function getMatchContent($html){
        $doc = \phpQuery::newDocument($html);
        return $doc->find('.category-container')->html();
    }

    /**
     * Метод находит в базе данных матчи, спарсенные за последний запуск приложения
     * Метод возвращает массив, каждый элемент которого содержит id матча, его название и название категории (турнира)
     * @return array
     */
    public function getAllMatches(){
        $matchesTable = $this->matchModel->getTableName(); //название таблицы с матчами
        $categoriesTable = $this->categoryModel->getTableName(); //название таблицы с категориями
        $columnDate = $this->categoryModel->getColumnDate(); //название поля с датой парсинга
        $columnMatchName = $this->matchModel->getColumnName(); //
        $columnCategoryName = $this->categoryModel->getColumnName(); //
        $columnCategoryId = $this->matchModel->getColumnCategoryId(); //

        $sql = "SELECT {$matchesTable}.id AS matchId,
        {$matchesTable}.{$columnMatchName} AS matchName,
        {$categoriesTable}.{$columnCategoryName} AS categoryName
        FROM {$matchesTable}
        INNER JOIN {$categoriesTable} ON {$matchesTable}.{$columnCategoryId} = {$categoriesTable}.id
        WHERE {$columnDate} = (SELECT MAX({$columnDate}) FROM {$categoriesTable})";
        return Db::getInstance()->queryAll($sql);
    }
}