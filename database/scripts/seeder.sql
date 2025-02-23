-- mysql -u <username> -p < seeder.sql
use bookstore;
-- Seeder for roles
INSERT INTO roles (name)
VALUES ('Admin'),
	('User');
-- Seeder for users
INSERT INTO users (email, name, enabled, password)
VALUES (
		'admin@example.com',
		'Admin User',
		1,
		'password_hash'
	),
	(
		'user@example.com',
		'Regular User',
		1,
		'password_hash'
	);
-- Seeder for user_roles
INSERT INTO user_roles (user_id, role_id)
VALUES (1, 1),
	-- Admin role
	(2, 2);
-- User role
-- Seeder for books
INSERT INTO books (title, author, publisher, year, price)
VALUES (
		'Book One',
		'Author One',
		'Publisher One',
		2023,
		19.99
	),
	(
		'Book Two',
		'Author Two',
		'Publisher Two',
		2022,
		29.99
	);
-- Seeder for shopping_carts
INSERT INTO shopping_carts (user_id, session_id)
VALUES (2, 'session123');
-- Seeder for cart_items
INSERT INTO cart_items (qty, subtotal, book_id, shopping_cart_id)
VALUES (1, 19.99, 1, 1),
	(2, 59.98, 2, 1);
-- Seeder for book_to_cart_items
INSERT INTO book_to_cart_items (book_id, cart_item_id)
VALUES (1, 1),
	(2, 2);
-- Seeder for billing_addresses
INSERT INTO billing_addresses (
		billing_address_city,
		billing_address_country,
		billing_address_name,
		billing_address_state,
		billing_address_street1,
		billing_address_zipcode
	)
VALUES (
		'City One',
		'Country One',
		'John Doe',
		'State One',
		'Street 1',
		'12345'
	);
-- Seeder for shipping_addresses
INSERT INTO shipping_addresses (
		shipping_address_city,
		shipping_address_country,
		shipping_address_name,
		shipping_address_state,
		shipping_address_street1,
		shipping_address_zipcode
	)
VALUES (
		'City Two',
		'Country Two',
		'Jane Doe',
		'State Two',
		'Street 2',
		'54321'
	);
-- Seeder for payments
INSERT INTO payments (
		card_number,
		cvc,
		default_payment,
		expiry_month,
		expiry_year,
		holder_name,
		type
	)
VALUES (
		'1234567812345678',
		123,
		1,
		12,
		2030,
		'John Doe',
		'Visa'
	);
-- Seeder for user_orders
INSERT INTO user_orders (
		order_date,
		order_status,
		order_total,
		shipping_date,
		shipping_method,
		billing_address_id,
		payment_id,
		shipping_address_id,
		user_id
	)
VALUES (
		'2024-12-24 12:00:00',
		'Shipped',
		79.97,
		'2024-12-25 12:00:00',
		'Express',
		1,
		1,
		1,
		2
	);
-- Seeder for password_reset_tokens
INSERT INTO password_reset_tokens (email, token, created_at, user_id)
VALUES (
		'user@example.com',
		'token123',
		'2024-12-24 10:00:00',
		2
	);