----------------------------------------------------------
-- Usage
----------------------------------------------------------
-- 1) drop `bookstore` database:
-- 	$ psql -U <user> -d postgres -c "DROP DATABASE IF EXISTS bookstore;"
-- 	NOTE: cannot execute the DROP DATABASE command while connected to the database you want to drop
-- 2) create `bookstore` database:
-- 	$ psql -U <user> -d postgres -c "CREATE DATABASE bookstore WITH ENCODING 'UTF8' LC_COLLATE 'ru_RU.UTF-8' LC_CTYPE 'ru_RU.UTF-8' TEMPLATE template_utf8;"
-- 3) cd into a folder with a schema.sql file
-- 4) execute schema.sql
-- 	$ psql -U <user> -d bookstore -f schema.sql
-- 		$ psql -U postgres -d bookstore -f schema.sql
----------------------------------------------------------
-- Tables
----------------------------------------------------------
-- users
-- books
-- categories
-- books_categories -- join table
-- orders
-- orders_books -- join table
-- reviews
-- payments
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
	publication_year SMALLINT NOT NULL,
	price DECIMAL(19, 2) NOT NULL,
	quantity_in_stock SMALLINT NOT NULL,
	description TEXT,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
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
	order_status VARCHAR(20) DEFAULT 'pending' NOT NULL, -- completed, pending, cancelled
	order_total DECIMAL(19, 2) NOT NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	user_id BIGINT NOT NULL
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
	payment_status VARCHAR(20) DEFAULT 'pending' NOT NULL, -- success, pending, failed
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	order_id BIGINT NOT NULL
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
--
-- prevent users from reviewing the same book multiple times
ALTER TABLE reviews
	ADD CONSTRAINT UNIQUE_user_book_review UNIQUE (user_id, book_id);
--
ALTER TABLE orders
	ADD CONSTRAINT FK_orders_users FOREIGN KEY (user_id) REFERENCES users(id)
		ON UPDATE CASCADE
		ON DELETE CASCADE;
--
ALTER TABLE payments
	ADD CONSTRAINT FK_payments_orders FOREIGN KEY (order_id) REFERENCES orders(id)
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
ALTER TABLE orders_books
	ADD CONSTRAINT FK_orders_books_books FOREIGN KEY (book_id) REFERENCES books(id)
		ON UPDATE CASCADE
		ON DELETE CASCADE;
--
ALTER TABLE orders_books
	ADD CONSTRAINT FK_orders_books_orders FOREIGN KEY (order_id) REFERENCES orders(id)
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
----------------------------------------------------------
-- Indices
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