<?php

/*
 *Промежуточный класс между классами,которые выводят страницы на сайт и классом Base_Controller
 * Класс описывает методы,которые будут работать на всех страницах (Вывод шапки,вывод новостей,вывод каталога и т.д.)
 * Эти методы будут описываться только в этом классе,так как будут работать на всем сайте и описывать их в каждом классе ни к чему
 */

abstract class Base extends Base_Controller
{
    protected $ob_m;  // здесь будет создаваться и храниться объект для работы с моделью сайта (БД)
    protected $title; //свойство для заголовка каждой страницы
    protected $style; // свойство для путей к файлам стилей,например template/default/style.css
    protected $script; // аналогично выше,только для скриптов
    protected $header;  //здесь будет храниться шаблон шапки сайта с переданными в него переменными
    protected $header_menu;
    protected $content; //средняя контентная часть сайта
    protected $left_bar;//шаблон левого блока
    protected $right_bar;//шаблон правого блока
    protected $footer;
    protected $right_side = TRUE;  //так как правая область есть не на каждой странице,то с помощью FALSE будем ее скрывать
    protected $news;  //массив новостей

    protected function input()  //берет данные на вход
    {
        $this->title = "Промстрой энерго | ";

        foreach ($this->styles as $style) {
            $this->style[] = SITE_URL . VIEW . $style;
        }

        foreach ($this->scripts as $script) {
            $this->script[] = SITE_URL . VIEW . $script;
        }

        $this->ob_m = Model::get_instance();    //создаем объект класса Model
        $this->news = $this->ob_m->get_news();

    }

    protected function output()  //выдает данные на выход
    {
        $this->left_bar = $this->render(VIEW . 'left_bar');
        if ($this->right_side) {
            $this->right_bar = $this->render(VIEW . 'right_bar',
                array('news'=>$this->news));
        }
        $this->footer = $this->render(VIEW . 'footer');
        $page = $this->render(VIEW . 'index',
            array(
                'header' => $this->header,
                'left_bar' => $this->left_bar,
                'content' => $this->content,
                'right_bar' => $this->right_bar,
                'footer' => $this->footer
            )
        );
        return $page;
    }


}