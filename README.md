# Книжный магазин на Laravel

## Функционал

### Каталог книг
<p align="center">
<img src="images/main_page.png" alt="Главная страница" height="600" style="vertical-align: top; margin-right: 10px;">
<img src="images/book.png" alt="Пример страницы" height="600" style="vertical-align: top; margin-right: 10px;">
</p>

### Поиск и фильтры в каталоге
<p align="center">
<img src="images/search_results.jpg" alt="Результаты поиска" width="400" style="vertical-align: top; margin-right: 10px;">
<img src="images/search_empty.jpg" alt="Отсутствие результатов поиска" width="400" style="vertical-align: top; margin-right: 10px;">
</p>

### Функционал корзины

<p align="center">
<img src="images/cart.jpg" alt="Корзина" width="600">
</p>

### Оформление заказа
<p align="center">
<img src="images/checkout.png" alt="Оформление заказа" height="600" style="vertical-align: top; margin-right: 10px;">
<img src="images/checkout_invoice.png" alt="Подтверждение оформления заказа" height="600" style="vertical-align: top; margin-right: 10px;">
</p>

### Отмена заказа и возврат товара

<p align="center">
<img src="images/order_cancellation.jpg" alt="Отмена заказа" width="250" style="vertical-align: top; margin-right: 10px;">
<img src="images/order_cancellation_confirmation.jpg" alt="Отмена заказа" width="1500" style="vertical-align: top; margin-right: 10px;">
</p>
<p align="center">
<img src="images/return.jpg" alt="Возврат товара" width="400" style="vertical-align: top; margin-right: 10px;">
<img src="images/return_confirmed.jpg" alt="Подтверждение возврата" width="400" style="vertical-align: top; margin-right: 10px;">
</p>

### Личный кабинет пользователя

<p align="center">
<img src="images/profile_data.jpg" alt="Данные пользователя" width="300" style="vertical-align: top; margin-right: 10px;">
<img src="images/profile_orders.jpg" alt="Список заказов" width="300" style="vertical-align: top; margin-right: 10px;">
<img src="images/profile_security.jpg" alt="Редактирование профиля" width="300" style="vertical-align: top; margin-right: 10px;">
<img src="images/profile_wishlist.jpg" alt="Список избранного" width="300" style="vertical-align: top; margin-right: 10px;">
</p>

## Требования

* PHP 8.2+
* Laravel 11
* Postgres 15.7

## Установка

1. Клонировать репозиторий проекта
    ```bash
    $ https://github.com/AlexanderKurenkov/bookstore-laravel.git
    $ cd bookstore-laravel
    ```

2. Установить зависимости с помощью Composer
    ```bash
    $ composer install
    ```

3. Создать файл .env на основе .env.example и настроить подключение к базе данных
    ```bash
    $ cp .env.example .env

    # Отредактировать файл .env, указав нужные настройки
    $ nano .env
    ```

4. Создать базу данных, используя скрипты в папке `database/sql`
    ```bash
    # Перейти в каталог database/sql
    $ cd database/sql

    # Создание схемы базы данных
    $ psql -U <user> -d postgres -c "DROP DATABASE IF EXISTS <database>;"
    $ psql -U <user> -d postgres -c "CREATE DATABASE <database> WITH ENCODING 'UTF8';"
    $ psql -U <user> -d <database> --encoding=UTF8 -f schema.sql

    # Наполнение базы данных
    $ psql -U <user> -d <database> -f seeder.sql
    ```

5. Выполнить миграции (создание таблиц, используемых Laravel) и запустить приложение
    ```bash
    $ php artisan migrate
    $ php artisan serve --host=localhost --port=8080
    ```

6. Открыть веб-браузер и перейти на страницу приложения: http://127.0.0.1:8000
