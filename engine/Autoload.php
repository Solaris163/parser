<?php


/**
 * Class Autoload
 */
class Autoload
{
    /**
     * Метод преобразовывает название класса в название файла и подключает этот файл
     * @param string $className
     */
    public function loadClass($className) {

        $fileName = str_replace(["app\\", "\\"], [DIR_ROOT . "/../", DS], $className) . '.php';

        if (file_exists($fileName)) {
            include $fileName;
        }else echo "файл не существует" . '<br>';
    }
}