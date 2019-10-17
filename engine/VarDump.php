<?php


namespace app\engine;


/**
 * Class VarDump
 * Класс для вывода объектов в читабельном виде
 * @package app\engine
 */
class VarDump
{
    /**
     * Метод выводит $var и заканчивает работу приложения
     * @param $var
     */
    public static function varDump($var)
    {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
        exit;
    }

    /**
     * Метод выводит $var и не заканчиват работу приложения, может вызываться неоднократно
     * @param $var
     */
    public static function varDumpMulti($var)
    {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
    }

    /**
     * Метод для вывода массивов
     * @param $var
     */
    public static function printR($var)
    {
        echo '<pre>';
        print_r($var);
        echo '</pre>';
        exit;
    }
}