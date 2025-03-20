----------------------------------------------------------
-- Usage
----------------------------------------------------------
-- 1) drop `bookstore` database:
-- 	$ psql -U <user> -d postgres -c "DROP DATABASE IF EXISTS bookstore;"
-- 	NOTE: cannot execute the DROP DATABASE command while connected to the database you want to drop
-- 2) create `bookstore` database:
-- 	$ psql -U <user> -d postgres -c "CREATE DATABASE bookstore WITH ENCODING 'UTF8';"
-- 		??? $ psql -U <user> -d postgres -c "CREATE DATABASE bookstore WITH ENCODING 'UTF8' LC_COLLATE 'ru_RU.UTF-8' LC_CTYPE 'ru_RU.UTF-8' TEMPLATE template_utf8;"
-- 3) cd into a folder with a schema.sql file
-- 4) execute schema.sql
-- 	$ psql -U <user> -d bookstore -f schema.sql
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
-- Create the users table
DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id BIGSERIAL PRIMARY KEY,
	first_name VARCHAR(255),
	last_name VARCHAR(255),
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email_verified_at TIMESTAMP,
    remember_token VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create the book table
DROP TABLE IF EXISTS books;
CREATE TABLE books (
	id BIGSERIAL PRIMARY KEY,
	title VARCHAR(255) NOT NULL,
	author VARCHAR(255) NOT NULL,
	publisher VARCHAR(255) NOT NULL,
	image_path VARCHAR(255),
    sample_page_images TEXT[],     			-- массив URL-адресов изображений c примерами страниц
	publication_year SMALLINT NOT NULL,
	price DECIMAL(19, 2) NOT NULL,
	quantity_in_stock SMALLINT,				-- NULL для цифровых и аудиокниг
	description TEXT,
	binding_type VARCHAR(50),       		-- тип переплета: твердый переплет, мягкая обложка (hardcover, paperback); NULL для цифровых и аудиокниг
    publication_type VARCHAR(50) NOT NULL,	-- тип издания: печатное, цифровое, аудиокнига (physical, ebook, audiobook)
    isbn VARCHAR(20) NOT NULL,				-- International Standard Book Number
    edition VARCHAR(50),            		-- издание (например, "2-е издание")
	circulation INT, 						-- тираж
    language VARCHAR(50) NOT NULL,			-- язык (например, русский, английский)
    pages SMALLINT,                 		-- общее число страниц
	weight DECIMAL(10, 2),					-- вес книги
	size VARCHAR(20),
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create the users_favorite_books table to track favorite books for each user
DROP TABLE IF EXISTS users_favorite_books;
CREATE TABLE users_favorite_books (
    user_id BIGINT NOT NULL,
    book_id BIGINT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, book_id) -- NOTE: also prevents user from 'liking' the same book multiple times
);

-- Categories Table
DROP TABLE IF EXISTS categories;
CREATE TABLE categories (
	id SERIAL PRIMARY KEY,
	name VARCHAR(255) UNIQUE NOT NULL,
	description TEXT,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
-- modeling a many-to-many relationship between books and categories
DROP TABLE IF EXISTS books_categories;
CREATE TABLE books_categories (
	book_id BIGINT NOT NULL,
	category_id BIGINT NOT NULL,
	PRIMARY KEY (book_id, category_id)
);

-- Create the orders table
DROP TABLE IF EXISTS orders;
CREATE TABLE orders (
	id BIGSERIAL PRIMARY KEY,
	order_status VARCHAR(20) DEFAULT 'pending' NOT NULL,
	order_total DECIMAL(19, 2) NOT NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	user_id BIGINT NOT NULL,
	delivery_detail_id BIGINT NOT NULL
);
-- Create the orders_books table
-- modeling a many-to-many relationship between orders and books
DROP TABLE IF EXISTS orders_books;
CREATE TABLE orders_books (
	quantity SMALLINT NOT NULL,
	price DECIMAL(19, 2) NOT NULL, -- price of the book at the time of purchase, which might differ from the current price
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	order_id BIGINT NOT NULL,
	book_id BIGINT NOT NULL,
	PRIMARY KEY (order_id, book_id)
);
-- Reviews table
DROP TABLE IF EXISTS reviews;
CREATE TABLE reviews (
	id SERIAL PRIMARY KEY,
	-- 1 to 5 stars
	rating SMALLINT CHECK (rating >= 1 AND rating <= 5) NOT NULL,
	review_comment TEXT,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	book_id BIGINT NOT NULL,
	user_id BIGINT NOT NULL
);
-- Create the payments table
-- Order can have multiple payments (for example, couple of failed payment attempts before successful payment)
DROP TABLE IF EXISTS payments;
CREATE TABLE payments (
	id BIGSERIAL PRIMARY KEY,
	amount DECIMAL(19, 2) NOT NULL,
	transaction_id VARCHAR(255) NOT NULL,
	payment_method VARCHAR(20) DEFAULT 'card' NOT NULL, 	-- card, cash
	payment_status VARCHAR(20) DEFAULT 'pending' NOT NULL, 	-- success, pending, failed
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	order_id BIGINT NOT NULL
);

DROP TABLE IF EXISTS card_payments;
CREATE TABLE card_payments (
    id BIGSERIAL PRIMARY KEY,
    payment_id BIGINT NOT NULL,
    card_type VARCHAR(50) NOT NULL,        -- тип платежной системы, например: МИР, Visa, MasterCard, Золотая Корона
    card_last_four CHAR(4) NOT NULL,       -- Last four digits of the card number
    card_expiry_month SMALLINT,            -- Expiration month (1-12)
    card_expiry_year SMALLINT,             -- Expiration year (YYYY)
    cardholder_name VARCHAR(255),          -- Optional: Name as printed on the card
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

DROP TABLE IF EXISTS delivery_details;
CREATE TABLE delivery_details (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL,
    address_line1 VARCHAR(255) NOT NULL,
    address_line2 VARCHAR(255),
    city VARCHAR(100) NOT NULL,
    state VARCHAR(100) NOT NULL,
    postal_code VARCHAR(20) NOT NULL,
    country VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
	user_comment VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

DROP TABLE IF EXISTS deliveries;
CREATE TABLE deliveries (
    id BIGSERIAL PRIMARY KEY,
    order_id BIGINT NOT NULL,
    courier VARCHAR(255),              -- Name of the courier (e.g., UPS, FedEx)
    tracking_number VARCHAR(255),      -- Tracking number provided by the courier
    delivery_status VARCHAR(20) DEFAULT 'pending' NOT NULL,  -- e.g., pending, shipped, in_transit, delivered, returned
    shipped_at TIMESTAMP,              -- When the order was shipped
    expected_delivery TIMESTAMP,       -- Estimated delivery date/time
    delivered_at TIMESTAMP,            -- Actual delivery date/time
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

DROP TABLE IF EXISTS order_cancellations;
CREATE TABLE order_cancellations (
    id BIGSERIAL PRIMARY KEY,
    order_id BIGINT NOT NULL,
    cancellation_reason TEXT,
    cancelled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    refunded_amount DECIMAL(19, 2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

DROP TABLE IF EXISTS order_returns;
CREATE TABLE order_returns (
    id BIGSERIAL PRIMARY KEY,
    order_id BIGINT NOT NULL,
    book_id BIGINT NOT NULL,
    return_quantity SMALLINT NOT NULL,
    return_reason TEXT,
    return_status VARCHAR(20) DEFAULT 'pending' NOT NULL,  -- e.g., pending, approved, rejected, processed
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
----------------------------------------------------------
-- Foreign Key Constraints
----------------------------------------------------------
ALTER TABLE reviews
	ADD CONSTRAINT FK_reviews_books FOREIGN KEY (book_id) REFERENCES books(id)
		ON UPDATE CASCADE
		ON DELETE CASCADE;
--
ALTER TABLE reviews
	ADD CONSTRAINT FK_reviews_users FOREIGN KEY (user_id) REFERENCES users(id)
		ON UPDATE CASCADE
		ON DELETE CASCADE;
-- prevent users from reviewing the same book multiple times
ALTER TABLE reviews
	ADD CONSTRAINT UNIQUE_user_book_review UNIQUE (user_id, book_id);
--
ALTER TABLE orders
	ADD CONSTRAINT FK_orders_users FOREIGN KEY (user_id) REFERENCES users(id)
		ON UPDATE CASCADE
		ON DELETE CASCADE;
--
ALTER TABLE orders
    ADD CONSTRAINT FK_orders_delivery_details FOREIGN KEY (delivery_detail_id) REFERENCES delivery_details(id)
		ON UPDATE CASCADE
		ON DELETE CASCADE;
--
ALTER TABLE payments
	ADD CONSTRAINT FK_payments_orders FOREIGN KEY (order_id) REFERENCES orders(id)
		ON UPDATE CASCADE
		ON DELETE CASCADE;
--
ALTER TABLE card_payments
    ADD CONSTRAINT FK_card_payments_payments FOREIGN KEY (payment_id) REFERENCES payments(id)
		ON UPDATE CASCADE
		ON DELETE CASCADE;
--
ALTER TABLE books_categories
	ADD CONSTRAINT FK_books_categories_books FOREIGN KEY (book_id) REFERENCES books(id)
		ON UPDATE CASCADE
		ON DELETE CASCADE;
--
ALTER TABLE books_categories
	ADD CONSTRAINT FK_books_categories_categories FOREIGN KEY (category_id) REFERENCES categories(id)
		ON UPDATE CASCADE
		ON DELETE CASCADE;
--
-- Foreign key constraint linking to the users table
ALTER TABLE users_favorite_books
    ADD CONSTRAINT FK_users_favorite_books_users FOREIGN KEY (user_id) REFERENCES users(id)
		ON UPDATE CASCADE
		ON DELETE CASCADE;
-- Foreign key constraint linking to the books table
ALTER TABLE users_favorite_books
    ADD CONSTRAINT FK_users_favorite_books_books FOREIGN KEY (book_id) REFERENCES books(id)
		ON UPDATE CASCADE
		ON DELETE CASCADE;
--
ALTER TABLE orders_books
	ADD CONSTRAINT FK_orders_books_books FOREIGN KEY (book_id) REFERENCES books(id)
		ON UPDATE CASCADE
		ON DELETE CASCADE;
--
ALTER TABLE orders_books
	ADD CONSTRAINT FK_orders_books_orders FOREIGN KEY (order_id) REFERENCES orders(id)
		ON UPDATE CASCADE
		ON DELETE CASCADE;

ALTER TABLE delivery_details
    ADD CONSTRAINT FK_delivery_details_users FOREIGN KEY (user_id) REFERENCES users(id)
		ON UPDATE CASCADE
		ON DELETE CASCADE;

ALTER TABLE order_cancellations
    ADD CONSTRAINT FK_order_cancellations_orders FOREIGN KEY (order_id) REFERENCES orders(id)
		ON UPDATE CASCADE
		ON DELETE CASCADE;

ALTER TABLE order_returns
    ADD CONSTRAINT FK_order_returns_orders FOREIGN KEY (order_id) REFERENCES orders(id)
		ON UPDATE CASCADE
		ON DELETE CASCADE;

ALTER TABLE order_returns
    ADD CONSTRAINT FK_order_returns_books FOREIGN KEY (book_id) REFERENCES books(id)
		ON UPDATE CASCADE
		ON DELETE CASCADE;
----------------------------------------------------------
-- Triggers
----------------------------------------------------------
-- Update the `updated_at` column to the current timestamp
CREATE OR REPLACE FUNCTION set_updated_at() RETURNS TRIGGER AS $$ BEGIN --
	NEW.updated_at = CURRENT_TIMESTAMP;
RETURN NEW;
END;
$$ LANGUAGE plpgsql;
-- Add the trigger to the `users` table
CREATE TRIGGER update_timestamp_in_users BEFORE
	UPDATE ON users FOR EACH ROW EXECUTE FUNCTION set_updated_at();
-- Add the trigger to the `books` table
CREATE TRIGGER update_timestamp_in_books BEFORE
	UPDATE ON books FOR EACH ROW EXECUTE FUNCTION set_updated_at();
-- Add the trigger to the `categories` table
CREATE TRIGGER update_timestamp_in_categories BEFORE
	UPDATE ON categories FOR EACH ROW EXECUTE FUNCTION set_updated_at();
-- Add the trigger to the `reviews` table
CREATE TRIGGER update_timestamp_in_reviews BEFORE
	UPDATE ON reviews FOR EACH ROW EXECUTE FUNCTION set_updated_at();
-- Add the trigger to the `orders` table
CREATE TRIGGER update_timestamp_in_orders BEFORE
	UPDATE ON orders FOR EACH ROW EXECUTE FUNCTION set_updated_at();
-- Add the trigger to the `orders_books` table
CREATE TRIGGER update_timestamp_in_orders_books BEFORE
	UPDATE ON orders_books FOR EACH ROW EXECUTE FUNCTION set_updated_at();
-- Add the trigger to the `payments` table
CREATE TRIGGER update_timestamp_in_payments BEFORE
	UPDATE ON payments FOR EACH ROW EXECUTE FUNCTION set_updated_at();
-- Add the trigger to the `card_payments` table
CREATE TRIGGER update_timestamp_in_card_payments BEFORE
	UPDATE ON card_payments FOR EACH ROW EXECUTE FUNCTION set_updated_at();
-- Add the trigger to the `delivery_details` table
CREATE TRIGGER update_timestamp_in_delivery_details BEFORE
	UPDATE ON delivery_details FOR EACH ROW EXECUTE FUNCTION set_updated_at();
-- Add the trigger to the `deliveries` table
CREATE TRIGGER update_timestamp_in_deliveries BEFORE
	UPDATE ON deliveries FOR EACH ROW EXECUTE FUNCTION set_updated_at();
-- Add the trigger to the `order_cancellations` table
CREATE TRIGGER update_timestamp_in_order_cancellations BEFORE
	UPDATE ON order_cancellations FOR EACH ROW EXECUTE FUNCTION set_updated_at();
-- Add the trigger to the `order_returns` table
CREATE TRIGGER update_timestamp_in_order_returns BEFORE
	UPDATE ON order_returns FOR EACH ROW EXECUTE FUNCTION set_updated_at();

----------------------------------------------------------
-- Indices on foreign keys
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

-- Adding indexes on commonly searched fields can improve performance.
CREATE INDEX idx_books_title ON books(title);
CREATE INDEX idx_books_author ON books(author);