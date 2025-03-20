-- $ psql -U <user> -d <database> -f seeder.sql
-- example: $ psql -U postgres -d bookstore -f seeder.sql
-- Seeding the users table
SET client_encoding TO 'UTF8'; -- If the data was inserted when the client encoding was incorrect, the text might have been stored incorrectly.

INSERT INTO users (
		first_name,
		last_name,
		email,
		password
	)
VALUES (
		'Александр',
		'Александров',
		'admin@example.com',
		-- hashed word 'password'
		'$2y$13$GeXsv/KQR7cFm/qNpdRraOCGAcSSBoOhsl56qS3IGw/zPcPbisENW'
	),
	(
		'Борис',
		'Борисов',
		'boris@example.com',
		-- hashed word 'password' (bcrypt)
		'$2y$13$GeXsv/KQR7cFm/qNpdRraOCGAcSSBoOhsl56qS3IGw/zPcPbisENW'
	),
	(
		'Василий',
		'Васильев',
		'vasiliy@example.com',
		-- hashed word 'password'
		'$2y$13$GeXsv/KQR7cFm/qNpdRraOCGAcSSBoOhsl56qS3IGw/zPcPbisENW'
	),
	(
		'Григорий',
		'Григорьев',
		'grigoriy@example.com',
		-- hashed word 'password'
		'$2y$13$GeXsv/KQR7cFm/qNpdRraOCGAcSSBoOhsl56qS3IGw/zPcPbisENW'
	);

INSERT INTO books (
    title, author, publisher, image_path, sample_page_images, publication_year, price, quantity_in_stock,
    description, binding_type, publication_type, isbn, edition, circulation, language, pages, weight, size
)
VALUES
    ('Мёртвые души', 'Николай Гоголь', 'Эксмо', '/images/books/1.webp',
     ARRAY['/images/books/page_1.jpg', '/images/books/page_2.jpg', '/images/books/page_3.jpg', '/images/books/page_4.jpg', '/images/books/page_5.jpg'], 1842, 720.30, 9,
     'Сатирическая поэма о российском чиновничестве.', 'hardcover', 'physical', '978-5-04-001234-5',
     '1-е издание', 10000, 'Русский', 384, 500.25, '20x30 cm'),

    ('Мастер и Маргарита', 'Михаил Булгаков', 'Эксмо', '/images/books/10.webp',
     ARRAY['/images/books/page_1.jpg', '/images/books/page_2.jpg', '/images/books/page_3.jpg', '/images/books/page_4.jpg', '/images/books/page_5.jpg'], 1967, 990.99, 5,
     'Мистический роман о визите дьявола в Москву.', 'hardcover', 'physical', '978-5-04-009876-4',
     '1-е издание', 15000, 'Русский', 448, 650.50, '22x30 cm'),

    ('Евгений Онегин', 'Александр Пушкин', 'Эксмо', '/images/books/3.webp',
     ARRAY['/images/books/page_1.jpg', '/images/books/page_2.jpg', '/images/books/page_3.jpg', '/images/books/page_4.jpg', '/images/books/page_5.jpg'], 1833, 480.99, 20,
     'Роман в стихах о судьбе светского молодого человека.', 'hardcover', 'physical', '978-5-04-003456-7',
     '2-е издание', 20000, 'Русский', 320, 300.30, '19x28 cm'),

    ('Отцы и дети', 'Иван Тургенев', 'АСТ', '/images/books/4.webp',
     ARRAY['/images/books/page_1.jpg', '/images/books/page_2.jpg', '/images/books/page_3.jpg', '/images/books/page_4.jpg', '/images/books/page_5.jpg'], 1862, 650.75, 8,
     'Роман о столкновении поколений и нигилизме.', 'hardcover', 'physical', '978-5-04-004567-8',
     '1-е издание', 12000, 'Русский', 352, 400.40, '21x30 cm'),

    ('Преступление и наказание', 'Фёдор Достоевский', 'Азбука', '/images/books/5.webp',
     ARRAY['/images/books/page_1.jpg', '/images/books/page_2.jpg', '/images/books/page_3.jpg', '/images/books/page_4.jpg', '/images/books/page_5.jpg'], 1866, 799.99, 15,
     'Роман о внутренней борьбе студента Раскольникова после убийства.', 'hardcover', 'physical',
     '978-5-04-005678-9', '1-е издание', 25000, 'Русский', 512, 600.60, '23x33 cm'),

    ('Война и мир', 'Лев Толстой', 'Эксмо', '/images/books/6.webp',
     ARRAY['/images/books/page_1.jpg', '/images/books/page_2.jpg', '/images/books/page_3.jpg', '/images/books/page_4.jpg', '/images/books/page_5.jpg'], 1869, 1499.50, 10,
     'Эпопея о жизни русской аристократии в эпоху Наполеоновских войн.', 'hardcover', 'physical',
     '978-5-04-006789-0', '1-е издание', 50000, 'Русский', 1200, 1500.75, '25x35 cm'),

    ('Анна Каренина', 'Лев Толстой', 'Азбука', '/images/books/7.webp',
     ARRAY['/images/books/page_1.jpg', '/images/books/page_2.jpg', '/images/books/page_3.jpg', '/images/books/page_4.jpg', '/images/books/page_5.jpg'], 1877, 899.00, 12,
     'Трагическая история любви Анны Карениной и Вронского.', 'hardcover', 'physical',
     '978-5-04-007890-1', '1-е издание', 18000, 'Русский', 864, 750.80, '20x30 cm'),

    ('Человек в футляре', 'Антон Чехов', 'АСТ', '/images/books/8.webp',
     ARRAY['/images/books/page_1.jpg', '/images/books/page_2.jpg', '/images/books/page_3.jpg', '/images/books/page_4.jpg', '/images/books/page_5.jpg'], 1898, 350.00, 18,
     'Повесть о замкнутом и боязливом человеке Беликове.', 'paperback', 'physical',
     '978-5-04-008901-2', '1-е издание', 8000, 'Русский', 192, 250.10, '18x28 cm'),

    ('Доктор Живаго', 'Борис Пастернак', 'Азбука', '/images/books/9.webp',
     ARRAY['/images/books/page_1.jpg', '/images/books/page_2.jpg', '/images/books/page_3.jpg', '/images/books/page_4.jpg', '/images/books/page_5.jpg'], 1957, 1100.75, 7,
     'Историко-романтическая драма на фоне революции.', 'hardcover', 'physical',
     '978-5-04-009012-3', '1-е издание', 10000, 'Русский', 672, 800.40, '22x32 cm'),

    ('Герой нашего времени', 'Михаил Лермонтов', 'Азбука', '/images/books/2.webp',
     ARRAY['/images/books/page_1.jpg', '/images/books/page_2.jpg', '/images/books/page_3.jpg', '/images/books/page_4.jpg', '/images/books/page_5.jpg'], 1840, 560.20, 14,
     'Психологический портрет офицера Печорина.', 'hardcover', 'physical',
     '978-5-04-003123-4', '1-е издание', 12000, 'Русский', 320, 400.25, '21x30 cm');

INSERT INTO categories (name, description) VALUES
	('Фантастика', 'Книги о вымышленных мирах и технологиях будущего'),
	('Детектив', 'Истории о расследованиях преступлений'),
	('Фэнтези', 'Мир магии, волшебства и невероятных приключений'),
	('История', 'Книги о прошлом, исторических событиях и личностях'),
	('Биография', 'Жизнеописания известных людей'),
	('Наука', 'Книги о научных открытиях и исследованиях'),
	('Философия', 'Размышления о жизни, бытии и сознании'),
	('Психология', 'Книги о человеческом мышлении и поведении'),
	('Классика', 'Произведения, проверенные временем'),
	('Поэзия', 'Сборники стихотворений и поэм');

INSERT INTO books_categories (book_id, category_id) VALUES
    (1, 9),  -- "Мёртвые души" -> Классика
    (1, 2),  -- "Мёртвые души" -> Детектив
    (2, 9), -- "Мастер и Маргарита" -> Классика
    (2, 3), -- "Мастер и Маргарита" -> Фэнтези
    (2, 7), -- "Мастер и Маргарита" -> Философия
    (3, 9),  -- "Евгений Онегин" -> Классика
    (3, 10), -- "Евгений Онегин" -> Поэзия
    (4, 9),  -- "Отцы и дети" -> Классика
    (4, 7),  -- "Отцы и дети" -> Философия
    (5, 9),  -- "Преступление и наказание" -> Классика
    (5, 2),  -- "Преступление и наказание" -> Детектив
    (6, 9),  -- "Война и мир" -> Классика
    (6, 4),  -- "Война и мир" -> История
    (7, 9),  -- "Анна Каренина" -> Классика
    (8, 9),  -- "Человек в футляре" -> Классика
    (8, 7),  -- "Человек в футляре" -> Философия
    (9, 9),  -- "Доктор Живаго" -> Классика
    (9, 4),  -- "Доктор Живаго" -> История
    (9, 2),  -- "Доктор Живаго" -> Детектив
    (10, 9),  -- "Герой нашего времени" -> Классика
    (10, 7),  -- "Герой нашего времени" -> Философия
    (10, 4);  -- "Герой нашего времени" -> История

INSERT INTO users_favorite_books (user_id, book_id)
VALUES
    (2, 1), (2, 3), (2, 5), (2, 7),
    (3, 2), (3, 4), (3, 6), (3, 8), (3, 10),
    (4, 1), (4, 5), (4, 6);

INSERT INTO orders (order_status, order_total, user_id, delivery_detail_id)
VALUES
    ('pending', 2499.75, 3, 1),
    ('completed', 1499.50, 4, 2),
    ('pending', 799.99, 3, 3),
    ('cancelled', 899.00, 4, 4),
    ('completed', 2799.00, 3, 5);

INSERT INTO orders_books (quantity, price, order_id, book_id)
VALUES
    (2, 799.99, 1, 1),  -- 2 copies of book with ID 1 for order with ID 1
    (1, 1499.50, 2, 2), -- 1 copy of book with ID 2 for order with ID 2
    (3, 799.99, 3, 3),  -- 3 copies of book with ID 3 for order with ID 3
    (1, 650.75, 4, 4),  -- 1 copy of book with ID 4 for order with ID 4
    (2, 799.99, 5, 5),  -- 2 copies of book with ID 5 for order with ID 5
    (1, 899.00, 1, 6),  -- 1 copy of book with ID 6 for order with ID 1
    (1, 560.20, 2, 7),  -- 1 copy of book with ID 7 for order with ID 2
    (1, 720.30, 3, 8),  -- 1 copy of book with ID 8 for order with ID 3
    (2, 480.99, 4, 9),  -- 2 copies of book with ID 9 for order with ID 4
    (1, 1100.75, 5, 10); -- 1 copy of book with ID 10 for order with ID 5

INSERT INTO reviews (rating, review_comment, book_id, user_id)
VALUES
    (5, 'Отличная книга! Очень понравилась сюжетная линия.', 1, 1),
    (4, 'Хорошая книга, но местами немного затянуто.', 1, 2),
    (3, 'Книга не оправдала ожиданий, но есть интересные моменты.', 1, 3),
    (5, 'Прекрасная история, не мог оторваться.', 2, 1),
    (4, 'Очень интересный сюжет, но концовка немного разочаровала.', 2, 2),
    (5, 'Потрясающая книга, с удовольствием прочитал.', 2, 3),
    (3, 'Не очень понравилось, но книга читаема.', 3, 1),
    (4, 'Довольно неплохо, интересные персонажи.', 3, 2),
    (5, 'Прекрасная книга, люблю такого рода литературу!', 3, 4),
    (4, 'Хорошая книга, но хотелось бы больше развития сюжета.', 4, 1),
    (5, 'Мне очень понравилось, книга захватывает с первых страниц!', 4, 3),
    (3, 'Не могу сказать, что книга мне понравилась, но она не плохая.', 4, 4),
    (4, 'Очень интересное чтиво, но местами затянутые сцены.', 5, 2),
    (5, 'Безумно понравилась, читается на одном дыхании.', 5, 4),
    (3, 'Не самая лучшая книга, но есть несколько интересных моментов.', 5, 1),
    (5, 'Шедевр! Однозначно рекомендую!', 6, 2),
    (4, 'Хорошая книга, но в некоторых местах она могла быть короче.', 6, 3),
    (5, 'Великолепная история, все очень динамично и захватывающе.', 6, 4),
    (4, 'Очень интересно, но местами затянуты описания.', 7, 1),
    (5, 'Читать было захватывающе, с удовольствием бы прочитал снова.', 7, 2),
    (3, 'Книга неплохая, но немного скучная для меня.', 7, 4),
    (4, 'Неплохо, но хотелось бы большего раскрытия персонажей.', 8, 1),
    (5, 'Сюжет великолепен, книга держит в напряжении до конца.', 8, 3),
    (5, 'Очень сильная книга, вызывает много эмоций.', 8, 4),
    (4, 'Хорошая книга, но немного не хватило динамики.', 9, 2),
    (5, 'Безумно увлекательная история, хочется больше таких книг!', 9, 3),
    (3, 'Интересная книга, но не моя тема.', 9, 1),
    (5, 'Прекрасная книга, просто восхищение от сюжета!', 10, 2),
    (4, 'Очень хорошая книга, но мне не хватило некоторой логики в сюжете.', 10, 4),
    (5, 'Книга потрясающая, впечатлила!', 10, 3);

-- Seeding the payments table (only for orders made by users with id 3 and 4)
INSERT INTO payments (amount, transaction_id, payment_method, payment_status, order_id)
VALUES
    (2499.75, 'txn_1001', 'card', 'success', 1),
    (1499.50, 'txn_1002', 'card', 'success', 2),
    (799.99, 'txn_1003', 'card', 'success', 3),
    (899.00, 'txn_1004', 'card', 'failed', 4),
    (899.00, 'txn_1005', 'card', 'success', 4),
    (2799.00, 'txn_1006', 'card', 'success', 5);

-- Seeding the delivery_details table (IDs 1 to 5)
INSERT INTO delivery_details (user_id, address_line1, address_line2, city, state, postal_code, country, phone, user_comment)
VALUES
    (3, '123 Main St', 'Apt 1', 'CityA', 'StateA', '12345', 'CountryA', '1234567890', 'Leave at door'),
    (4, '456 Elm St', 'Suite 200', 'CityB', 'StateB', '23456', 'CountryB', '2345678901', 'Ring bell'),
    (3, '789 Oak St', NULL, 'CityC', 'StateC', '34567', 'CountryC', '3456789012', ''),
    (4, '321 Pine St', 'Floor 3', 'CityD', 'StateD', '45678', 'CountryD', '4567890123', ''),
    (3, '654 Cedar St', NULL, 'CityE', 'StateE', '56789', 'CountryE', '5678901234', 'Call on arrival');

-- Seeding the deliveries table for orders (skipping order with cancelled status if desired)
INSERT INTO deliveries (order_id, courier, tracking_number, delivery_status, shipped_at, expected_delivery, delivered_at)
VALUES
    (1, 'UPS', '1Z999AA10123456784', 'pending', NULL, CURRENT_TIMESTAMP + INTERVAL '5 days', NULL),
    (2, 'FedEx', 'FDX123456789', 'delivered', CURRENT_TIMESTAMP - INTERVAL '3 days', CURRENT_TIMESTAMP - INTERVAL '1 day', CURRENT_TIMESTAMP - INTERVAL '1 day'),
    (3, 'DHL', 'DHL987654321', 'pending', NULL, CURRENT_TIMESTAMP + INTERVAL '4 days', NULL),
    (5, 'USPS', '9400110897700000000000', 'delivered', CURRENT_TIMESTAMP - INTERVAL '4 days', CURRENT_TIMESTAMP - INTERVAL '2 days', CURRENT_TIMESTAMP - INTERVAL '2 days');

-- Seeding the card_payments table for payments made via card
-- Assumes payment IDs are sequential starting at 1 in order of insertion.
INSERT INTO card_payments (payment_id, card_type, card_last_four, card_expiry_month, card_expiry_year, cardholder_name)
VALUES
    (1, 'Visa', '4242', 12, 2026, 'Александр Александров'),
    (2, 'MasterCard', '5555', 11, 2025, 'Борис Борисов'),
    (3, 'Visa', '4242', 10, 2024, 'Василий Васильев'),
    (5, 'MasterCard', '5555', 9, 2023, 'Григорий Григорьев'),
    (6, 'Visa', '4242', 8, 2027, 'Василий Васильев');

-- Seeding the order_cancellations table (for the cancelled order)
INSERT INTO order_cancellations (order_id, cancellation_reason, refunded_amount)
VALUES
    (4, 'Customer requested cancellation before shipment', 899.00);

-- Seeding the order_returns table with sample return records
INSERT INTO order_returns (order_id, book_id, return_quantity, return_reason, return_status)
VALUES
    (1, 1, 1, 'Damaged copy' 'pending'),
    (3, 3, 2, 'Incorrect item delivered', 'pending');,
