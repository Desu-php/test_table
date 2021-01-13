<?php


namespace core;


class View

{
    public static function display($view, $vars = [])
    {
        extract($vars);
        $path = dirname(__DIR__).'/resource/views/' . $view . '.php';
        if (!file_exists($path)) exit('Вид не найден');
        ob_start();
        require $path;
        echo ob_get_clean();
    }
}





















