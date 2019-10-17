<?php


namespace app\models;


use app\engine\Db;

/**
 * Класс реализует методы для работы с категориями матчей
 * Class Category
 * @package app\models
 */
class Category
{
    /**
     * @var string Название таблицы в базе данных
     */
    protected $tableName = 'categories';
    /**
     * @var string Название поля для хранения имени категории (турнира)
     */
    protected $columnName = 'name';
    /**
     * @var string Название поля для хранения даты парсинга
     */
    protected $columnDate = 'date';

    /**
     * @var int id категории
     */
    public $id;
    /**
     * @var string Название категории (турнира)
     */
    public $name;
    /**
     * @var string Дата и время создания категории (дата и время парсинга страницы)
     */
    public $date;

    /**
     * Метод сохраняет в базу данных название категории и дату парсинга
     * Метод возвращает true, если сохранение прошло успешно
     * @return bool
     */
    public function save(){
        if (is_string($this->name)){
            $sql = "INSERT INTO {$this->tableName} ({$this->columnName}, {$this->columnDate})
                VALUES (:name, :date)";
            return Db::getInstance()->query($sql, [':name' => $this->name, ':date' => $this->date]);
        }
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
    public function getColumnDate()
    {
        return $this->columnDate;
    }

    /**
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }
}