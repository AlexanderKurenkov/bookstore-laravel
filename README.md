# Книжный магазин на Laravel

## Функционал

### Каталог книг
<p align="center">
<img src="images/main_page.png" alt="Главная страница" height="600">
<img src="images/book.png" alt="Пример страницы" height="600">
</p>

### Поиск и фильтры в каталоге
<table align="center">
<tr>
<td><img src="images/search_results.jpg" alt="Результаты поиска" width="400"></td>
<td><img src="images/search_empty.jpg" alt="Отсутствие результатов поиска" width="400"></td>
</tr>
</table>

### Функционал корзины

<p align="center">
<img src="images/cart.jpg" alt="Корзина" width="600">
</p>

### Оформление заказа
<table align="center">
<tr>
<td><img src="images/checkout.png" alt="Оформление заказа" width="300"></td>
<td><img src="images/checkout_invoice.png" alt="Подтверждение оформления заказа" width="300"></td>
</tr>
</table>

### Отмена заказа и возврат товара

<table align="center">
<tr>
<td><img src="images/order_cancellation.jpg" alt="Отмена заказа" width="300"></td>
<td><img src="images/order_cancellation_confirmation.jpg" alt="Отмена заказа" width="300"></td>
<td><img src="images/return.jpg" alt="Возврат товара" width="400"></td>
<td><img src="images/return_confirmed.jpg" alt="Подтверждение возврата" width="400"></td>
</tr>
</table>

### Личный кабинет пользователя

<table align="center">
  <tr>
    <td><img src="images/profile_data.jpg" alt="Данные пользователя" width="250"></td>
    <td><img src="images/profile_orders.jpg" alt="Список заказов" width="250"></td>
    <td><img src="images/profile_security.jpg" alt="Редактирование профиля" width="250"></td>
    <td><img src="images/profile_wishlist.jpg" alt="Список избранного" width="250"></td>
  </tr>
</table>

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
