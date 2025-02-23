----------------------------------------------------------
-- Usage
----------------------------------------------------------
-- 1) cd into a folder with a schema.sql file
-- 2) $ psql -U <user_name> -d <db_name> -f dump_data_only.sql

----------------------------------------------------------
--
-- PostgreSQL database dump
--

-- Dumped from database version 15.7 (Debian 15.7-1.pgdg120+1)
-- Dumped by pg_dump version 15.8

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Data for Name: books; Type: TABLE DATA; Schema: public; Owner: user
--

COPY public.books (id, title, author, publisher, publication_year, price, quantity_in_stock, description, created_at, updated_at) FROM stdin;
1	Преступление и наказание	Фёдор Достоевский	Азбука	1866	799.99	15	Роман о внутренней борьбе студента Раскольникова после убийства.	2025-02-04 14:19:00.620413+00	2025-02-04 14:19:00.620413+00
2	Война и мир	Лев Толстой	Эксмо	1869	1499.50	10	Эпопея о жизни русской аристократии в эпоху Наполеоновских войн.	2025-02-04 14:19:00.620413+00	2025-02-04 14:19:00.620413+00
3	Анна Каренина	Лев Толстой	Азбука	1877	899.00	12	Трагическая история любви Анны Карениной и Вронского.	2025-02-04 14:19:00.620413+00	2025-02-04 14:19:00.620413+00
4	Отцы и дети	Иван Тургенев	АСТ	1862	650.75	8	Роман о столкновении поколений и нигилизме.	2025-02-04 14:19:00.620413+00	2025-02-04 14:19:00.620413+00
5	Мёртвые души	Николай Гоголь	Эксмо	1842	720.30	9	Сатирическая поэма о российском чиновничестве.	2025-02-04 14:19:00.620413+00	2025-02-04 14:19:00.620413+00
6	Герой нашего времени	Михаил Лермонтов	Азбука	1840	560.20	14	Психологический портрет офицера Печорина.	2025-02-04 14:19:00.620413+00	2025-02-04 14:19:00.620413+00
7	Евгений Онегин	Александр Пушкин	Эксмо	1833	480.99	20	Роман в стихах о судьбе светского молодого человека.	2025-02-04 14:19:00.620413+00	2025-02-04 14:19:00.620413+00
8	Человек в футляре	Антон Чехов	АСТ	1898	350.00	18	Повесть о замкнутом и боязливом человеке Беликове.	2025-02-04 14:19:00.620413+00	2025-02-04 14:19:00.620413+00
9	Доктор Живаго	Борис Пастернак	Азбука	1957	1100.75	7	Историко-романтическая драма на фоне революции.	2025-02-04 14:19:00.620413+00	2025-02-04 14:19:00.620413+00
10	Мастер и Маргарита	Михаил Булгаков	Эксмо	1967	990.99	5	Мистический роман о визите дьявола в Москву.	2025-02-04 14:19:00.620413+00	2025-02-04 14:19:00.620413+00
\.


--
-- Data for Name: categories; Type: TABLE DATA; Schema: public; Owner: user
--

COPY public.categories (id, name, description, created_at, updated_at) FROM stdin;
1	Фантастика	Книги о вымышленных мирах и технологиях будущего	2025-02-04 14:20:53.293182+00	2025-02-04 14:20:53.293182+00
2	Детектив	Истории о расследованиях преступлений	2025-02-04 14:20:53.293182+00	2025-02-04 14:20:53.293182+00
3	Фэнтези	Мир магии, волшебства и невероятных приключений	2025-02-04 14:20:53.293182+00	2025-02-04 14:20:53.293182+00
4	История	Книги о прошлом, исторических событиях и личностях	2025-02-04 14:20:53.293182+00	2025-02-04 14:20:53.293182+00
5	Биография	Жизнеописания известных людей	2025-02-04 14:20:53.293182+00	2025-02-04 14:20:53.293182+00
6	Наука	Книги о научных открытиях и исследованиях	2025-02-04 14:20:53.293182+00	2025-02-04 14:20:53.293182+00
7	Философия	Размышления о жизни, бытии и сознании	2025-02-04 14:20:53.293182+00	2025-02-04 14:20:53.293182+00
8	Психология	Книги о человеческом мышлении и поведении	2025-02-04 14:20:53.293182+00	2025-02-04 14:20:53.293182+00
9	Классика	Произведения, проверенные временем	2025-02-04 14:20:53.293182+00	2025-02-04 14:20:53.293182+00
10	Поэзия	Сборники стихотворений и поэм	2025-02-04 14:20:53.293182+00	2025-02-04 14:20:53.293182+00
\.


--
-- Data for Name: book_categories; Type: TABLE DATA; Schema: public; Owner: user
--

COPY public.book_categories (book_id, category_id) FROM stdin;
1	9
2	9
2	4
3	9
4	9
4	7
5	9
6	9
6	7
7	9
7	10
8	9
8	7
9	9
9	4
10	9
10	3
10	7
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: user
--

COPY public.users (id, email, password_hash, roles, first_name, last_name, created_at, updated_at) FROM stdin;
1	superadmin@example.com	$2y$13$GeXsv/KQR7cFm/qNpdRraOCGAcSSBoOhsl56qS3IGw/zPcPbisENW	["ROLE_SUPER_ADMIN"]	Александр	Александров	2025-02-04 14:27:44.300916+00	2025-02-04 14:27:44.300916+00
2	admin@example.com	$2y$13$GeXsv/KQR7cFm/qNpdRraOCGAcSSBoOhsl56qS3IGw/zPcPbisENW	["ROLE_ADMIN"]	Борис	Борисов	2025-02-04 14:27:44.300916+00	2025-02-04 14:27:44.300916+00
3	vasiliy@example.com	$2y$13$GeXsv/KQR7cFm/qNpdRraOCGAcSSBoOhsl56qS3IGw/zPcPbisENW	["ROLE_USER"]	Василий	Васильев	2025-02-04 14:27:44.300916+00	2025-02-04 14:27:44.300916+00
4	grigoriy@example.com	$2y$13$GeXsv/KQR7cFm/qNpdRraOCGAcSSBoOhsl56qS3IGw/zPcPbisENW	["ROLE_USER"]	Григорий	Григорьев	2025-02-04 14:27:44.300916+00	2025-02-04 14:27:44.300916+00
\.


--
-- Data for Name: orders; Type: TABLE DATA; Schema: public; Owner: user
--

COPY public.orders (id, order_status, order_total, created_at, updated_at, user_id) FROM stdin;
1	pending	2499.75	2025-02-04 14:28:17.693436+00	2025-02-04 14:28:17.693436+00	3
2	completed	1499.50	2025-02-04 14:28:17.693436+00	2025-02-04 14:28:17.693436+00	4
3	pending	799.99	2025-02-04 14:28:17.693436+00	2025-02-04 14:28:17.693436+00	3
4	cancelled	899.00	2025-02-04 14:28:17.693436+00	2025-02-04 14:28:17.693436+00	4
5	completed	2799.00	2025-02-04 14:28:17.693436+00	2025-02-04 14:28:17.693436+00	3
\.


--
-- Data for Name: order_items; Type: TABLE DATA; Schema: public; Owner: user
--

COPY public.order_items (quantity, price, created_at, updated_at, order_id, book_id) FROM stdin;
2	799.99	2025-02-04 14:28:39.763983+00	2025-02-04 14:28:39.763983+00	1	1
1	1499.50	2025-02-04 14:28:39.763983+00	2025-02-04 14:28:39.763983+00	2	2
3	799.99	2025-02-04 14:28:39.763983+00	2025-02-04 14:28:39.763983+00	3	3
1	650.75	2025-02-04 14:28:39.763983+00	2025-02-04 14:28:39.763983+00	4	4
2	799.99	2025-02-04 14:28:39.763983+00	2025-02-04 14:28:39.763983+00	5	5
1	899.00	2025-02-04 14:28:39.763983+00	2025-02-04 14:28:39.763983+00	1	6
1	560.20	2025-02-04 14:28:39.763983+00	2025-02-04 14:28:39.763983+00	2	7
1	720.30	2025-02-04 14:28:39.763983+00	2025-02-04 14:28:39.763983+00	3	8
2	480.99	2025-02-04 14:28:39.763983+00	2025-02-04 14:28:39.763983+00	4	9
1	1100.75	2025-02-04 14:28:39.763983+00	2025-02-04 14:28:39.763983+00	5	10
\.


--
-- Data for Name: payments; Type: TABLE DATA; Schema: public; Owner: user
--

COPY public.payments (id, amount, transaction_id, payment_status, created_at, updated_at, order_id) FROM stdin;
1	2499.75	TX123456789	pending	2025-02-04 14:36:26.940574+00	2025-02-04 14:36:26.940574+00	1
2	1499.50	TX987654321	success	2025-02-04 14:36:26.940574+00	2025-02-04 14:36:26.940574+00	2
3	799.99	TX112233445	pending	2025-02-04 14:36:26.940574+00	2025-02-04 14:36:26.940574+00	3
4	899.00	TX556677889	failed	2025-02-04 14:36:26.940574+00	2025-02-04 14:36:26.940574+00	4
5	2799.00	TX998877665	success	2025-02-04 14:36:26.940574+00	2025-02-04 14:36:26.940574+00	5
\.


--
-- Data for Name: reviews; Type: TABLE DATA; Schema: public; Owner: user
--

COPY public.reviews (id, rating, review_comment, created_at, updated_at, book_id, user_id) FROM stdin;
1	5	Превосходная книга, обязательна к прочтению для всех любителей классики.	2025-02-04 14:36:02.028978+00	2025-02-04 14:36:02.028978+00	1	1
2	4	Очень интересная, но местами затянутая. Хороший роман.	2025-02-04 14:36:02.028978+00	2025-02-04 14:36:02.028978+00	2	2
3	3	История захватывающая, но концовка оставляет желать лучшего.	2025-02-04 14:36:02.028978+00	2025-02-04 14:36:02.028978+00	3	3
4	5	Одно из величайших произведений русской литературы, сильно впечатляет.	2025-02-04 14:36:02.028978+00	2025-02-04 14:36:02.028978+00	4	4
5	4	Великолепная книга, несмотря на некоторые моменты, которые могли бы быть лучше.	2025-02-04 14:36:02.028978+00	2025-02-04 14:36:02.028978+00	5	1
\.


--
-- Name: books_id_seq; Type: SEQUENCE SET; Schema: public; Owner: user
--

SELECT pg_catalog.setval('public.books_id_seq', 10, true);


--
-- Name: categories_id_seq; Type: SEQUENCE SET; Schema: public; Owner: user
--

SELECT pg_catalog.setval('public.categories_id_seq', 10, true);


--
-- Name: orders_id_seq; Type: SEQUENCE SET; Schema: public; Owner: user
--

SELECT pg_catalog.setval('public.orders_id_seq', 5, true);


--
-- Name: payments_id_seq; Type: SEQUENCE SET; Schema: public; Owner: user
--

SELECT pg_catalog.setval('public.payments_id_seq', 5, true);


--
-- Name: reviews_id_seq; Type: SEQUENCE SET; Schema: public; Owner: user
--

SELECT pg_catalog.setval('public.reviews_id_seq', 5, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: user
--

SELECT pg_catalog.setval('public.users_id_seq', 4, true);


--
-- PostgreSQL database dump complete
--
