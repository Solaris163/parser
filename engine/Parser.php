<?php


namespace app\engine;


/**
 * Класс получает html код страницы с помощью метода parse()
 * Class Parser
 * @package app\engine
 */
class Parser
{
    /**
     * @var
     */
    private $ch;

    /**
     * Метод получает URL страницы и отдает html код
     * @param string $url Адрес страницы
     * @return bool|string
     */
    public function parse($url){
        $this->ch = curl_init($url);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($this->ch);
        curl_close($this->ch);
        return $result;
    }
}