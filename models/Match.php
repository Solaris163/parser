<?php


namespace app\models;


use app\engine\Db;

/**
 * Class Match
 * Класс реализует методы для работы с матчами
 * @package app\models
 */
class Match
{
    /**
     * @var string Название таблицы в базе данных
     */
    protected $tableName = 'matches';

    /**
     * @var string Название поля для хранения названия матча
     */
    protected $columnName = 'name';
    /**
     * @var string Название поля для хранения ссылки на страницу матча
     */
    protected $columnLink = 'link';
    /**
     * @var string Название поля для хранения html кода с данными матча
     */
    protected $columnHtml = 'html';
    /**
     * @var int Название поля для хранения id категории матча (id турнира)
     */
    protected $columnCategoryId = 'categoryId';
    /**
     * @var array Массив с матчами для пакетного добавления в базу данных
     */
    protected $arrMatches = [];

    /**
     * @var int Id матча
     */
    public $id;
    /**
     * @var string Название матча
     */
    public $name;
    /**
     * @var string html код с данными матча
     */
    public $html;
    /**
     * @var string ссылка на страницу матча
     */
    public $link;
    /**
     * @var string id категории матча (id турнира)
     */
    public $categoryId;

    /**
     * Метод добавляет один матч в массив $arrMatches
     */
    public function addMatch(){
        $this->arrMatches[] = ['name' => $this->name, 'link' => $this->link];
    }

    /**
     * Метод сохраняет в базу данных матчи из массива $arrMatches
     * @var string $categoryId Id категории матча (id турнира)
     */
    public function save($categoryId){
        $values = $this->getValues($categoryId); //получим данные для построения sql - запроса
        $params = $this->getParams(); //получим параметры для передачи методу PDO query() класса Db
        $sql = "INSERT INTO {$this->tableName} ({$this->columnName}, {$this->columnLink}, {$this->columnCategoryId})
            VALUES " . $values;
        Db::getInstance()->query($sql, $params);
        $this->arrMatches = []; //очистим массив с матчами
    }

    /**
     * Метод подготавливает параметры для передачи их в метод query класса Db вместе с запросом sql (для биндинга)
     * Метод возвращает массив вида [":name1" => 'ЦСКА - Спатак', ":name2" => 'Зенит - Спатак']
     * @return array
     */
    public function getParams(){
        $params = [];
        $i = 1;
        foreach ($this->arrMatches as $match){
            $params[":name{$i}"] = $match['name'];
            $params[":link{$i}"] = $match['link'];
            $i++;
        }
        return $params;
    }

    /**
     * Метод подготавливает строку с псевдопараметрами для вставки в sql - запрос
     * Метод возвращает строку вида '(:name1, :link1, 155),(:name2, :link2, 155)'
     * @var string $categoryId Id категории матча (id турнира)
     * @return array
     */
    public function getValues($categoryId){
        $values = '';
        $i = 1;
        foreach ($this->arrMatches as $match){
            $values .= " (:name{$i}, :link{$i}, {$categoryId}),";
            $i++;
        }
        $values = substr($values, 0, -1); //обрежем запятую в конце строки.
        return $values;
    }

    /**
     * Метод добавляет контент матча в строку матча в базе данных
     * @param string $html Html - код с контентом матча
     * @param int $matchId Id матча
     */
    public function saveHtml($html, $matchId){
        $sql = "UPDATE {$this->tableName} SET {$this->columnHtml} = :html WHERE id = {$matchId}";
        Db::getInstance()->query($sql, [":html" => $html]);
    }

    /**
     * Метод получает из базы данных контент матча
     * @param int $matchId Id матча
     * @return string Html - код с контентом матча
     */
    public function getMatchContentFromDb($matchId){
        $sql = "SELECT {$this->columnHtml} FROM {$this->tableName} WHERE id = :id";
        return Db::getInstance()->queryAll($sql, [":id" => $matchId])[0]["$this->columnHtml"];
    }

    /**
     * @return string
     */
    public function getColumnName()
    {
        return $this->columnName;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param string $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * @return string
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * @return string
     */
    public function getColumnLink()
    {
        return $this->columnLink;
    }

    /**
     * @return string
     */
    public function getColumnCategoryId()
    {
        return $this->columnCategoryId;
    }
}