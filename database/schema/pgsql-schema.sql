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
-- Name: set_updated_at(); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION public.set_updated_at() RETURNS trigger
    LANGUAGE plpgsql
    AS $$ BEGIN --
    NEW.updated_at = CURRENT_TIMESTAMP;
RETURN NEW;
END;
$$;


SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: books; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.books (
    id bigint NOT NULL,
    title character varying(255) NOT NULL,
    author character varying(255) NOT NULL,
    publisher character varying(255) NOT NULL,
    image_path character varying(255),
    sample_page_images text[],
    publication_year smallint NOT NULL,
    price numeric(19,2) NOT NULL,
    quantity_in_stock smallint,
    description text,
    binding_type character varying(50),
    publication_type character varying(50) NOT NULL,
    isbn character varying(20) NOT NULL,
    edition character varying(50),
    circulation integer,
    language character varying(50) NOT NULL,
    pages smallint,
    weight numeric(10,2),
    size character varying(20),
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


--
-- Name: books_categories; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.books_categories (
    book_id bigint NOT NULL,
    category_id bigint NOT NULL
);


--
-- Name: books_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.books_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: books_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.books_id_seq OWNED BY public.books.id;


--
-- Name: cache; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration integer NOT NULL
);


--
-- Name: cache_locks; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration integer NOT NULL
);


--
-- Name: card_payments; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.card_payments (
    id bigint NOT NULL,
    payment_id bigint NOT NULL,
    card_type character varying(50) NOT NULL,
    card_last_four character(4) NOT NULL,
    card_expiry_month smallint,
    card_expiry_year smallint,
    cardholder_name character varying(255),
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


--
-- Name: card_payments_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.card_payments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: card_payments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.card_payments_id_seq OWNED BY public.card_payments.id;


--
-- Name: categories; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.categories (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    description text,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    url_slug character varying(20)
);


--
-- Name: categories_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.categories_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: categories_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.categories_id_seq OWNED BY public.categories.id;


--
-- Name: deliveries; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.deliveries (
    id bigint NOT NULL,
    order_id bigint NOT NULL,
    courier character varying(255),
    tracking_number character varying(255),
    delivery_status character varying(20) DEFAULT 'pending'::character varying NOT NULL,
    shipped_at timestamp without time zone,
    expected_delivery timestamp without time zone,
    delivered_at timestamp without time zone,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


--
-- Name: deliveries_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.deliveries_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: deliveries_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.deliveries_id_seq OWNED BY public.deliveries.id;


--
-- Name: delivery_details; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.delivery_details (
    id bigint NOT NULL,
    user_id bigint NOT NULL,
    address_line1 character varying(255) NOT NULL,
    address_line2 character varying(255),
    city character varying(100) NOT NULL,
    state character varying(100) NOT NULL,
    postal_code character varying(20) NOT NULL,
    country character varying(100) NOT NULL,
    phone character varying(20),
    user_comment character varying(255),
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


--
-- Name: delivery_details_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.delivery_details_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: delivery_details_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.delivery_details_id_seq OWNED BY public.delivery_details.id;


--
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- Name: job_batches; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.job_batches (
    id character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    total_jobs integer NOT NULL,
    pending_jobs integer NOT NULL,
    failed_jobs integer NOT NULL,
    failed_job_ids text NOT NULL,
    options text,
    cancelled_at integer,
    created_at integer NOT NULL,
    finished_at integer
);


--
-- Name: jobs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.jobs (
    id bigint NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    attempts smallint NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);


--
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: order_cancellations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.order_cancellations (
    id bigint NOT NULL,
    order_id bigint NOT NULL,
    cancellation_reason text,
    cancelled_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    refunded_amount numeric(19,2),
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


--
-- Name: order_cancellations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.order_cancellations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: order_cancellations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.order_cancellations_id_seq OWNED BY public.order_cancellations.id;


--
-- Name: order_returns; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.order_returns (
    id bigint NOT NULL,
    order_id bigint NOT NULL,
    book_id bigint NOT NULL,
    return_quantity smallint NOT NULL,
    return_reason text,
    return_status character varying(20) DEFAULT 'pending'::character varying NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


--
-- Name: order_returns_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.order_returns_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: order_returns_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.order_returns_id_seq OWNED BY public.order_returns.id;


--
-- Name: orders; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.orders (
    id bigint NOT NULL,
    order_status character varying(20) DEFAULT 'pending'::character varying NOT NULL,
    order_total numeric(19,2) NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    user_id bigint NOT NULL,
    delivery_detail_id bigint NOT NULL
);


--
-- Name: orders_books; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.orders_books (
    quantity smallint NOT NULL,
    price numeric(19,2) NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    order_id bigint NOT NULL,
    book_id bigint NOT NULL
);


--
-- Name: orders_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.orders_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: orders_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.orders_id_seq OWNED BY public.orders.id;


--
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


--
-- Name: payments; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.payments (
    id bigint NOT NULL,
    amount numeric(19,2) NOT NULL,
    transaction_id character varying(255) NOT NULL,
    payment_method character varying(20) DEFAULT 'card'::character varying NOT NULL,
    payment_status character varying(20) DEFAULT 'pending'::character varying NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    order_id bigint NOT NULL
);


--
-- Name: payments_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.payments_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: payments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.payments_id_seq OWNED BY public.payments.id;


--
-- Name: reviews; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.reviews (
    id integer NOT NULL,
    rating smallint NOT NULL,
    review_comment text,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    book_id bigint NOT NULL,
    user_id bigint NOT NULL,
    CONSTRAINT reviews_rating_check CHECK (((rating >= 1) AND (rating <= 5)))
);


--
-- Name: reviews_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.reviews_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: reviews_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.reviews_id_seq OWNED BY public.reviews.id;


--
-- Name: sessions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id bigint,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);


--
-- Name: users; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    first_name character varying(255),
    last_name character varying(255),
    email character varying(255) NOT NULL,
    password character varying(255) NOT NULL,
    email_verified_at timestamp without time zone,
    remember_token character varying(100),
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    phone character varying(20),
    date_of_birth date,
    gender character varying(10),
    CONSTRAINT users_gender_check CHECK ((((gender)::text = ANY ((ARRAY['male'::character varying, 'female'::character varying])::text[])) OR (gender IS NULL)))
);


--
-- Name: users_favorite_books; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.users_favorite_books (
    user_id bigint NOT NULL,
    book_id bigint NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);


--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: books id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.books ALTER COLUMN id SET DEFAULT nextval('public.books_id_seq'::regclass);


--
-- Name: card_payments id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.card_payments ALTER COLUMN id SET DEFAULT nextval('public.card_payments_id_seq'::regclass);


--
-- Name: categories id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.categories ALTER COLUMN id SET DEFAULT nextval('public.categories_id_seq'::regclass);


--
-- Name: deliveries id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.deliveries ALTER COLUMN id SET DEFAULT nextval('public.deliveries_id_seq'::regclass);


--
-- Name: delivery_details id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.delivery_details ALTER COLUMN id SET DEFAULT nextval('public.delivery_details_id_seq'::regclass);


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: order_cancellations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.order_cancellations ALTER COLUMN id SET DEFAULT nextval('public.order_cancellations_id_seq'::regclass);


--
-- Name: order_returns id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.order_returns ALTER COLUMN id SET DEFAULT nextval('public.order_returns_id_seq'::regclass);


--
-- Name: orders id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.orders ALTER COLUMN id SET DEFAULT nextval('public.orders_id_seq'::regclass);


--
-- Name: payments id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.payments ALTER COLUMN id SET DEFAULT nextval('public.payments_id_seq'::regclass);


--
-- Name: reviews id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.reviews ALTER COLUMN id SET DEFAULT nextval('public.reviews_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Name: books_categories books_categories_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.books_categories
    ADD CONSTRAINT books_categories_pkey PRIMARY KEY (book_id, category_id);


--
-- Name: books books_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.books
    ADD CONSTRAINT books_pkey PRIMARY KEY (id);


--
-- Name: cache_locks cache_locks_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);


--
-- Name: cache cache_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);


--
-- Name: card_payments card_payments_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.card_payments
    ADD CONSTRAINT card_payments_pkey PRIMARY KEY (id);


--
-- Name: categories categories_name_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.categories
    ADD CONSTRAINT categories_name_key UNIQUE (name);


--
-- Name: categories categories_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.categories
    ADD CONSTRAINT categories_pkey PRIMARY KEY (id);


--
-- Name: deliveries deliveries_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.deliveries
    ADD CONSTRAINT deliveries_pkey PRIMARY KEY (id);


--
-- Name: delivery_details delivery_details_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.delivery_details
    ADD CONSTRAINT delivery_details_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- Name: job_batches job_batches_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.job_batches
    ADD CONSTRAINT job_batches_pkey PRIMARY KEY (id);


--
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: order_cancellations order_cancellations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.order_cancellations
    ADD CONSTRAINT order_cancellations_pkey PRIMARY KEY (id);


--
-- Name: order_returns order_returns_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.order_returns
    ADD CONSTRAINT order_returns_pkey PRIMARY KEY (id);


--
-- Name: orders_books orders_books_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.orders_books
    ADD CONSTRAINT orders_books_pkey PRIMARY KEY (order_id, book_id);


--
-- Name: orders orders_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.orders
    ADD CONSTRAINT orders_pkey PRIMARY KEY (id);


--
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- Name: payments payments_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.payments
    ADD CONSTRAINT payments_pkey PRIMARY KEY (id);


--
-- Name: reviews reviews_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.reviews
    ADD CONSTRAINT reviews_pkey PRIMARY KEY (id);


--
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- Name: reviews unique_user_book_review; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.reviews
    ADD CONSTRAINT unique_user_book_review UNIQUE (user_id, book_id);


--
-- Name: users users_email_key; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_key UNIQUE (email);


--
-- Name: users_favorite_books users_favorite_books_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users_favorite_books
    ADD CONSTRAINT users_favorite_books_pkey PRIMARY KEY (user_id, book_id);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: idx_books_author; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_books_author ON public.books USING btree (author);


--
-- Name: idx_books_categories_book_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_books_categories_book_id ON public.books_categories USING btree (book_id);


--
-- Name: idx_books_categories_category_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_books_categories_category_id ON public.books_categories USING btree (category_id);


--
-- Name: idx_books_title; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_books_title ON public.books USING btree (title);


--
-- Name: idx_card_payments_payment_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_card_payments_payment_id ON public.card_payments USING btree (payment_id);


--
-- Name: idx_deliveries_order_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_deliveries_order_id ON public.deliveries USING btree (order_id);


--
-- Name: idx_delivery_details_user_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_delivery_details_user_id ON public.delivery_details USING btree (user_id);


--
-- Name: idx_order_cancellations_order_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_order_cancellations_order_id ON public.order_cancellations USING btree (order_id);


--
-- Name: idx_order_returns_book_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_order_returns_book_id ON public.order_returns USING btree (book_id);


--
-- Name: idx_order_returns_order_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_order_returns_order_id ON public.order_returns USING btree (order_id);


--
-- Name: idx_orders_books_book_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_orders_books_book_id ON public.orders_books USING btree (book_id);


--
-- Name: idx_orders_books_order_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_orders_books_order_id ON public.orders_books USING btree (order_id);


--
-- Name: idx_orders_user_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_orders_user_id ON public.orders USING btree (user_id);


--
-- Name: idx_payments_order_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_payments_order_id ON public.payments USING btree (order_id);


--
-- Name: idx_reviews_book_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_reviews_book_id ON public.reviews USING btree (book_id);


--
-- Name: idx_reviews_user_id; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX idx_reviews_user_id ON public.reviews USING btree (user_id);


--
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


--
-- Name: sessions_last_activity_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);


--
-- Name: sessions_user_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


--
-- Name: books update_timestamp_in_books; Type: TRIGGER; Schema: public; Owner: -
--

CREATE TRIGGER update_timestamp_in_books BEFORE UPDATE ON public.books FOR EACH ROW EXECUTE FUNCTION public.set_updated_at();


--
-- Name: card_payments update_timestamp_in_card_payments; Type: TRIGGER; Schema: public; Owner: -
--

CREATE TRIGGER update_timestamp_in_card_payments BEFORE UPDATE ON public.card_payments FOR EACH ROW EXECUTE FUNCTION public.set_updated_at();


--
-- Name: categories update_timestamp_in_categories; Type: TRIGGER; Schema: public; Owner: -
--

CREATE TRIGGER update_timestamp_in_categories BEFORE UPDATE ON public.categories FOR EACH ROW EXECUTE FUNCTION public.set_updated_at();


--
-- Name: deliveries update_timestamp_in_deliveries; Type: TRIGGER; Schema: public; Owner: -
--

CREATE TRIGGER update_timestamp_in_deliveries BEFORE UPDATE ON public.deliveries FOR EACH ROW EXECUTE FUNCTION public.set_updated_at();


--
-- Name: delivery_details update_timestamp_in_delivery_details; Type: TRIGGER; Schema: public; Owner: -
--

CREATE TRIGGER update_timestamp_in_delivery_details BEFORE UPDATE ON public.delivery_details FOR EACH ROW EXECUTE FUNCTION public.set_updated_at();


--
-- Name: order_cancellations update_timestamp_in_order_cancellations; Type: TRIGGER; Schema: public; Owner: -
--

CREATE TRIGGER update_timestamp_in_order_cancellations BEFORE UPDATE ON public.order_cancellations FOR EACH ROW EXECUTE FUNCTION public.set_updated_at();


--
-- Name: order_returns update_timestamp_in_order_returns; Type: TRIGGER; Schema: public; Owner: -
--

CREATE TRIGGER update_timestamp_in_order_returns BEFORE UPDATE ON public.order_returns FOR EACH ROW EXECUTE FUNCTION public.set_updated_at();


--
-- Name: orders update_timestamp_in_orders; Type: TRIGGER; Schema: public; Owner: -
--

CREATE TRIGGER update_timestamp_in_orders BEFORE UPDATE ON public.orders FOR EACH ROW EXECUTE FUNCTION public.set_updated_at();


--
-- Name: orders_books update_timestamp_in_orders_books; Type: TRIGGER; Schema: public; Owner: -
--

CREATE TRIGGER update_timestamp_in_orders_books BEFORE UPDATE ON public.orders_books FOR EACH ROW EXECUTE FUNCTION public.set_updated_at();


--
-- Name: payments update_timestamp_in_payments; Type: TRIGGER; Schema: public; Owner: -
--

CREATE TRIGGER update_timestamp_in_payments BEFORE UPDATE ON public.payments FOR EACH ROW EXECUTE FUNCTION public.set_updated_at();


--
-- Name: reviews update_timestamp_in_reviews; Type: TRIGGER; Schema: public; Owner: -
--

CREATE TRIGGER update_timestamp_in_reviews BEFORE UPDATE ON public.reviews FOR EACH ROW EXECUTE FUNCTION public.set_updated_at();


--
-- Name: users update_timestamp_in_users; Type: TRIGGER; Schema: public; Owner: -
--

CREATE TRIGGER update_timestamp_in_users BEFORE UPDATE ON public.users FOR EACH ROW EXECUTE FUNCTION public.set_updated_at();


--
-- Name: books_categories fk_books_categories_books; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.books_categories
    ADD CONSTRAINT fk_books_categories_books FOREIGN KEY (book_id) REFERENCES public.books(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: books_categories fk_books_categories_categories; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.books_categories
    ADD CONSTRAINT fk_books_categories_categories FOREIGN KEY (category_id) REFERENCES public.categories(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: card_payments fk_card_payments_payments; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.card_payments
    ADD CONSTRAINT fk_card_payments_payments FOREIGN KEY (payment_id) REFERENCES public.payments(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: delivery_details fk_delivery_details_users; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.delivery_details
    ADD CONSTRAINT fk_delivery_details_users FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: order_cancellations fk_order_cancellations_orders; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.order_cancellations
    ADD CONSTRAINT fk_order_cancellations_orders FOREIGN KEY (order_id) REFERENCES public.orders(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: order_returns fk_order_returns_books; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.order_returns
    ADD CONSTRAINT fk_order_returns_books FOREIGN KEY (book_id) REFERENCES public.books(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: order_returns fk_order_returns_orders; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.order_returns
    ADD CONSTRAINT fk_order_returns_orders FOREIGN KEY (order_id) REFERENCES public.orders(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: orders_books fk_orders_books_books; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.orders_books
    ADD CONSTRAINT fk_orders_books_books FOREIGN KEY (book_id) REFERENCES public.books(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: orders_books fk_orders_books_orders; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.orders_books
    ADD CONSTRAINT fk_orders_books_orders FOREIGN KEY (order_id) REFERENCES public.orders(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: orders fk_orders_delivery_details; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.orders
    ADD CONSTRAINT fk_orders_delivery_details FOREIGN KEY (delivery_detail_id) REFERENCES public.delivery_details(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: orders fk_orders_users; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.orders
    ADD CONSTRAINT fk_orders_users FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: payments fk_payments_orders; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.payments
    ADD CONSTRAINT fk_payments_orders FOREIGN KEY (order_id) REFERENCES public.orders(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: reviews fk_reviews_books; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.reviews
    ADD CONSTRAINT fk_reviews_books FOREIGN KEY (book_id) REFERENCES public.books(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: reviews fk_reviews_users; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.reviews
    ADD CONSTRAINT fk_reviews_users FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: users_favorite_books fk_users_favorite_books_books; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users_favorite_books
    ADD CONSTRAINT fk_users_favorite_books_books FOREIGN KEY (book_id) REFERENCES public.books(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: users_favorite_books fk_users_favorite_books_users; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users_favorite_books
    ADD CONSTRAINT fk_users_favorite_books_users FOREIGN KEY (user_id) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

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
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	0001_01_01_000000_create_users_table	1
2	0001_01_01_000001_create_cache_table	1
3	0001_01_01_000002_create_jobs_table	1
\.


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.migrations_id_seq', 3, true);


--
-- PostgreSQL database dump complete
--

