<?php


namespace app\engine;


/**
 * Класс рендерит страницу
 * Class Render
 * @package app\engine
 */
class Render
{
    /**
     * @var string Нижний шаблон странцы, куда будет добавляться содержимое
     */
    public $layout = 'layout.php';

    /**
     * Метод рендерит шаблон
     * @param string $template Шаблон страницы
     * @param array $params Параметры с содержимым страницы
     * @return string
     */
    public function renderTemplate($template, $params = []) {
        ob_start();
        extract($params);

        $template = '../views/' . $template;
        include $template;
        return ob_get_clean();
    }

    /**
     * Метод дважды рендерит шаблон (рендерит нижний шаблон layout и передает ему в качестве пареметров отрендеренный
     * второй шаблон)
     * @param string $template Шаблон страницы
     * @param array $params Параметры с содержимым страницы
     * @return string
     */
    public function renderPage($template, $params = []) {
        return $this->renderTemplate(
            $this->layout,
            ['content' => $this->renderTemplate($template, $params)]
        );
    }
}