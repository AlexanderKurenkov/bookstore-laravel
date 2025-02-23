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

INSERT INTO books (title, author, publisher, publication_year, price, quantity_in_stock, description)
VALUES
   	('Преступление и наказание', 'Фёдор Достоевский', 'Азбука', 1866, 799.99, 15, 'Роман о внутренней борьбе студента Раскольникова после убийства.'),
	('Война и мир', 'Лев Толстой', 'Эксмо', 1869, 1499.50, 10, 'Эпопея о жизни русской аристократии в эпоху Наполеоновских войн.'),
    ('Анна Каренина', 'Лев Толстой', 'Азбука', 1877, 899.00, 12, 'Трагическая история любви Анны Карениной и Вронского.'),
    ('Отцы и дети', 'Иван Тургенев', 'АСТ', 1862, 650.75, 8, 'Роман о столкновении поколений и нигилизме.'),
    ('Мёртвые души', 'Николай Гоголь', 'Эксмо', 1842, 720.30, 9, 'Сатирическая поэма о российском чиновничестве.'),
    ('Герой нашего времени', 'Михаил Лермонтов', 'Азбука', 1840, 560.20, 14, 'Психологический портрет офицера Печорина.'),
    ('Евгений Онегин', 'Александр Пушкин', 'Эксмо', 1833, 480.99, 20, 'Роман в стихах о судьбе светского молодого человека.'),
    ('Человек в футляре', 'Антон Чехов', 'АСТ', 1898, 350.00, 18, 'Повесть о замкнутом и боязливом человеке Беликове.'),
    ('Доктор Живаго', 'Борис Пастернак', 'Азбука', 1957, 1100.75, 7, 'Историко-романтическая драма на фоне революции.'),
    ('Мастер и Маргарита', 'Михаил Булгаков', 'Эксмо', 1967, 990.99, 5, 'Мистический роман о визите дьявола в Москву.');

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

INSERT INTO book_categories (book_id, category_id) VALUES
    (1, 9),  -- "Преступление и наказание" -> Классика
    (2, 9),  -- "Война и мир" -> Классика
    (2, 4),  -- "Война и мир" -> История
    (3, 9),  -- "Анна Каренина" -> Классика
    (4, 9),  -- "Отцы и дети" -> Классика
    (4, 7),  -- "Отцы и дети" -> Философия
    (5, 9),  -- "Мёртвые души" -> Классика
    (6, 9),  -- "Герой нашего времени" -> Классика
    (6, 7),  -- "Герой нашего времени" -> Философия
    (7, 9),  -- "Евгений Онегин" -> Классика
    (7, 10), -- "Евгений Онегин" -> Поэзия
    (8, 9),  -- "Человек в футляре" -> Классика
    (8, 7),  -- "Человек в футляре" -> Философия
    (9, 9),  -- "Доктор Живаго" -> Классика
    (9, 4),  -- "Доктор Живаго" -> История
    (10, 9), -- "Мастер и Маргарита" -> Классика
    (10, 3), -- "Мастер и Маргарита" -> Фэнтези
    (10, 7); -- "Мастер и Маргарита" -> Философия

INSERT INTO orders (order_status, order_total, user_id)
VALUES
    ('pending', 2499.75, 3),
    ('completed', 1499.50, 4),
    ('pending', 799.99, 3),
    ('cancelled', 899.00, 4),
    ('completed', 2799.00, 3);

INSERT INTO order_books (quantity, price, order_id, book_id)
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
    (5, 'Превосходная книга, обязательна к прочтению для всех любителей классики.', 1, 1),  -- Book ID 1, User ID 1
    (4, 'Очень интересная, но местами затянутая. Хороший роман.', 2, 2),  -- Book ID 2, User ID 2
    (3, 'История захватывающая, но концовка оставляет желать лучшего.', 3, 3),  -- Book ID 3, User ID 3
    (5, 'Одно из величайших произведений русской литературы, сильно впечатляет.', 4, 4),  -- Book ID 4, User ID 4
    (4, 'Великолепная книга, несмотря на некоторые моменты, которые могли бы быть лучше.', 5, 1);  -- Book ID 5, User ID 1

INSERT INTO payments (amount, transaction_id, payment_status, order_id)
VALUES
    (2499.75, 'TX123456789', 'pending', 1),  -- Order ID 1
    (1499.50, 'TX987654321', 'success', 2),   -- Order ID 2
    (799.99, 'TX112233445', 'pending', 3),   -- Order ID 3
    (899.00, 'TX556677889', 'failed', 4),   -- Order ID 4
    (2799.00, 'TX998877665', 'success', 5);  -- Order ID 5
