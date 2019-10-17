<?php


namespace app\engine;


/**
 * Класс реализует подключение к базе данных
 * Class Db
 * @package app\engine
 */
class Db
{
    /**
     * @var array
     */
    private $config = [
        'driver' => 'mysql',
        'host' => 'localhost',
        'login' => 'root',
        'password' => '',
        'database' => 'parser',
        'charset' => 'utf8',
    ];

    /**
     * @var Db
     */
    private static $instance = null;

    /**
     * @var \PDO
     */
    private $connection = null;

    /**
     * Метод реализует паттерн singleton
     * Метод возвращает объект класса Db, а если объект не существует, то создает его.
     * @return Db
     */
    public static function getInstance() {
        if(is_null(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * Метод создает подключение к базе данных
     * @return \PDO
     */
    private function getConnection() {
        if (is_null($this->connection)) {
            $this->connection = new \PDO($this->getDSN(),
                $this->config['login'],
                $this->config['password']
                );
            $this->connection->setAttribute(
                \PDO::ATTR_DEFAULT_FETCH_MODE,
                \PDO::FETCH_ASSOC);
        }
        return $this->connection;
    }

    /**
     * Метод подготавливает DSN для подключения к базе данных
     * @return string
     */
    private function getDSN() {
        return sprintf("%s:host=%s;dbname=%s;charset=%s",
            $this->config['driver'],
            $this->config['host'],
            $this->config['database'],
            $this->config['charset']
            );
    }

    /**
     * Метод подготавливает и выполняет запрос к базе данных без получения данных из базы
     * @param $sql
     * @param array $params
     * @return bool
     */
    public function query($sql, $params = []) {
        $pdoStatement = $this->getConnection()->prepare($sql);
        return $pdoStatement->execute($params);
    }

    /**
     * Метод подготавливает и выполняет запрос к базе данных с получением данных из базы
     * @param $sql
     * @param array $params
     * @return array
     */
    public function queryAll($sql, $params = []) {
        $pdoStatement = $this->getConnection()->prepare($sql);
        $pdoStatement->execute($params);
        return $pdoStatement->fetchAll();
    }

    /**
     * Метод возвращает id последней строки, записанной в базу данных
     * @return int
     */
    public function getLastId() {
        return $this->connection->lastInsertId();
    }
}