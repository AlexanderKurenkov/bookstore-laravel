----------------------------------------------------------
-- Usage
----------------------------------------------------------
-- $ chcp 65001
-- 1) drop `bookstore` database:
-- 	$ psql -U <user> -d postgres -c "DROP DATABASE IF EXISTS bookstore;"
-- 	NOTE: cannot execute the DROP DATABASE command while connected to the database you want to drop
-- 2) create `bookstore` database:
-- 	$ psql -U <user> -d postgres -c "CREATE DATABASE bookstore WITH ENCODING 'UTF8';"
-- 		??? $ psql -U <user> -d postgres -c "CREATE DATABASE bookstore WITH ENCODING 'UTF8' LC_COLLATE 'ru_RU.UTF-8' LC_CTYPE 'ru_RU.UTF-8' TEMPLATE template_utf8;"
-- 3) cd into a folder with a schema.sql file
-- 4) execute schema.sql
-- 	$ psql -U <user> -d bookstore --encoding=UTF8 -f schema.sql
-- 		$ psql -U postgres -d bookstore -f schema.sql
----------------------------------------------------------
-- Tables
----------------------------------------------------------
-- users
-- books
-- users_favorite_books -- join table
-- categories
-- books_categories -- join table
-- orders
-- orders_books -- join table
-- reviews
-- payments
-- card_payments
-- delivery_details
-- deliveries
-- order_cancellations
-- order_returns
----------------------------------------------------------
-- SET client_encoding TO 'UTF8'; -- If the data was inserted when the client encoding was incorrect, the text might have been stored incorrectly.

-- Таблица для пользователей.
DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id BIGSERIAL PRIMARY KEY,
    first_name VARCHAR(20),
    last_name VARCHAR(30),
    email VARCHAR(30) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email_verified_at TIMESTAMP,
    phone VARCHAR(20),
    date_of_birth DATE,
    gender VARCHAR(10)
        CHECK (gender IN ('male', 'female') OR gender IS NULL);
    remember_token VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Таблица для книг.
DROP TABLE IF EXISTS books;
CREATE TABLE books (
    id BIGSERIAL PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    author VARCHAR(100) NOT NULL,
    publisher VARCHAR(80) NOT NULL,
    image_path VARCHAR(255),
    -- массив URL-адресов изображений c примерами страниц
    sample_page_images TEXT[],
    publication_year SMALLINT NOT NULL,
    price DECIMAL(19, 2) NOT NULL,
    -- NULL для цифровых и аудиокниг
    quantity_in_stock SMALLINT,
    description TEXT,
    -- тип переплета: твердый переплет, мягкая обложка (hardcover, paperback)
    -- NULL для цифровых и аудиокниг
    binding_type VARCHAR(50),
    -- тип издания: печатное, цифровое, аудиокнига (physical, ebook, audiobook)
    publication_type VARCHAR(50) NOT NULL,
    -- ISBN (International Standard Book Number)
    isbn VARCHAR(20) NOT NULL,
    -- издание (например, "2-е издание")
    edition VARCHAR(50),
    -- тираж
    circulation INT,
    -- язык (например, русский, английский)
    language VARCHAR(50) NOT NULL,
    -- общее число страниц
    pages SMALLINT,
    -- вес книги
    weight DECIMAL(10, 2),
    size VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Промежуточная таблица для реализации
-- связи M:N между отношениями "users" и "books".
DROP TABLE IF EXISTS users_favorite_books;
CREATE TABLE users_favorite_books (
    user_id BIGINT NOT NULL,
    book_id BIGINT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    -- ПРИМЕЧАНИЕ: нет необходимости в ограничении уникальности,
    -- чтобы предотвратить добавление одной и той же книги в избранное несколько раз,
    -- так как это уже гарантирукет первичный ключ.
    PRIMARY KEY (user_id, book_id)
);

-- Таблица для категорий книг.
DROP TABLE IF EXISTS categories;
CREATE TABLE categories (
    id SERIAL PRIMARY KEY,
    name VARCHAR(20) UNIQUE NOT NULL,
    url_slug VARCHAR(20) UNIQUE NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Промежуточная таблица для реализации
-- связи M:N между отношениями "books" и "categories".
DROP TABLE IF EXISTS books_categories;
CREATE TABLE books_categories (
    book_id BIGINT NOT NULL,
    category_id BIGINT NOT NULL,
    PRIMARY KEY (book_id, category_id)
);

-- Таблица для заказов.
DROP TABLE IF EXISTS orders;
CREATE TABLE orders (
    id BIGSERIAL PRIMARY KEY,
    -- Возможные значения:
    --      оформляется (pending),
    --      комплектуется (processing),
    --      отправлен (shipped),
    --      доставлен (delivered),
    --      отменен (cancelled),
    --      возвращен (returned).
    order_status VARCHAR(20) DEFAULT 'pending' NOT NULL,
    order_total DECIMAL(19, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id BIGINT NOT NULL,
    delivery_detail_id BIGINT NOT NULL
);

-- Промежуточная таблица для реализации
-- связи M:N между отношениями "orders" и "books".
DROP TABLE IF EXISTS orders_books;
CREATE TABLE orders_books (
    quantity SMALLINT NOT NULL,
    price DECIMAL(19, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    order_id BIGINT NOT NULL,
    book_id BIGINT NOT NULL,
    PRIMARY KEY (order_id, book_id)
);

-- Таблица для отзывов пользователей.
DROP TABLE IF EXISTS reviews;
CREATE TABLE reviews (
    id SERIAL PRIMARY KEY,
    -- от 1 до 5 звезд
    rating SMALLINT
        CHECK (rating >= 1 AND rating <= 5) NOT NULL,
    review_comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    book_id BIGINT NOT NULL,
    user_id BIGINT NOT NULL
);

-- Таблица для оплаты.
-- ПРИМЕЧАНИЕ: Заказ может содержать несколько платежей, например,
-- несколько неудачных попыток оплаты перед успешной оплатой.
DROP TABLE IF EXISTS payments;
CREATE TABLE payments (
    id BIGSERIAL PRIMARY KEY,
    amount DECIMAL(19, 2) NOT NULL,
    transaction_id VARCHAR(255) NOT NULL,
    -- card, cash
    payment_method VARCHAR(20) DEFAULT 'card' NOT NULL,
    -- success, pending, failed
    payment_status VARCHAR(20) DEFAULT 'pending' NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    order_id BIGINT NOT NULL
);

-- Таблица для данных по оплате банковской картой.
DROP TABLE IF EXISTS card_payments;
CREATE TABLE card_payments (
    id BIGSERIAL PRIMARY KEY,
    card_type VARCHAR(20) NOT NULL,
    -- Последние четыре цифры номера карты
    card_last_four CHAR(4) NOT NULL,
    -- Месяц истечения срока действия (1-12)
    card_expiry_month SMALLINT,
    -- Год истечения срока действия (ГГГГ)
    card_expiry_year SMALLINT,
    -- Имя, указанное на карте
    cardholder_name VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    payment_id BIGINT NOT NULL
);

-- Таблица с дополнительной информацией о доставке.
DROP TABLE IF EXISTS delivery_details;
CREATE TABLE delivery_details (
    id BIGSERIAL PRIMARY KEY,
    address_line1 VARCHAR(100) NOT NULL,
    address_line2 VARCHAR(100),
    city VARCHAR(20) NOT NULL,
    state VARCHAR(50) NOT NULL,
    postal_code VARCHAR(20) NOT NULL,
    country VARCHAR(50) NOT NULL,
    phone VARCHAR(20),
    user_comment VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id BIGINT NOT NULL,
);

-- Таблица для информации о доставке.
DROP TABLE IF EXISTS deliveries;
CREATE TABLE deliveries (
    id BIGSERIAL PRIMARY KEY,
    -- Название службы доставки.
    courier VARCHAR(255),
    -- Номер для отслеживания, предоставленный курьером.
    tracking_number VARCHAR(50),
    -- Статус доставки: pending, shipped, in_transit, delivered, returned.
    delivery_status VARCHAR(20)
        DEFAULT 'pending' NOT NULL,
    -- Время отправки товара.
    shipped_at TIMESTAMP,
    -- Ожидаемое время доставки.
    expected_delivery TIMESTAMP,
    -- Фактическое время доставки.
    delivered_at TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    order_id BIGINT NOT NULL,
);

-- Таблица с информацией об отмене заказа.
DROP TABLE IF EXISTS order_cancellations;
CREATE TABLE order_cancellations (
    id BIGSERIAL PRIMARY KEY,
    cancellation_reason TEXT,
    cancelled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    refunded_amount DECIMAL(19, 2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    order_id BIGINT NOT NULL
);

-- Таблица с информацией о возврате заказа.
DROP TABLE IF EXISTS order_returns;
CREATE TABLE order_returns (
    id BIGSERIAL PRIMARY KEY,
    order_id BIGINT NOT NULL,
    book_id BIGINT NOT NULL,
    return_quantity SMALLINT NOT NULL,
    return_reason TEXT,
    -- pending, approved, rejected
    return_status VARCHAR(20) DEFAULT 'pending' NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
----------------------------------------------------------
-- Ограничения внешнего ключа
----------------------------------------------------------
ALTER TABLE reviews
    ADD CONSTRAINT FK_reviews_books
        FOREIGN KEY (book_id) REFERENCES books(id)
            ON UPDATE CASCADE
            ON DELETE CASCADE;

ALTER TABLE reviews
    ADD CONSTRAINT FK_reviews_users
        FOREIGN KEY (user_id) REFERENCES users(id)
            ON UPDATE CASCADE
            ON DELETE CASCADE;

-- Ограничение уникальности позволяет избежать случая, когда
-- пользователь может оставить отзыв для одной и той же книги несколько раз.
ALTER TABLE reviews
    ADD CONSTRAINT UNIQUE_user_book_review
        UNIQUE (user_id, book_id);

ALTER TABLE orders
    ADD CONSTRAINT FK_orders_users
        FOREIGN KEY (user_id) REFERENCES users(id)
            ON UPDATE CASCADE
            ON DELETE CASCADE;

ALTER TABLE orders
    ADD CONSTRAINT FK_orders_delivery_details
        FOREIGN KEY (delivery_detail_id) REFERENCES delivery_details(id)
            ON UPDATE CASCADE
            ON DELETE CASCADE;

ALTER TABLE payments
    ADD CONSTRAINT FK_payments_orders
        FOREIGN KEY (order_id) REFERENCES orders(id)
            ON UPDATE CASCADE
            ON DELETE CASCADE;

ALTER TABLE card_payments
    ADD CONSTRAINT FK_card_payments_payments
        FOREIGN KEY (payment_id) REFERENCES payments(id)
            ON UPDATE CASCADE
            ON DELETE CASCADE;

ALTER TABLE books_categories
    ADD CONSTRAINT FK_books_categories_books
        FOREIGN KEY (book_id) REFERENCES books(id)
            ON UPDATE CASCADE
            ON DELETE CASCADE;

ALTER TABLE books_categories
    ADD CONSTRAINT FK_books_categories_categories
        FOREIGN KEY (category_id) REFERENCES categories(id)
            ON UPDATE CASCADE
            ON DELETE CASCADE;

ALTER TABLE users_favorite_books
    ADD CONSTRAINT FK_users_favorite_books_users
        FOREIGN KEY (user_id) REFERENCES users(id)
            ON UPDATE CASCADE
            ON DELETE CASCADE;

ALTER TABLE users_favorite_books
    ADD CONSTRAINT FK_users_favorite_books_books
        FOREIGN KEY (book_id) REFERENCES books(id)
            ON UPDATE CASCADE
            ON DELETE CASCADE;
--
ALTER TABLE orders_books
    ADD CONSTRAINT FK_orders_books_books
        FOREIGN KEY (book_id) REFERENCES books(id)
            ON UPDATE CASCADE
            ON DELETE CASCADE;

ALTER TABLE orders_books
    ADD CONSTRAINT FK_orders_books_orders
        FOREIGN KEY (order_id) REFERENCES orders(id)
            ON UPDATE CASCADE
            ON DELETE CASCADE;

ALTER TABLE delivery_details
    ADD CONSTRAINT FK_delivery_details_users
        FOREIGN KEY (user_id) REFERENCES users(id)
            ON UPDATE CASCADE
            ON DELETE CASCADE;

ALTER TABLE order_cancellations
    ADD CONSTRAINT FK_order_cancellations_orders
        FOREIGN KEY (order_id) REFERENCES orders(id)
            ON UPDATE CASCADE
            ON DELETE CASCADE;

ALTER TABLE order_returns
    ADD CONSTRAINT FK_order_returns_orders
        FOREIGN KEY (order_id) REFERENCES orders(id)
            ON UPDATE CASCADE
            ON DELETE CASCADE;

ALTER TABLE order_returns
    ADD CONSTRAINT FK_order_returns_books
        FOREIGN KEY (book_id) REFERENCES books(id)
            ON UPDATE CASCADE
            ON DELETE CASCADE;
------------------------------------------------------------------------
-- Триггеры для автоматической установки значения столбца `updated_at`.
------------------------------------------------------------------------
-- Обновить столбец `updated_at`, используя текущую временную метку.
CREATE OR REPLACE FUNCTION set_updated_at() RETURNS TRIGGER AS $$ BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
RETURN NEW;
END;
$$ LANGUAGE plpgsql;
CREATE TRIGGER update_timestamp_in_users BEFORE
    UPDATE ON users FOR EACH ROW EXECUTE FUNCTION set_updated_at();
CREATE TRIGGER update_timestamp_in_books BEFORE
    UPDATE ON books FOR EACH ROW EXECUTE FUNCTION set_updated_at();
CREATE TRIGGER update_timestamp_in_categories BEFORE
    UPDATE ON categories FOR EACH ROW EXECUTE FUNCTION set_updated_at();
CREATE TRIGGER update_timestamp_in_reviews BEFORE
    UPDATE ON reviews FOR EACH ROW EXECUTE FUNCTION set_updated_at();
CREATE TRIGGER update_timestamp_in_orders BEFORE
    UPDATE ON orders FOR EACH ROW EXECUTE FUNCTION set_updated_at();
CREATE TRIGGER update_timestamp_in_orders_books BEFORE
    UPDATE ON orders_books FOR EACH ROW EXECUTE FUNCTION set_updated_at();
CREATE TRIGGER update_timestamp_in_payments BEFORE
    UPDATE ON payments FOR EACH ROW EXECUTE FUNCTION set_updated_at();
CREATE TRIGGER update_timestamp_in_card_payments BEFORE
    UPDATE ON card_payments FOR EACH ROW EXECUTE FUNCTION set_updated_at();
CREATE TRIGGER update_timestamp_in_delivery_details BEFORE
    UPDATE ON delivery_details FOR EACH ROW EXECUTE FUNCTION set_updated_at();
CREATE TRIGGER update_timestamp_in_deliveries BEFORE
    UPDATE ON deliveries FOR EACH ROW EXECUTE FUNCTION set_updated_at();
CREATE TRIGGER update_timestamp_in_order_cancellations BEFORE
    UPDATE ON order_cancellations FOR EACH ROW EXECUTE FUNCTION set_updated_at();
CREATE TRIGGER update_timestamp_in_order_returns BEFORE
    UPDATE ON order_returns FOR EACH ROW EXECUTE FUNCTION set_updated_at();

----------------------------------------------------------
-- Индексы для внешних ключей
----------------------------------------------------------
-- `reviews`
CREATE INDEX idx_reviews_book_id ON reviews(book_id);
CREATE INDEX idx_reviews_user_id ON reviews(user_id);

-- `orders`
CREATE INDEX idx_orders_user_id ON orders(user_id);

-- `payments`
CREATE INDEX idx_payments_order_id ON payments(order_id);

-- `books_categories` (composite key)
CREATE INDEX idx_books_categories_book_id ON books_categories(book_id);
CREATE INDEX idx_books_categories_category_id ON books_categories(category_id);

-- `orders_books` (composite key)
CREATE INDEX idx_orders_books_order_id ON orders_books(order_id);
CREATE INDEX idx_orders_books_book_id ON orders_books(book_id);

-- card_payments (payment_id)
CREATE INDEX idx_card_payments_payment_id ON card_payments(payment_id);

-- delivery_details (user_id)
CREATE INDEX idx_delivery_details_user_id ON delivery_details(user_id);

-- deliveries (order_id)
CREATE INDEX idx_deliveries_order_id ON deliveries(order_id);

-- order_cancellations (order_id)
CREATE INDEX idx_order_cancellations_order_id ON order_cancellations(order_id);

-- order_returns (order_id, book_id)
CREATE INDEX idx_order_returns_order_id ON order_returns(order_id);
CREATE INDEX idx_order_returns_book_id ON order_returns(book_id);

-- Добавление индексов к часто используемым полям
-- для повышения производительности.
CREATE INDEX idx_books_title ON books(title);
CREATE INDEX idx_books_author ON books(author);