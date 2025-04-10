<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Отображает главную страницу.
     *
     * @return View Представление главной страницы
     */
    public function index(): View
    {
        return view('index');
    }

    /**
     * Отображает страницу "О нас".
     *
     * @return View Представление страницы "О нас"
     */
    public function about(): View
    {
        return view('about');
    }

    /**
     * Отображает страницу с часто задаваемыми вопросами.
     *
     * @return View Представление страницы FAQ
     */
    public function faq(): View
    {
        return view('faq');
    }

    /**
     * Отображает страницу с условиями использования.
     *
     * @return View Представление страницы условий
     */
    public function terms(): View
    {
        return view('terms');
    }

    /**
     * Отображает страницу политики конфиденциальности.
     *
     * @return View Представление страницы политики конфиденциальности
     */
    public function privacy(): View
    {
        return view('privacy');
    }
}