--
-- PostgreSQL database dump
--

\restrict 5GheGnbH8lrBJLKIAyETKJHJeaI6JKOhaSYyDIQkWMeUwu2QYfeWDAKy1MjFpUn

-- Dumped from database version 18.4
-- Dumped by pg_dump version 18.4

-- Started on 2026-07-30 13:09:09

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 251 (class 1259 OID 41144)
-- Name: asset_maintenance_logs; Type: TABLE; Schema: public; Owner: ams_user
--

CREATE TABLE public.asset_maintenance_logs (
    id bigint NOT NULL,
    asset_id bigint NOT NULL,
    ticket_id bigint,
    pengajuan_aset_id bigint,
    dispose_aset_id bigint,
    berita_acara_id bigint,
    maintenance_type character varying(255) DEFAULT 'repair'::character varying NOT NULL,
    title character varying(255) NOT NULL,
    description text,
    cost numeric(15,2),
    performed_by character varying(255),
    performed_at date NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT asset_maintenance_logs_maintenance_type_check CHECK (((maintenance_type)::text = ANY ((ARRAY['repair'::character varying, 'sparepart_replacement'::character varying, 'routine_service'::character varying, 'upgrade'::character varying, 'disposal'::character varying])::text[])))
);


ALTER TABLE public.asset_maintenance_logs OWNER TO ams_user;

--
-- TOC entry 250 (class 1259 OID 41143)
-- Name: asset_maintenance_logs_id_seq; Type: SEQUENCE; Schema: public; Owner: ams_user
--

CREATE SEQUENCE public.asset_maintenance_logs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.asset_maintenance_logs_id_seq OWNER TO ams_user;

--
-- TOC entry 5263 (class 0 OID 0)
-- Dependencies: 250
-- Name: asset_maintenance_logs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ams_user
--

ALTER SEQUENCE public.asset_maintenance_logs_id_seq OWNED BY public.asset_maintenance_logs.id;


--
-- TOC entry 237 (class 1259 OID 16532)
-- Name: asset_models; Type: TABLE; Schema: public; Owner: ams_user
--

CREATE TABLE public.asset_models (
    id bigint NOT NULL,
    category_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    manufacturer character varying(255),
    model_number character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.asset_models OWNER TO ams_user;

--
-- TOC entry 236 (class 1259 OID 16531)
-- Name: asset_models_id_seq; Type: SEQUENCE; Schema: public; Owner: ams_user
--

CREATE SEQUENCE public.asset_models_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.asset_models_id_seq OWNER TO ams_user;

--
-- TOC entry 5264 (class 0 OID 0)
-- Dependencies: 236
-- Name: asset_models_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ams_user
--

ALTER SEQUENCE public.asset_models_id_seq OWNED BY public.asset_models.id;


--
-- TOC entry 239 (class 1259 OID 16549)
-- Name: assets; Type: TABLE; Schema: public; Owner: ams_user
--

CREATE TABLE public.assets (
    id bigint NOT NULL,
    asset_tag character varying(255) NOT NULL,
    serial character varying(255),
    asset_model_id bigint NOT NULL,
    location_id bigint,
    status character varying(255) DEFAULT 'in_stock'::character varying NOT NULL,
    purchase_date date,
    purchase_cost numeric(12,2),
    notes text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    room character varying(255),
    department character varying(255),
    primary_user character varying(255),
    secondary_user character varying(255),
    processor character varying(255),
    ram character varying(255),
    storage_hdd character varying(255),
    storage_ssd character varying(255),
    vga_card character varying(255),
    monitor_id character varying(255),
    monitor_spec character varying(255),
    condition character varying(255) DEFAULT 'bagus'::character varying NOT NULL,
    purchase_year integer,
    warranty character varying(255),
    attachments json,
    operating_system character varying(255),
    CONSTRAINT assets_status_check CHECK (((status)::text = ANY (ARRAY['in_stock'::text, 'checked_out'::text, 'in_repair'::text, 'archived'::text, 'disposed'::text])))
);


ALTER TABLE public.assets OWNER TO ams_user;

--
-- TOC entry 238 (class 1259 OID 16548)
-- Name: assets_id_seq; Type: SEQUENCE; Schema: public; Owner: ams_user
--

CREATE SEQUENCE public.assets_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.assets_id_seq OWNER TO ams_user;

--
-- TOC entry 5265 (class 0 OID 0)
-- Dependencies: 238
-- Name: assets_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ams_user
--

ALTER SEQUENCE public.assets_id_seq OWNED BY public.assets.id;


--
-- TOC entry 243 (class 1259 OID 40966)
-- Name: berita_acaras; Type: TABLE; Schema: public; Owner: ams_user
--

CREATE TABLE public.berita_acaras (
    id bigint NOT NULL,
    letter_number character varying(255) NOT NULL,
    letter_date date NOT NULL,
    category character varying(255) DEFAULT 'kehilangan'::character varying NOT NULL,
    title character varying(255),
    asset_id bigint,
    asset_tag character varying(255),
    asset_name character varying(255),
    quantity character varying(255) DEFAULT '1 Unit'::character varying NOT NULL,
    completeness character varying(255) DEFAULT '1 Unit Laptop + Charger'::character varying,
    party1_name character varying(255) NOT NULL,
    party1_title character varying(255) DEFAULT 'IT STAFF'::character varying NOT NULL,
    party1_department character varying(255) DEFAULT 'INFORMATION & TECHNOLOGY'::character varying NOT NULL,
    party2_name character varying(255) NOT NULL,
    party2_title character varying(255),
    party2_department character varying(255),
    approver_name character varying(255) DEFAULT 'SETYADI CANDRAWINATA'::character varying,
    approver_title character varying(255) DEFAULT 'GM Finance & Operations'::character varying,
    description_points text NOT NULL,
    attachments json,
    created_by bigint,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT berita_acaras_category_check CHECK (((category)::text = ANY ((ARRAY['kehilangan'::character varying, 'kerusakan_sparepart'::character varying, 'transfer_asset'::character varying, 'penggantian_unit'::character varying])::text[])))
);


ALTER TABLE public.berita_acaras OWNER TO ams_user;

--
-- TOC entry 242 (class 1259 OID 40965)
-- Name: berita_acaras_id_seq; Type: SEQUENCE; Schema: public; Owner: ams_user
--

CREATE SEQUENCE public.berita_acaras_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.berita_acaras_id_seq OWNER TO ams_user;

--
-- TOC entry 5266 (class 0 OID 0)
-- Dependencies: 242
-- Name: berita_acaras_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ams_user
--

ALTER SEQUENCE public.berita_acaras_id_seq OWNED BY public.berita_acaras.id;


--
-- TOC entry 225 (class 1259 OID 16435)
-- Name: cache; Type: TABLE; Schema: public; Owner: ams_user
--

CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration bigint NOT NULL
);


ALTER TABLE public.cache OWNER TO ams_user;

--
-- TOC entry 226 (class 1259 OID 16446)
-- Name: cache_locks; Type: TABLE; Schema: public; Owner: ams_user
--

CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration bigint NOT NULL
);


ALTER TABLE public.cache_locks OWNER TO ams_user;

--
-- TOC entry 233 (class 1259 OID 16508)
-- Name: categories; Type: TABLE; Schema: public; Owner: ams_user
--

CREATE TABLE public.categories (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    type character varying(255) DEFAULT 'asset'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.categories OWNER TO ams_user;

--
-- TOC entry 232 (class 1259 OID 16507)
-- Name: categories_id_seq; Type: SEQUENCE; Schema: public; Owner: ams_user
--

CREATE SEQUENCE public.categories_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.categories_id_seq OWNER TO ams_user;

--
-- TOC entry 5267 (class 0 OID 0)
-- Dependencies: 232
-- Name: categories_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ams_user
--

ALTER SEQUENCE public.categories_id_seq OWNED BY public.categories.id;


--
-- TOC entry 241 (class 1259 OID 16577)
-- Name: checkouts; Type: TABLE; Schema: public; Owner: ams_user
--

CREATE TABLE public.checkouts (
    id bigint NOT NULL,
    asset_id bigint NOT NULL,
    user_id bigint,
    checked_out_by bigint,
    checked_in_by bigint,
    checked_out_at timestamp(0) without time zone NOT NULL,
    checked_in_at timestamp(0) without time zone,
    checkout_notes text,
    checkin_notes text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    primary_user character varying(255),
    secondary_user character varying(255),
    checkout_attachment character varying(255),
    checkin_attachment character varying(255),
    checkout_attachments json,
    checkin_attachments json,
    component_checklist json
);


ALTER TABLE public.checkouts OWNER TO ams_user;

--
-- TOC entry 240 (class 1259 OID 16576)
-- Name: checkouts_id_seq; Type: SEQUENCE; Schema: public; Owner: ams_user
--

CREATE SEQUENCE public.checkouts_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.checkouts_id_seq OWNER TO ams_user;

--
-- TOC entry 5268 (class 0 OID 0)
-- Dependencies: 240
-- Name: checkouts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ams_user
--

ALTER SEQUENCE public.checkouts_id_seq OWNED BY public.checkouts.id;


--
-- TOC entry 247 (class 1259 OID 41041)
-- Name: dispose_asets; Type: TABLE; Schema: public; Owner: ams_user
--

CREATE TABLE public.dispose_asets (
    id bigint NOT NULL,
    disposal_number character varying(255) NOT NULL,
    disposal_date date NOT NULL,
    asset_id bigint,
    asset_tag character varying(255) NOT NULL,
    asset_name character varying(255) NOT NULL,
    disposal_reason text NOT NULL,
    disposal_type character varying(255) DEFAULT 'sale'::character varying NOT NULL,
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    estimated_salvage_value numeric(15,2),
    created_by_name character varying(255) DEFAULT 'Bambang Yulianto'::character varying NOT NULL,
    spv_name character varying(255) DEFAULT 'Supervisor IT'::character varying,
    manager_name character varying(255) DEFAULT 'SETYADI CANDRAWINATA'::character varying,
    ga_recipient_name character varying(255) DEFAULT 'General Affairs (GA)'::character varying,
    attachments json,
    created_by bigint,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT dispose_asets_disposal_type_check CHECK (((disposal_type)::text = ANY ((ARRAY['sale'::character varying, 'destruction'::character varying, 'trade_in'::character varying, 'scrap'::character varying])::text[]))),
    CONSTRAINT dispose_asets_status_check CHECK (((status)::text = ANY ((ARRAY['pending'::character varying, 'approved'::character varying, 'transferred_to_ga'::character varying, 'completed'::character varying])::text[])))
);


ALTER TABLE public.dispose_asets OWNER TO ams_user;

--
-- TOC entry 246 (class 1259 OID 41040)
-- Name: dispose_asets_id_seq; Type: SEQUENCE; Schema: public; Owner: ams_user
--

CREATE SEQUENCE public.dispose_asets_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.dispose_asets_id_seq OWNER TO ams_user;

--
-- TOC entry 5269 (class 0 OID 0)
-- Dependencies: 246
-- Name: dispose_asets_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ams_user
--

ALTER SEQUENCE public.dispose_asets_id_seq OWNED BY public.dispose_asets.id;


--
-- TOC entry 231 (class 1259 OID 16488)
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: ams_user
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection character varying(255) NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.failed_jobs OWNER TO ams_user;

--
-- TOC entry 230 (class 1259 OID 16487)
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: ams_user
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.failed_jobs_id_seq OWNER TO ams_user;

--
-- TOC entry 5270 (class 0 OID 0)
-- Dependencies: 230
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ams_user
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- TOC entry 229 (class 1259 OID 16473)
-- Name: job_batches; Type: TABLE; Schema: public; Owner: ams_user
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


ALTER TABLE public.job_batches OWNER TO ams_user;

--
-- TOC entry 228 (class 1259 OID 16458)
-- Name: jobs; Type: TABLE; Schema: public; Owner: ams_user
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


ALTER TABLE public.jobs OWNER TO ams_user;

--
-- TOC entry 227 (class 1259 OID 16457)
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: ams_user
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.jobs_id_seq OWNER TO ams_user;

--
-- TOC entry 5271 (class 0 OID 0)
-- Dependencies: 227
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ams_user
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- TOC entry 235 (class 1259 OID 16521)
-- Name: locations; Type: TABLE; Schema: public; Owner: ams_user
--

CREATE TABLE public.locations (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    address character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.locations OWNER TO ams_user;

--
-- TOC entry 234 (class 1259 OID 16520)
-- Name: locations_id_seq; Type: SEQUENCE; Schema: public; Owner: ams_user
--

CREATE SEQUENCE public.locations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.locations_id_seq OWNER TO ams_user;

--
-- TOC entry 5272 (class 0 OID 0)
-- Dependencies: 234
-- Name: locations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ams_user
--

ALTER SEQUENCE public.locations_id_seq OWNED BY public.locations.id;


--
-- TOC entry 220 (class 1259 OID 16390)
-- Name: migrations; Type: TABLE; Schema: public; Owner: ams_user
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO ams_user;

--
-- TOC entry 219 (class 1259 OID 16389)
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: ams_user
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.migrations_id_seq OWNER TO ams_user;

--
-- TOC entry 5273 (class 0 OID 0)
-- Dependencies: 219
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ams_user
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- TOC entry 223 (class 1259 OID 16414)
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: ams_user
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


ALTER TABLE public.password_reset_tokens OWNER TO ams_user;

--
-- TOC entry 245 (class 1259 OID 41006)
-- Name: pengajuan_asets; Type: TABLE; Schema: public; Owner: ams_user
--

CREATE TABLE public.pengajuan_asets (
    id bigint NOT NULL,
    request_number character varying(255) NOT NULL,
    request_date date NOT NULL,
    title character varying(255) NOT NULL,
    requester_name character varying(255) NOT NULL,
    requester_department character varying(255) NOT NULL,
    item_type character varying(255) DEFAULT 'Laptop'::character varying NOT NULL,
    quantity integer DEFAULT 1 NOT NULL,
    priority character varying(255) DEFAULT 'medium'::character varying NOT NULL,
    status character varying(255) DEFAULT 'pending'::character varying NOT NULL,
    reason text NOT NULL,
    specification_requested text,
    estimated_cost numeric(15,2),
    approver_name character varying(255) DEFAULT 'SETYADI CANDRAWINATA'::character varying,
    approver_title character varying(255) DEFAULT 'GM Finance & Operations'::character varying,
    attachments json,
    created_by bigint,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    area character varying(255),
    CONSTRAINT pengajuan_asets_priority_check CHECK (((priority)::text = ANY ((ARRAY['low'::character varying, 'medium'::character varying, 'high'::character varying, 'urgent'::character varying])::text[]))),
    CONSTRAINT pengajuan_asets_status_check CHECK (((status)::text = ANY ((ARRAY['pending'::character varying, 'approved'::character varying, 'rejected'::character varying, 'completed'::character varying])::text[])))
);


ALTER TABLE public.pengajuan_asets OWNER TO ams_user;

--
-- TOC entry 244 (class 1259 OID 41005)
-- Name: pengajuan_asets_id_seq; Type: SEQUENCE; Schema: public; Owner: ams_user
--

CREATE SEQUENCE public.pengajuan_asets_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.pengajuan_asets_id_seq OWNER TO ams_user;

--
-- TOC entry 5274 (class 0 OID 0)
-- Dependencies: 244
-- Name: pengajuan_asets_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ams_user
--

ALTER SEQUENCE public.pengajuan_asets_id_seq OWNED BY public.pengajuan_asets.id;


--
-- TOC entry 224 (class 1259 OID 16423)
-- Name: sessions; Type: TABLE; Schema: public; Owner: ams_user
--

CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id bigint,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);


ALTER TABLE public.sessions OWNER TO ams_user;

--
-- TOC entry 249 (class 1259 OID 41080)
-- Name: tickets; Type: TABLE; Schema: public; Owner: ams_user
--

CREATE TABLE public.tickets (
    id bigint NOT NULL,
    ticket_number character varying(255) NOT NULL,
    reporter_name character varying(255) NOT NULL,
    reporter_department character varying(255) NOT NULL,
    contact_number character varying(255),
    location_id bigint,
    room character varying(255),
    asset_id bigint,
    asset_tag character varying(255),
    asset_name character varying(255),
    category character varying(255) DEFAULT 'hardware'::character varying NOT NULL,
    subject character varying(255) NOT NULL,
    description text NOT NULL,
    scheduled_date date NOT NULL,
    scheduled_time_slot character varying(255) DEFAULT '09:00 - 12:00'::character varying NOT NULL,
    due_date date,
    priority character varying(255) DEFAULT 'medium'::character varying NOT NULL,
    assigned_to bigint,
    assigned_to_name character varying(255),
    status character varying(255) DEFAULT 'open'::character varying NOT NULL,
    reschedule_reason text,
    resolution_notes text,
    resolved_at timestamp(0) without time zone,
    pengajuan_aset_id bigint,
    dispose_aset_id bigint,
    berita_acara_id bigint,
    attachments json,
    created_by bigint,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    room_notes character varying(255),
    CONSTRAINT tickets_category_check CHECK (((category)::text = ANY ((ARRAY['hardware'::character varying, 'software'::character varying, 'network_wifi'::character varying, 'printer_scanner'::character varying, 'access_rights'::character varying, 'scheduled_service'::character varying, 'other'::character varying])::text[]))),
    CONSTRAINT tickets_priority_check CHECK (((priority)::text = ANY ((ARRAY['low'::character varying, 'medium'::character varying, 'high'::character varying, 'critical'::character varying])::text[]))),
    CONSTRAINT tickets_status_check CHECK (((status)::text = ANY ((ARRAY['open'::character varying, 'scheduled'::character varying, 'in_progress'::character varying, 'pending_sparepart'::character varying, 'resolved'::character varying, 'closed'::character varying, 'rescheduled'::character varying])::text[])))
);


ALTER TABLE public.tickets OWNER TO ams_user;

--
-- TOC entry 248 (class 1259 OID 41079)
-- Name: tickets_id_seq; Type: SEQUENCE; Schema: public; Owner: ams_user
--

CREATE SEQUENCE public.tickets_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.tickets_id_seq OWNER TO ams_user;

--
-- TOC entry 5275 (class 0 OID 0)
-- Dependencies: 248
-- Name: tickets_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ams_user
--

ALTER SEQUENCE public.tickets_id_seq OWNED BY public.tickets.id;


--
-- TOC entry 222 (class 1259 OID 16400)
-- Name: users; Type: TABLE; Schema: public; Owner: ams_user
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.users OWNER TO ams_user;

--
-- TOC entry 221 (class 1259 OID 16399)
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: ams_user
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.users_id_seq OWNER TO ams_user;

--
-- TOC entry 5276 (class 0 OID 0)
-- Dependencies: 221
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ams_user
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- TOC entry 4981 (class 2604 OID 41147)
-- Name: asset_maintenance_logs id; Type: DEFAULT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.asset_maintenance_logs ALTER COLUMN id SET DEFAULT nextval('public.asset_maintenance_logs_id_seq'::regclass);


--
-- TOC entry 4949 (class 2604 OID 16535)
-- Name: asset_models id; Type: DEFAULT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.asset_models ALTER COLUMN id SET DEFAULT nextval('public.asset_models_id_seq'::regclass);


--
-- TOC entry 4950 (class 2604 OID 16552)
-- Name: assets id; Type: DEFAULT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.assets ALTER COLUMN id SET DEFAULT nextval('public.assets_id_seq'::regclass);


--
-- TOC entry 4954 (class 2604 OID 40969)
-- Name: berita_acaras id; Type: DEFAULT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.berita_acaras ALTER COLUMN id SET DEFAULT nextval('public.berita_acaras_id_seq'::regclass);


--
-- TOC entry 4946 (class 2604 OID 16511)
-- Name: categories id; Type: DEFAULT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.categories ALTER COLUMN id SET DEFAULT nextval('public.categories_id_seq'::regclass);


--
-- TOC entry 4953 (class 2604 OID 16580)
-- Name: checkouts id; Type: DEFAULT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.checkouts ALTER COLUMN id SET DEFAULT nextval('public.checkouts_id_seq'::regclass);


--
-- TOC entry 4969 (class 2604 OID 41044)
-- Name: dispose_asets id; Type: DEFAULT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.dispose_asets ALTER COLUMN id SET DEFAULT nextval('public.dispose_asets_id_seq'::regclass);


--
-- TOC entry 4944 (class 2604 OID 16491)
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- TOC entry 4943 (class 2604 OID 16461)
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- TOC entry 4948 (class 2604 OID 16524)
-- Name: locations id; Type: DEFAULT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.locations ALTER COLUMN id SET DEFAULT nextval('public.locations_id_seq'::regclass);


--
-- TOC entry 4941 (class 2604 OID 16393)
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- TOC entry 4962 (class 2604 OID 41009)
-- Name: pengajuan_asets id; Type: DEFAULT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.pengajuan_asets ALTER COLUMN id SET DEFAULT nextval('public.pengajuan_asets_id_seq'::regclass);


--
-- TOC entry 4976 (class 2604 OID 41083)
-- Name: tickets id; Type: DEFAULT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.tickets ALTER COLUMN id SET DEFAULT nextval('public.tickets_id_seq'::regclass);


--
-- TOC entry 4942 (class 2604 OID 16403)
-- Name: users id; Type: DEFAULT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- TOC entry 5256 (class 0 OID 41144)
-- Dependencies: 251
-- Data for Name: asset_maintenance_logs; Type: TABLE DATA; Schema: public; Owner: ams_user
--

COPY public.asset_maintenance_logs (id, asset_id, ticket_id, pengajuan_aset_id, dispose_aset_id, berita_acara_id, maintenance_type, title, description, cost, performed_by, performed_at, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 5242 (class 0 OID 16532)
-- Dependencies: 237
-- Data for Name: asset_models; Type: TABLE DATA; Schema: public; Owner: ams_user
--

COPY public.asset_models (id, category_id, name, manufacturer, model_number, created_at, updated_at) FROM stdin;
1	1	Custom PC Unit	Custom	PC-DESKTOP	2026-07-24 03:20:12	2026-07-24 03:20:12
2	3	Monitor Standard	LG / ViewSonic	MON-19	2026-07-24 03:20:12	2026-07-24 03:20:12
3	2	Latitude 5420	Dell	LAT-5420	2026-07-24 03:20:12	2026-07-24 03:20:12
4	5	-	-	-	2026-07-24 03:22:07	2026-07-24 03:22:07
5	5	-	\N	\N	2026-07-24 03:22:24	2026-07-24 03:22:24
6	1	PC Standard Unit	Custom / General	\N	2026-07-24 11:14:52	2026-07-24 11:14:52
7	6	ACER ASPIRE A514-53	Custom / General	\N	2026-07-24 11:33:53	2026-07-24 11:33:53
8	6	REDMIBOOK 15	Custom / General	\N	2026-07-24 11:33:53	2026-07-24 11:33:53
9	6	LENOVO 80XU	Custom / General	\N	2026-07-24 11:33:53	2026-07-24 11:33:53
10	6	LENOVO 81W0	Custom / General	\N	2026-07-24 11:33:53	2026-07-24 11:33:53
11	6	HP 15-BW0X	Custom / General	\N	2026-07-24 11:33:53	2026-07-24 11:33:53
12	6	ACER ASPIRE A314-22	Custom / General	\N	2026-07-24 11:33:53	2026-07-24 11:33:53
13	6	HP STREAM 13	Custom / General	\N	2026-07-24 11:33:53	2026-07-24 11:33:53
14	6	TOSHIBA SATELITE L640	Custom / General	\N	2026-07-24 11:33:53	2026-07-24 11:33:53
15	6	HP 14-BW0XX	Custom / General	\N	2026-07-24 11:33:53	2026-07-24 11:33:53
16	6	ACER ASPIRE A314-32	Custom / General	\N	2026-07-24 11:33:53	2026-07-24 11:33:53
17	6	TOSHIBA	Custom / General	\N	2026-07-24 11:33:53	2026-07-24 11:33:53
18	6	LENOVO 81D5	Custom / General	\N	2026-07-24 11:33:54	2026-07-24 11:33:54
19	6	ACER ASPIRE E5-475	Custom / General	\N	2026-07-24 11:33:54	2026-07-24 11:33:54
20	6	LENOVO 81NB	Custom / General	\N	2026-07-24 11:33:54	2026-07-24 11:33:54
21	6	HP 14S-DQ2XXX	Custom / General	\N	2026-07-24 11:33:54	2026-07-24 11:33:54
22	6	LENOVO 320	Custom / General	\N	2026-07-24 11:33:54	2026-07-24 11:33:54
23	6	HP 14S-DK1XXX	Custom / General	\N	2026-07-24 11:33:54	2026-07-24 11:33:54
24	6	ASUS X409-M409MDA	Custom / General	\N	2026-07-24 11:33:54	2026-07-24 11:33:54
25	6	LENOVO	Custom / General	\N	2026-07-24 11:33:54	2026-07-24 11:33:54
26	6	LENOVO 81NA	Custom / General	\N	2026-07-24 11:33:54	2026-07-24 11:33:54
27	6	ACER ASPIRE E5-476	Custom / General	\N	2026-07-24 11:33:54	2026-07-24 11:33:54
28	6	HP 14-S DK0XXX	Custom / General	\N	2026-07-24 11:33:54	2026-07-24 11:33:54
29	6	DELL	Custom / General	\N	2026-07-24 11:33:54	2026-07-24 11:33:54
30	6	THINKBOOK 20RV	Custom / General	\N	2026-07-24 11:33:54	2026-07-24 11:33:54
31	6	HP 245 G8 NOTEBOOK	Custom / General	\N	2026-07-24 11:33:54	2026-07-24 11:33:54
32	6	LENOVO 82NA	Custom / General	\N	2026-07-24 11:33:54	2026-07-24 11:33:54
33	6	LENOVO 20244	Custom / General	\N	2026-07-24 11:33:54	2026-07-24 11:33:54
34	6	HP 240 G8 NOTEBOOK	Custom / General	\N	2026-07-24 11:33:54	2026-07-24 11:33:54
35	6	TUF GAMNG FX505DD	Custom / General	\N	2026-07-24 11:33:54	2026-07-24 11:33:54
36	6	HP 14-DK1XXX	Custom / General	\N	2026-07-24 11:33:54	2026-07-24 11:33:54
37	6	LENOVO IDEAPAD S145-14AST	Custom / General	\N	2026-07-24 11:33:54	2026-07-24 11:33:54
38	6	ASUS VIVOBOOK X414EA-A1400EA	Custom / General	\N	2026-07-24 11:33:54	2026-07-24 11:33:54
39	6	LAPTOP Standard Unit	Custom / General	\N	2026-07-24 11:33:54	2026-07-24 11:33:54
40	6	ASUS VIVOBOOK X409-M409MDA	Custom / General	\N	2026-07-24 11:33:54	2026-07-24 11:33:54
41	6	HP COMPAQ 510	Custom / General	\N	2026-07-24 11:33:54	2026-07-24 11:33:54
42	6	ACER 4920	Custom / General	\N	2026-07-24 11:33:54	2026-07-24 11:33:54
43	6	TOSHIBA SATELITE A205	Custom / General	\N	2026-07-24 11:33:54	2026-07-24 11:33:54
44	6	ASUS X445LAB	Custom / General	\N	2026-07-24 11:33:54	2026-07-24 11:33:54
45	6	HP COMPAQ NX7400	Custom / General	\N	2026-07-24 11:33:54	2026-07-24 11:33:54
46	6	ACER 4741	Custom / General	\N	2026-07-24 11:33:54	2026-07-24 11:33:54
47	6	THINKPAD T480	Custom / General	\N	2026-07-24 11:33:54	2026-07-24 11:33:54
48	6	DELL 7490	Custom / General	\N	2026-07-24 11:33:54	2026-07-24 11:33:54
49	7	SELULLER Standard Unit	Custom / General	\N	2026-07-24 11:33:54	2026-07-24 11:33:54
50	7	CASHCOW HC-YK937	Custom / General	\N	2026-07-24 11:33:54	2026-07-24 11:33:54
51	8	EPSON L6260 INKJET	Custom / General	\N	2026-07-24 11:33:54	2026-07-24 11:33:54
52	8	EPSON L3250 INKJET	Custom / General	\N	2026-07-24 11:33:54	2026-07-24 11:33:54
53	8	HP M15W	Custom / General	\N	2026-07-24 11:33:54	2026-07-24 11:33:54
54	8	EPSON L1350	Custom / General	\N	2026-07-24 11:33:54	2026-07-24 11:33:54
55	8	HP LASERJET P1102	Custom / General	\N	2026-07-24 11:33:54	2026-07-24 11:33:54
56	8	HP LASER 108W	Custom / General	\N	2026-07-24 11:33:54	2026-07-24 11:33:54
57	8	EPSON LQ-310 DOTMATRIX	Custom / General	\N	2026-07-24 11:33:54	2026-07-24 11:33:54
58	8	EPSON L1250 INKJET	Custom / General	\N	2026-07-24 11:33:54	2026-07-24 11:33:54
59	8	EPSON LQ-300 DOTMATRIX	Custom / General	\N	2026-07-24 11:33:54	2026-07-24 11:33:54
60	8	SAMSUNG ML-1710	Custom / General	\N	2026-07-24 11:33:54	2026-07-24 11:33:54
61	8	CANON PIXMA G3000	Custom / General	\N	2026-07-24 11:33:54	2026-07-24 11:33:54
62	8	CANON PIXMA IP2770	Custom / General	\N	2026-07-24 11:33:54	2026-07-24 11:33:54
63	8	HP DEKSJET F2180	Custom / General	\N	2026-07-24 11:33:54	2026-07-24 11:33:54
64	8	HP DEKSJET 5525	Custom / General	\N	2026-07-24 11:33:54	2026-07-24 11:33:54
65	8	EPSON LX300 DOTMATRIX	Custom / General	\N	2026-07-24 11:33:54	2026-07-24 11:33:54
66	8	BROTHER	Custom / General	\N	2026-07-24 11:33:54	2026-07-24 11:33:54
67	9	SONY ZV-E10	Custom / General	\N	2026-07-24 11:33:54	2026-07-24 11:33:54
68	10	RUIJIE RG-EW1200G PRO	Custom / General	\N	2026-07-24 11:33:54	2026-07-24 11:33:54
69	10	UNIFI AC PRO	Custom / General	\N	2026-07-24 11:33:55	2026-07-24 11:33:55
70	11	RUIJIE REYEE RG-RAP52 OD	Custom / General	\N	2026-07-24 11:33:55	2026-07-24 11:33:55
71	10	MIKROTIK ROUTER OS	Custom / General	\N	2026-07-24 11:33:55	2026-07-24 11:33:55
72	10	RUIJIE RG-EW300 PRO	Custom / General	\N	2026-07-24 11:33:55	2026-07-24 11:33:55
73	10	MIKROTIK RB730GR3	Custom / General	\N	2026-07-24 11:33:55	2026-07-24 11:33:55
74	12	SWITCH 24 PORTS	Custom / General	\N	2026-07-24 11:33:55	2026-07-24 11:33:55
75	13	TP LINK 8 PORTS	Custom / General	\N	2026-07-24 11:33:55	2026-07-24 11:33:55
76	13	TP LINK 16 PORTS	Custom / General	\N	2026-07-24 11:33:55	2026-07-24 11:33:55
77	13	D LINK 8 PORTS	Custom / General	\N	2026-07-24 11:33:55	2026-07-24 11:33:55
78	13	MERCURYS 8 PORTS	Custom / General	\N	2026-07-24 11:33:55	2026-07-24 11:33:55
79	13	D-LINK 16 PORTS	Custom / General	\N	2026-07-24 11:33:55	2026-07-24 11:33:55
80	13	RUIJIE EG-ES108GD 8 PORTS	Custom / General	\N	2026-07-24 11:33:55	2026-07-24 11:33:55
81	1	PC Desktop	Cusome	\N	2026-07-27 14:29:02	2026-07-27 14:29:02
\.


--
-- TOC entry 5244 (class 0 OID 16549)
-- Dependencies: 239
-- Data for Name: assets; Type: TABLE DATA; Schema: public; Owner: ams_user
--

COPY public.assets (id, asset_tag, serial, asset_model_id, location_id, status, purchase_date, purchase_cost, notes, created_at, updated_at, room, department, primary_user, secondary_user, processor, ram, storage_hdd, storage_ssd, vga_card, monitor_id, monitor_spec, condition, purchase_year, warranty, attachments, operating_system) FROM stdin;
8	GTK-01-01-01	00326-10000-00000-AA633	6	1	checked_out	\N	\N	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	\N	PPIC	DEWI ELIYANTI	AYU	I3-GEN 10	8 GB	-	256 GB	NVIDIA GEFORCE GT 610	GTK-M-01-01-01	VIEWSONIC 19"	bagus	2020	\N	\N	\N
9	GTK-01-01-02	-	6	2	checked_out	\N	\N	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	\N	FINANCE & ACCOUNTING	YENNY TJAHYADI	ANINGTYAS	I3-GEN 2	8 GB	-	128 GB	-	GTK-M-01-01-02	HP 19"	bagus	2011	\N	\N	\N
10	GTK-01-01-03	-	6	1	checked_out	\N	\N	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	\N	PURCHASING	YUNI SAMSUDIN	\N	INTEL PENTIUM	2 GB	500 GB	-	-	GTK-M-01-01-03	LG 14"	bagus	2013	\N	\N	\N
11	GTK-01-01-04	-	6	1	checked_out	\N	\N	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	\N	PURCHASING	GABI	REFURBISH	I3-GEN 3	10 GB	150 GB	-	-	GTK-M-01-01-04	LG 14"	bagus	2012	\N	\N	\N
12	GTK-01-01-05	-	6	1	checked_out	\N	\N	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	\N	HCD	NURUL AZIZAH F	SRI LESTARI	I3-GEN 3	2 GB	256 GB	-	-	GTK-M-01-01-05	LG 14"	bagus	2012	\N	\N	\N
13	GTK-01-01-06	-	6	1	checked_out	\N	\N	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	\N	FINANCE & ACCOUNTING	USWATI HASANAH	\N	INTEL PENTIUM	2 GB	500 GB	-	-	GTK-M-01-01-06	LG 20"	bagus	2013	\N	\N	\N
7	GTK-01-01-07	-	6	1	checked_out	\N	\N	\N	2026-07-24 11:14:52	2026-07-24 11:33:53	\N	PRODUKSI	SEPTIANY	BERLIANA RIZKA R	I3-GEN 3	4 GB	150 GB	128 GB	-	GTK-M-01-01-07	LG 14"	bagus	2012	\N	\N	\N
14	GTK-01-01-08	-	6	1	in_stock	\N	\N	Prioritas: P1	2026-07-24 11:33:53	2026-07-24 11:33:53	\N	PRODUKSI	\N	GAMMI PUSPITA E	I3-GEN 2	8 GB	70 GB	-	-	GTK-M-01-01-08	LG 14"	tidak display	2011	\N	\N	\N
15	GTK-01-01-09	-	6	1	checked_out	\N	\N	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	\N	RMPM	AHMAD HERMANSYAH	\N	INTEL PENTIUM	8 GB	-	128 GB	-	GTK-M-01-01-09	LG 14"	bagus	2013	\N	\N	\N
16	GTK-01-01-10	-	6	1	checked_out	\N	\N	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	\N	RMPM	ROHIMAN	\N	INTEL PENTIUM	8 GB	-	128 GB	-	GTK-M-01-01-10	LG 14"	bagus	2013	\N	\N	\N
17	GTK-01-01-11	-	6	1	checked_out	\N	\N	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	\N	RMPM	HARIAN	RISSA ILLA S	INTEL PENTIUM	4 GB	-	-	-	GTK-M-01-01-11	LG 14"	bagus	2013	\N	\N	\N
18	GTK-01-01-12	-	6	1	checked_out	\N	\N	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	\N	FINISH GOODS	INTAN NURAENI	\N	I3-GEN 10	8 GB	-	256 GB	-	GTK-M-01-01-12	AOC 20"	bagus	2020	\N	\N	\N
19	GTK-01-01-13	-	6	1	checked_out	\N	\N	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	\N	RMPM	DITA	AHMAD S	INTEL PENTIUM	8 GB	1000 GB	128 GB	-	GTK-M-01-01-13	ACER 14"	bagus	2013	\N	\N	\N
20	GTK-01-01-14	-	6	1	checked_out	\N	\N	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	\N	FINISH GOODS	ABUNATA	\N	I3-GEN 10	8 GB	-	256 GB	-	GTK-M-01-01-14	LG 14"	bagus	2020	\N	\N	\N
21	GTK-01-01-15	-	6	1	checked_out	\N	\N	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	\N	QUALITY CONTROL	JANNY AISYAH P	\N	I3-GEN 2	8 GB	-	128 GB	-	GTK-M-01-01-15	LG 14"	bagus	2011	\N	\N	\N
22	GTK-01-01-16	-	6	1	in_stock	\N	\N	Prioritas: P1	2026-07-24 11:33:53	2026-07-24 11:33:53	\N	QUALITY CONTROL	\N	NURUL FADILLAH	I3-GEN 2	2 GB	150 GB	-	-	GTK-M-01-01-16	LG 14"	bagus	2011	\N	\N	\N
23	GTK-01-01-17	-	6	1	checked_out	\N	\N	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	\N	QUALITY CONTROL	NISSA	PUTRI INTAN NADIRA	I3-GEN 10	8 GB	-	256 GB	-	GTK-M-01-01-17	LG 14"	bagus	2020	\N	\N	\N
24	GTK-01-01-18	-	6	1	checked_out	\N	\N	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	\N	QUALITY ASSURANCE	METRIZAL BUCHARI	\N	I3-GEN 2	8 GB	-	128 GB	-	GTK-M-01-01-18	LG 14"	bagus	2011	\N	\N	\N
25	GTK-01-01-19	-	6	1	in_stock	\N	\N	UNIT SISA CASING | Prioritas: P1	2026-07-24 11:33:53	2026-07-24 11:33:53	\N	-	\N	\N	I3-GEN 3	2 GB	-	-	-	GTK-M-01-01-19	\N	mati total	2012	\N	\N	\N
26	GTK-01-01-20	-	6	2	checked_out	\N	\N	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	\N	PRODUKSI	HARI	\N	I3-GEN 3	2 GB	150 GB	-	-	GTK-M-01-01-20	LG 14"	bagus	2012	\N	\N	\N
27	GTK-01-01-21	-	6	1	checked_out	\N	\N	UNTUK ERP & ZIMBRA	2026-07-24 11:33:53	2026-07-24 11:33:53	\N	INFORMATION & TECHNOLOGY	DIVISI INFORMATION & TECHNOLOGY	\N	XEON E3-1240V2	24 GB	3 TB	-	-	-	-	bagus	2012	\N	\N	\N
28	GTK-01-01-22	-	6	1	checked_out	\N	\N	UNTUK DATA CENTER	2026-07-24 11:33:53	2026-07-24 11:33:53	\N	INFORMATION & TECHNOLOGY	DIVISI INFORMATION & TECHNOLOGY	\N	I5-GEN 10	16 GB	-	-	-	-	-	bagus	2015	\N	\N	\N
29	GTK-01-03-01	NXHZ6SN002043097E12N00	7	2	checked_out	\N	\N	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	\N	BUSINESS DEVELOPMENT	JESSICA CLAUDIA	IMAM FAISAL	I3-GEN 10	8 GB	-	512 GB	-	-	-	bagus	2020	\N	\N	\N
30	GTK-01-03-02	-	8	1	checked_out	\N	\N	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	\N	HCD	LIDWINA DIAH P	\N	I3-GEN 11	8 GB	-	256 GB	-	-	-	bagus	2021	\N	\N	\N
31	GTK-01-03-03	-	9	2	in_stock	\N	\N	UNIT KEADAAN MATI TOTAL | Prioritas: P1	2026-07-24 11:33:53	2026-07-24 11:33:53	\N	INFORMATION & TECHNOLOGY	\N	ADAM HAKULYAKIN	AMD-A9	4 GB	1000 GB	-	-	-	-	mati total	2016	\N	\N	\N
32	GTK-01-03-04	-	10	1	checked_out	\N	\N	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	\N	HCD	DIANA SWY HASNIKA	ADEL	AMD RYZEN 3	4 GB	-	256 GB	-	-	-	bagus	2017	\N	\N	\N
33	GTK-01-03-05	-	11	2	in_stock	\N	\N	CASING RUSAK | LAPTOP LEMOT | Prioritas: P1	2026-07-24 11:33:53	2026-07-24 11:33:53	\N	INFORMATION & TECHNOLOGY	\N	YUDHA PRASETYO	AMD-A10	4 GB	1000 GB	-	-	-	-	casing rusak	2014	\N	\N	\N
34	GTK-01-03-06	-	12	1	checked_out	\N	\N	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	\N	HCD	YUHENDI	\N	AMD RYZEN 3	12 GB	-	256 GB	-	-	-	bagus	2017	\N	\N	\N
35	GTK-01-03-07	-	13	1	checked_out	\N	\N	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	\N	PURCHASING	YUNI SAMSUDIN	\N	INTEL CELERON	2 GB	-	32 GB	-	-	-	kurang optimal	2020	\N	\N	\N
36	GTK-01-03-08	-	12	1	checked_out	\N	\N	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	\N	FACTORY GOODS	DIMAS PRAYOGA W	\N	INTEL CELERON	4 GB	-	256 GB	-	-	-	bagus	2020	\N	\N	\N
37	GTK-01-03-09	-	14	1	checked_out	\N	\N	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	\N	PRODUKSI	HARIAN	\N	I3-GEN 6	6 GB	300 GB	-	-	-	-	kurang optimal	2015	\N	\N	\N
38	GTK-01-03-10	-	7	2	checked_out	\N	\N	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	\N	MARKETING	RIYATUL	NURUL	I3-GEN 10	4 GB	-	256 GB	-	-	-	bagus	2020	\N	\N	\N
39	GTK-01-03-11	-	13	1	in_stock	\N	\N	UNIT KEADAAN MATI TOTAL | Prioritas: P1	2026-07-24 11:33:53	2026-07-24 11:33:53	\N	INFORMATION & TECHNOLOGY	\N	HARIAN	INTEL CELERON	2 GB	-	32 GB	-	-	-	mati total	2020	\N	\N	\N
40	GTK-01-03-12	-	13	1	checked_out	\N	\N	LCD TOMPEL	2026-07-24 11:33:53	2026-07-24 11:33:53	\N	INFORMATION & TECHNOLOGY	\N	HARIAN	INTEL CELERON	2 GB	-	32 GB	-	-	-	kurang optimal	2020	\N	\N	\N
41	GTK-01-03-13	-	8	1	in_stock	\N	\N	HILANG SAAT MAKAN MALAM, SUDAH LAPOR POLISI LP/B/104/II/2026 PADA TANGGAL 09/02/2026	2026-07-24 11:33:53	2026-07-24 11:33:53	\N	PRODUKSI	\N	MANDA FERRY LAVERIUS	I3-GEN 11	8 GB	-	256 GB	-	-	-	tidak diketahui	2021	\N	\N	\N
42	GTK-01-03-14	-	15	1	checked_out	\N	\N	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	\N	MAINTENANCE	DIDIK	WAHYU DWI S	AMD-A9	8 GB	-	256 GB	-	-	-	bagus	2016	\N	\N	\N
43	GTK-01-03-15	-	16	1	checked_out	\N	\N	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	\N	RMPM	REZA RIZKY PERMADI	DEO	INTEL CELERON	12 GB	-	256 GB	-	-	-	bagus	2020	\N	\N	\N
44	GTK-01-03-16	-	17	1	checked_out	\N	\N	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	\N	FACTORY GOODS	OPI		I3-GEN 6	4 GB	150 GB	-	-	-	-	bagus	2015	\N	\N	\N
45	GTK-01-03-17	-	13	1	checked_out	\N	\N	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	\N	INFORMATION & TECHNOLOGY	\N	HARIAN	INTEL CELERON	2 GB	-	32 GB	-	-	-	kurang optimal	2020	\N	\N	\N
46	GTK-02-01-01	00331-10000-00001-AA624	6	2	checked_out	\N	\N	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	\N	SALES	ANISAH	\N	I3-GEN 10	8 GB	-	512 GB	-	GTK-M-02-01-01	MAGIX 19"	bagus	2020	\N	\N	\N
47	GTK-02-01-02	00330-80000-00000-AA427	6	2	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	FINANCE & ACCOUNTING	MIRZA NOVIYANTI	EDI PURNOMO	I5-GEN 2	6 GB	500 GB	128 GB	-	GTK-M-02-01-02	LG 14"	bagus	2011	\N	\N	\N
48	GTK-02-01-03	00330-80000-00000-AA622	6	2	checked_out	\N	\N	PENAMBAHAN RAM	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	FINANCE & ACCOUNTING	MARISSA SHANTI	GABI KANIA	I5-GEN 3	4 GB	2000 GB	128 GB	-	GTK-M-02-01-03	DA 20"	kurang optimal	2013	\N	\N	\N
49	GTK-02-01-04	00330-80000-00000-AA505	6	2	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	FINANCE & ACCOUNTING	FATIMAH AZZAHRA	\N	I3-GEN 10	8 GB	150 GB	256 GB	NVIDIA GEFORCE GT 730	GTK-M-02-01-04	LG 14"	bagus	2020	\N	\N	\N
50	GTK-02-01-05	00328-10000-00001-AA164	6	2	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	FINANCE & ACCOUNTING	HANIFA RIFQAH	ERLIZA PUTRI U	I3-GEN 10	8 GB	500 GB	256 GB	NVIDIA GEFORCE GT 730	GTK-M-02-01-05	LG 14"	bagus	2020	\N	\N	\N
51	GTK-02-01-06	00326-30000-00000-AA443	6	2	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	FINANCE & ACCOUNTING	\N	NUR	I3-GEN 10	8 GB	-	256 GB	NVIDIA GEFORCE GT 730	GTK-M-02-01-06	LG 14"	bagus	2020	\N	\N	\N
52	GTK-02-01-07	-	6	2	checked_out	\N	\N	PENAMBAHAN SSD	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	FINANCE & ACCOUNTING	JESSICA NOVA SARI	\N	I5-GEN 3	6 GB	160 GB	-	-	GTK-M-02-01-07	HP 19"	kurang optimal	2013	\N	\N	\N
53	GTK-02-01-08	00331-10000-00001-AA578	6	2	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	FINANCE & ACCOUNTING	DHIAYUN NAQIYAH	GABI KANIA	I3-GEN 3	10 GB	-	128 GB	-	GTK-M-02-01-08	SPC 20"	bagus	2012	\N	\N	\N
54	GTK-02-01-09	00330-80000-00000-AA091	6	2	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	FINANCE & ACCOUNTING	SEBASTIANA A	OLGA	I3-GEN 10	8 GB	-	128 GB	NVIDIA GEFORCE GT 730	GTK-M-02-01-09	MAGIX 20"	bagus	2020	\N	\N	\N
55	GTK-02-01-10	00331-10000-00001-AA369	6	2	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	FINANCE & ACCOUNTING	RIZKA RAHMAWATI	NILAM	I3-GEN 2	6 GB	-	128 GB	-	GTK-M-02-01-10	SAMSUNG 14"	bagus	2012	\N	\N	\N
56	GTK-02-01-11	00330-80000-00000-AA172	6	2	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	FINANCE & ACCOUNTING	HANNA	ATIQAH	I5-GEN 2	8 GB	-	128 GB	-	GTK-M-02-01-11	HP 19"	bagus	2011	\N	\N	\N
57	GTK-02-01-12	-	6	2	in_stock	\N	\N	PROC OVERHEAT + MATI TOTAL | Prioritas: P1	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	FINANCE & ACCOUNTING	\N	YENNY TJAHYADI	I5-GEN 2	-	250 GB	-	-	-	-	rusak	2011	\N	\N	\N
58	GTK-02-01-13	-	6	3	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	MARKETING	RAKA JENTA	\N	I7-GEN 9	16 GB	512 GB	256 GB	\N	GTK-M-02-01-13	VIEWSONIC 19"	bagus	2019	\N	\N	\N
59	GTK-02-01-14	00330-80000-00000-AA826	6	2	checked_out	\N	\N	PENGGANTIAN VGA JAN 2026	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	MARKETING	UMAR FATIH	\N	I7-GEN 10	16 GB	2000 GB	512 GB	NVIDIA GEFORCE RTX 3050	GTK-M-02-01-14	AOC 20"	bagus	2020	\N	\N	\N
60	GTK-02-01-15	00331-10000-00001-AA692	6	2	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	MARKETING	ZIYAH PARAMITA S	\N	I7-GEN 4	16 GB	6000 GB	512 GB	NVIDIA GEFORCE GTX 1050 TI	GTK-M-02-01-15	LG 22"	bagus	2014	\N	\N	\N
61	GTK-02-01-16	-	6	3	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	DIGITAL MARKETING	RIZKY METIADI	RIANTO	AMD RYZEN 5	16 GB	1000 GB	256 GB	NVIDIA GEFORCE GTX 1660 VENTUS	GTK-M-02-01-16	LG 14"	bagus	2021	\N	\N	\N
62	GTK-02-01-17	-	6	2	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	MARKETING	MARIA M R	AMESYA HANAN S	I5-GEN 8	16 GB	4000 GB	512 GB	\N	GTK-M-02-01-17	LG 19"	bagus	2018	\N	\N	\N
63	GTK-02-01-18	00330-80000-00000-AA704	6	2	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	MARKETING	ALDILA WIDYA P	\N	I3-GEN 10	8 GB	-	256 GB	NVIDIA GEFORCE GT 610	GTK-M-02-01-18	LG 14"	bagus	2020	\N	\N	\N
64	GTK-02-01-19	-	6	2	in_stock	\N	\N	DIJUAL KEPADA ELVINA COMP NUSANTARA TANGGAL 18/02/2026	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	INFORMATION & TECHNOLOGY	\N	BAMBANG YULIANTO	-	-	-	-	\N	-	\N	rusak	2012	\N	\N	\N
65	GTK-02-01-20	-	6	2	in_stock	\N	\N	UNIT SISA CASING + MOTHERBOARD | Prioritas: P1	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	INFORMATION & TECHNOLOGY	\N	\N	-	-	-	-	\N	-	\N	rusak	2012	\N	\N	\N
66	GTK-02-01-21	-	6	2	in_stock	\N	\N	DIJUAL KEPADA ELVINA COMP NUSANTARA TANGGAL 18/02/2026	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	INFORMATION & TECHNOLOGY	\N	ZIYAH PARAMITA S	-	-	-	-	\N	-	\N	rusak berat	2012	\N	\N	\N
67	GTK-02-01-22	-	6	1	in_stock	\N	\N	UNIT SISA CASING + MOTHERBOARD | Prioritas: P1	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	INFORMATION & TECHNOLOGY	\N	\N	I3-GEN 3	-	-	-	\N	-	\N	rusak	2012	\N	\N	\N
68	GTK-02-01-23	00330-80000-00000-AA284	6	2	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	RESEARCH N DEVELOPMENT	HARPAH NOVIANI	\N	I5-GEN 2	8 GB	300 GB	128 GB	\N	GTK-M-02-01-23	LG 14"	bagus	2011	\N	\N	\N
69	GTK-02-01-24	-	6	2	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	HCD	SRI LESTARI	IDA MARSELA	I5-GEN 2	8 GB	150 GB	-	\N	GTK-M-02-01-24	LG 14"	bagus	2011	\N	\N	\N
70	GTK-02-01-25	00331-10000-00001-AA177	6	2	checked_out	\N	2600000.00	BULAN MEI | Vendor: JOS COM (HARCO)	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	SALES	VANIA A W	\N	I3-GEN 6	8 GB	-	128 GB	\N	GTK-M-02-01-25	LENOVO 22"	bagus	2026	01/06/26	\N	\N
71	GTK-02-01-26	-	6	2	checked_out	\N	2600000.00	BULAN MEI | Vendor: JOS COM (HARCO)	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	INFORMATION & TECHNOLOGY	IT	\N	I3-GEN 6	8 GB	-	128 GB	\N	GTK-M-02-01-26	VIEWSONIC 19"	bagus	2026	01/06/26	\N	\N
72	GTK-02-01-27	-	6	1	checked_out	\N	2600000.00	BULAN MEI | Vendor: JOS COM (HARCO)	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	QUALITY CONTROL	EDITH	\N	I3-GEN 6	8 GB	-	128 GB	\N	GTK-M-02-01-27	SPC 20"	bagus	2026	01/06/26	\N	\N
73	GTK-02-03-01	00331-10000-00001-AA402	18	2	checked_out	\N	\N	PENAMBAHAN SSD, RAM + GANTI BATERAI	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	RESEARCH N DEVELOPMENT	BAYU ANDI S	MITA	AMD-A9	8 GB	512 GB	-	-	-	-	kurang optimal	2016	\N	\N	\N
74	GTK-02-03-02	NXGCUSN0037290BA057600	19	2	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	RESEARCH N DEVELOPMENT	TASIYA	\N	I3-GEN 6	8 GB	-	512 GB	-	-	-	bagus	2017	\N	\N	\N
75	GTK-02-03-03	-	20	2	checked_out	\N	\N	KAMERA TIDAK TERDETEKSI	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	SALES	IMAX	FARADILLA PUTRI M	AMD RYZEN 3	8 GB	-	512 GB	\N	-	-	bagus	2017	\N	\N	\N
76	GTK-02-03-04	MP1D16H8	20	2	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	MARKETING	GLENN GOUWARDI	ARDIANSYAH	I5-GEN 8	8 GB	-	512 GB	\N	-	-	bagus	2018	\N	\N	\N
77	GTK-02-03-05	-	18	2	in_stock	\N	\N	UNIT KEADAAN MATI TOTAL | Prioritas: P1	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	INFORMATION & TECHNOLOGY	\N	FALYA PRATIWI	AMD-A9	8 GB	1000 GB	-	\N	-	-	mati total	2016	\N	\N	\N
78	GTK-02-03-06	-	21	2	in_stock	\N	\N	CASE = KEBANJIRAN DI RUMAH | Prioritas: P1	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	INFORMATION & TECHNOLOGY	\N	AYU PRASETYA	INTEL CELERON	12 GB	-	-	\N	-	-	mati total	2020	\N	\N	\N
79	GTK-02-03-07	-	22	1	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	PPIC	DEWI ELIYANTI	RATU NADHIFA K	I3-GEN 6	8 GB	-	128 GB	\N	-	-	bagus	2017	\N	\N	\N
80	GTK-02-03-08	-	21	2	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	MARKETING	ADINDA FATIMAH A	IHSAN GUSWANSYAH	INTEL CELERON	8 GB	-	512 GB	\N	-	-	bagus	2020	\N	\N	\N
81	GTK-02-03-09	-	23	2	checked_out	\N	\N	TRACKPAD TIDAK BISA	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	BUSINESS DEVELOPMENT	ANINGTYAS	DEWI ELIYANTI	AMD ATHLON SILVER	6 GB	-	256 GB	\N	-	-	bagus	2021	\N	\N	\N
82	GTK-02-03-10	-	21	2	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	MARKETING	WINDA PUTRI D	ANISA MAULIDA	I3-GEN 11	8 GB	-	256 GB	\N	-	-	bagus	2021	\N	\N	\N
83	GTK-02-03-11	-	24	4	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	DIGITAL MARKETING	EKO	ANISA NURUL IZZA	AMD ATHLON GOLD	4 GB	-	512 GB	\N	-	-	bagus	2020	\N	\N	\N
84	GTK-02-03-12	-	21	2	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	MARKETING	GUSTI RISTA Y	AUDREY GRISEILA	I3-GEN 11	8 GB	-	256 GB	\N	-	-	bagus	2021	\N	\N	\N
85	GTK-02-03-13	-	21	2	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	BUSINESS DEVELOPMENT	BADZLINA TSAABITAH R	TSALSABILA	INTEL CELERON	8 GB	-	512 GB	\N	-	-	bagus	2020	\N	\N	\N
86	GTK-02-03-14	-	21	2	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	BUSINESS DEVELOPMENT	REYNARD DZAKY N	BADZLINA TSAABITAH R	INTEL CELERON	8 GB	-	512 GB	\N	-	-	bagus	2020	\N	\N	\N
87	GTK-02-03-15	-	21	2	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	BUSINESS DEVELOPMENT	AIDAH NURUL H	ANGEL	INTEL CELERON	8 GB	-	512 GB	\N	-	-	bagus	2020	\N	\N	\N
88	GTK-02-03-16	-	12	2	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	FINANCE & ACCOUNTING	GABI KANIA	EDI PURNOMO	AMD RYZEN 3	10 GB	-	256 GB	\N	-	-	bagus	2017	\N	\N	\N
89	GTK-02-03-17	-	18	1	checked_out	\N	\N	PENGGANTIAN PALMREST	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	HCD	DIKDIK IRAWAN	DANANG	AMD-A9	8 GB	-	512 GB	\N	-	-	kurang optimal	2016	\N	\N	\N
90	GTK-02-03-18	-	25	2	in_stock	\N	\N	Prioritas: P1	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	INFORMATION & TECHNOLOGY	\N	\N	-	-	-	-	\N	-	-	mati total	\N	\N	\N	\N
91	GTK-02-03-19	-	23	2	checked_out	\N	\N	PENAMBAHAN RAM	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	HCD	FEBRI SANTOSO	\N	AMD RYZEN 3	4 GB	-	256 GB	\N	-	-	kurang optimal	2017	\N	\N	\N
92	GTK-02-03-20	-	26	2	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	HCD	SAYYIDAH BALQIS	\N	I3-GEN 10	8 GB	-	256 GB	\N	-	-	bagus	2020	\N	\N	\N
93	GTK-02-03-21	NXGCUSN003731035837600	27	2	checked_out	\N	\N	SEMENTARA DIPAKAI NESHA	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	INFORMATION & TECHNOLOGY	NESHA	FAUZAN	I3-GEN 6	8 GB	-	128 GB	-	-	-	bagus	2017	\N	\N	\N
94	GTK-02-03-22	PF17F21V	18	2	checked_out	\N	\N	PENAMBAHAN SSD	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	SALES	LAURA MALAU	\N	AMD-A9	8 GB	1000 GB	-	-	-	-	kurang optimal	2016	\N	\N	\N
95	GTK-02-03-23	35519/31VA00829	8	2	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	SALES	SETIOWATI	JULIANTO PRAJA	I3-GEN 11	8 GB	-	256 GB	-	-	-	bagus	2021	\N	\N	\N
96	GTK-02-03-24	-	28	2	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	INFORMATION & TECHNOLOGY	BAMBANG YULIANTO	\N	I3-GEN 11	6 GB	-	512 GB	-	-	-	bagus	2021	\N	\N	\N
97	GTK-02-03-25	-	29	2	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	SALES	MUHAMMAD IDHAM	\N	N/A	N/A	N/A	N/A	-	-	-	bagus	\N	\N	\N	\N
99	GTK-02-03-27	-	30	2	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	SALES	RATNO TIMOR	\N	N/A	N/A	N/A	N/A	-	-	-	bagus	\N	\N	\N	\N
100	GTK-02-03-28	5CG042C2BX	23	3	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	DIGITAL MARKETING	CANTIKA	NADA DEWANDA	AMD RYZEN 3	8 GB	1000 GB	256 GB	-	-	-	bagus	2017	\N	\N	\N
102	GTK-02-03-30	-	31	3	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	DIGITAL MARKETING	HASAN JUNAIDI	RIEZKI ANDITYA	AMD RYZEN 5	8 GB	-	512 GB	-	-	-	bagus	2017	\N	\N	\N
103	GTK-02-03-31	-	23	4	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	DIGITAL MARKETING	BARIQ NABIL R	HENDRA KURNIAWAN	AMD RYZEN 3	8 GB	-	256 GB	-	-	-	bagus	2017	\N	\N	\N
104	GTK-02-03-32	5CG2180CBX	31	2	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	SALES	HANTI SUPARMI	EDY CHANDRA	AMD RYZEN 5	8 GB	-	512 GB	-	-	-	bagus	2017	\N	\N	\N
105	GTK-02-03-33	-	32	2	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	SALES	OKI SUSANTO	YANTO	I3-GEN 10	8 GB	-	256 GB	-	-	-	bagus	2020	\N	\N	\N
106	GTK-02-03-34	3.29136E+12	33	2	in_stock	\N	\N	DIJUAL KEPADA ELVINA COMP NUSANTARA TANGGAL 18/02/2026	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	SALES	\N	EKA	I3-GEN 3	4 GB	1000 GB	-	-	-	-	rusak berat	2012	\N	\N	\N
107	GTK-02-03-35	35519/31WQ00277	8	2	in_stock	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	INFORMATION & TECHNOLOGY	\N	ELA	I3-GEN 11	8 GB	-	256 GB	-	-	-	bagus	2021	\N	\N	\N
108	GTK-02-03-36	-	8	2	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	HCD	HR KARSA	LITANIA	I3-GEN 11	8 GB	-	256 GB	-	-	-	bagus	2021	\N	\N	\N
109	GTK-02-03-37	-	34	2	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	FINANCE & ACCOUNTING	SETYADI CANDRAWINATA	\N	I3-GEN 11	12 GB	-	256 GB	-	-	-	bagus	2021	\N	\N	\N
110	GTK-02-03-38	KBNRCV01V644466	35	3	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	MARKETING	RIANTO	INTAN PRATIWI	AMD RYZEN 3	16 GB	-	512 GB	-	-	-	bagus	2017	\N	\N	\N
111	GTK-02-03-39	-	18	2	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	SALES	GEAN GHOFARIO	JUAN CHRISTIAN	AMD-A9	8 GB	1000 GB	128 GB	-	-	-	bagus	2016	\N	\N	\N
112	GTK-02-03-40	5CG136C3W2	34	2	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	SALES	ANISAH	TAMI	I3-GEN 11	8 GB	-	256 GB	-	-	-	bagus	2021	\N	\N	\N
114	GTK-02-03-42	-	37	2	checked_out	\N	\N	PENAMBAHAN SSD	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	HCD	NESHA	\N	AMD-A9	8 GB	1000 GB	-	-	-	-	kurang optimal	2016	\N	\N	\N
115	GTK-02-03-43	-	38	3	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	DIGITAL MARKETING	SALFA SALSABILA M	CITRA	I3-GEN 11	8 GB	-	256 GB	-	-	-	bagus	2021	\N	\N	\N
116	GTK-02-03-44	-	39	2	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	INFORMATION & TECHNOLOGY	\N	SETYADI CANDRAWINATA	-	-	-	-	-	-	-	bagus	\N	\N	\N	\N
117	GTK-02-03-45	L4N0CV13541117D	40	2	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	SALES	FAUZAN HIDAYAT	IHSAN GUSWANSYAH	AMD ATHLON GOLD	-	-	512 GB	-	-	-	bagus	2020	\N	\N	\N
118	GTK-02-03-46	-	41	2	in_stock	\N	\N	DIJUAL KEPADA ELVINA COMP NUSANTARA TANGGAL 18/02/2026	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	INFORMATION & TECHNOLOGY	\N	\N	-	-	-	-	-	-	-	rusak berat	2007	\N	\N	\N
119	GTK-02-03-47	-	42	2	in_stock	\N	\N	DIJUAL KEPADA ELVINA COMP NUSANTARA TANGGAL 18/02/2026	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	INFORMATION & TECHNOLOGY	\N	\N	-	-	-	-	-	-	-	rusak berat	2015	\N	\N	\N
120	GTK-02-03-48	-	43	2	in_stock	\N	\N	DIJUAL KEPADA ELVINA COMP NUSANTARA TANGGAL 18/02/2026	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	INFORMATION & TECHNOLOGY	\N	\N	-	-	-	-	-	-	-	rusak berat	2007	\N	\N	\N
121	GTK-02-03-49	-	44	2	in_stock	\N	\N	DIJUAL KEPADA ELVINA COMP NUSANTARA TANGGAL 18/02/2026	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	INFORMATION & TECHNOLOGY	\N	\N	-	-	-	-	-	-	-	rusak berat	2010	\N	\N	\N
122	GTK-02-03-50	-	45	2	in_stock	\N	\N	DIJUAL KEPADA ELVINA COMP NUSANTARA TANGGAL 18/02/2026	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	INFORMATION & TECHNOLOGY	\N	\N	-	-	-	-	-	-	-	rusak berat	2009	\N	\N	\N
101	GTK-02-03-29	37663/31ZT00500	8	2	in_stock	\N	\N	GANTI SSD - CORRUPT	2026-07-24 11:33:54	2026-07-29 11:54:51	\N	SALES	\N	\N	I3-GEN 11	8 GB	-	256 GB	-	-	-	bagus	2021	\N	[]	\N
98	GTK-02-03-26	37663/31Z00330	8	2	in_stock	\N	\N	\N	2026-07-24 11:33:54	2026-07-29 13:30:32	\N	SALES	\N	\N	I3-GEN 11	8 GB	-	256 GB	-	-	-	bagus	2021	\N	\N	\N
123	GTK-02-03-51	-	41	2	in_stock	\N	\N	DIJUAL KEPADA ELVINA COMP NUSANTARA TANGGAL 18/02/2026	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	INFORMATION & TECHNOLOGY	\N	\N	-	-	-	-	-	-	-	rusak berat	2010	\N	\N	\N
124	GTK-02-03-52	-	33	1	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	QUALITY ASSURANCE	HARIAN	\N	I3-GEN 3	2 GB	1000 GB	-	-	-	-	kurang optimal	2012	\N	\N	\N
125	GTK-02-03-53	-	46	2	in_stock	\N	\N	DIJUAL KEPADA ELVINA COMP NUSANTARA TANGGAL 18/02/2026	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	INFORMATION & TECHNOLOGY	\N	\N	-	-	-	-	-	-	-	rusak berat	2014	\N	\N	\N
126	GTK-02-03-54	-	13	2	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	MARKETING	VERDYANSYAH	\N	INTEL CELERON	2 GB	-	32 GB	-	-	-	-	2020	\N	\N	\N
127	GTK-02-03-55	-	13	2	in_stock	\N	\N	UNIT KEADAAN MATI TOTAL | Prioritas: P1	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	INFORMATION & TECHNOLOGY	\N	\N	INTEL CELERON	2 GB	-	32 GB	-	-	-	mati total	2020	\N	\N	\N
128	GTK-02-03-56	-	41	2	in_stock	\N	\N	DIJUAL KEPADA ELVINA COMP NUSANTARA TANGGAL 18/02/2026	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	INFORMATION & TECHNOLOGY	\N	\N	-	-	-	-	-	-	-	rusak berat	2010	\N	\N	\N
129	GTK-02-03-57	-	33	2	in_stock	\N	\N	DIJUAL KEPADA ELVINA COMP NUSANTARA TANGGAL 18/02/2026	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	INFORMATION & TECHNOLOGY	\N	\N	-	-	-	-	-	-	-	rusak berat	2012	\N	\N	\N
130	GTK-02-03-58	-	47	2	checked_out	\N	2750000.00	- | Vendor: SPARTA COMPUTINDO	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	SALES	CHAHYONO	\N	I5-GEN 8	8 GB	-	256 GB	-	-	-	bagus	2025	\N	\N	\N
131	GTK-02-03-59	5004JR2	48	2	in_stock	\N	2750000.00	- | Vendor: SPARTA COMPUTINDO	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	INFORMATION & TECHNOLOGY	\N	HAIDY	I5-GEN 8	8 GB	-	256 GB	-	-	-	bagus	2025	\N	\N	\N
132	GTK-02-03-60	PF1LJ46V	47	2	checked_out	\N	2787162.00	- | Vendor: ALPRASOFT	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	FINANCE & ACCOUNTING	EDI PURNOMO	\N	I5-GEN 8	8 GB	-	256 GB	-	-	-	bagus	2025	\N	\N	\N
134	GTK-02-03-62	PF13BYDT	47	2	checked_out	\N	2787162.00	- | Vendor: ALPRASOFT	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	MARKETING	NADA DEWANDA	\N	I5-GEN 8	8 GB	-	256 GB	-	-	-	bagus	2025	\N	\N	\N
135	GTK-02-03-63	PF13BYEZ	47	2	checked_out	\N	2787162.00	- | Vendor: ALPRASOFT	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	INFORMATION & TECHNOLOGY	INDRA	TIKA SUMARYA	I5-GEN 8	8 GB	-	256 GB	-	-	-	bagus	2025	\N	\N	\N
136	GTK-02-03-64	PF1LJ47A	47	1	checked_out	\N	2787162.00	- | Vendor: ALPRASOFT	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	PRODUKSI	HARRY	INDRA	I5-GEN 8	8 GB	-	256 GB	-	-	-	bagus	2025	\N	\N	\N
137	GTK-03-01-01	-	6	3	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	DIGITAL MARKETING	ICAD	YOGI	I3-GEN 3	8 GB	150 GB	128 GB	-	GTK-M-03-01-01	HP 19"	bagus	2012	\N	\N	\N
138	GTK-03-01-02	-	6	3	checked_out	\N	1000000.00	UNTUK IMPLEMENTASI SYSTEM DI OFFICE JKT | Vendor: SPARTA COMPUTINDO	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	DIGITAL MARKETING	FARHANAN EKA	YOGI	I3-GEN 3	4 GB	-	128 GB	-	GTK-M-03-01-02	HP 19"	bagus	2025	\N	\N	\N
139	GTK-03-01-03	-	6	3	checked_out	\N	1000000.00	UNTUK IMPLEMENTASI SYSTEM DI OFFICE JKT | Vendor: SPARTA COMPUTINDO	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	DIGITAL MARKETING	FARHANAN EKA	YOGI	I3-GEN 3	4 GB	-	128 GB	-	GTK-M-03-01-03	HP 19"	bagus	2025	\N	\N	\N
140	GTK-03-01-04	-	6	3	checked_out	\N	1000000.00	UNTUK IMPLEMENTASI SYSTEM DI OFFICE JKT | Vendor: SPARTA COMPUTINDO	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	DIGITAL MARKETING	FARHANAN EKA	YOGI	I3-GEN 3	4 GB	-	128 GB	-	GTK-M-03-01-04	HP 19"	bagus	2025	\N	\N	\N
141	GTK-03-01-05	-	6	3	checked_out	\N	9142000.00	UNTUK LIVESTREAM NATUR | Vendor: HARCO MANGGA DUA	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	DIGITAL MARKETING	RIZKI METIADI	\N	AMD RYZEN 5	16 GB	-	256 GB	NVIDIA GEFORCE RTX 3050	GTK-M-03-01-05	LG 14"	bagus	2025	\N	\N	\N
142	GTK-03-01-06	-	6	4	checked_out	\N	950000.00	UNTUK EKSPANSI SYSTEM KE BRANCH | Vendor: SPARTA COMPUTINDO	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	OPERASIONAL	FARHANAN EKA	\N	I3-GEN 2	8 GB	-	128 GB	-	GTK-M-03-01-06	HP 19"	bagus	2025	\N	\N	\N
143	GTK-03-01-07	-	6	4	checked_out	\N	950000.00	UNTUK EKSPANSI SYSTEM KE BRANCH | Vendor: SPARTA COMPUTINDO	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	OPERASIONAL	FARHANAN EKA	\N	I3-GEN 2	8 GB	-	128 GB	-	GTK-M-03-01-07	HP 19"	bagus	2025	\N	\N	\N
144	GTK-03-01-08	-	6	4	checked_out	\N	950000.00	UNTUK EKSPANSI SYSTEM KE BRANCH | Vendor: SPARTA COMPUTINDO	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	OPERASIONAL	FARHANAN EKA	\N	I3-GEN 2	8 GB	-	128 GB	-	GTK-M-03-01-08	HP 19"	bagus	2025	\N	\N	\N
145	GTK-03-01-09	-	6	4	checked_out	\N	950000.00	UNTUK EKSPANSI SYSTEM KE BRANCH | Vendor: SPARTA COMPUTINDO	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	OPERASIONAL	FARHANAN EKA	\N	I3-GEN 2	8 GB	-	128 GB	-	GTK-M-03-01-09	HP 19"	bagus	2025	\N	\N	\N
146	GTK-03-01-10	-	6	4	checked_out	\N	950000.00	UNTUK EKSPANSI SYSTEM KE BRANCH | Vendor: SPARTA COMPUTINDO	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	OPERASIONAL	FARHANAN EKA	\N	I3-GEN 2	8 GB	-	128 GB	-	GTK-M-03-01-10	HP 19"	bagus	2025	\N	\N	\N
147	GTK-03-01-11	-	6	4	checked_out	\N	950000.00	UNTUK EKSPANSI SYSTEM KE BRANCH | Vendor: SPARTA COMPUTINDO	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	OPERASIONAL	FARHANAN EKA	\N	I3-GEN 2	8 GB	-	128 GB	-	GTK-M-03-01-11	HP 19"	bagus	2025	\N	\N	\N
148	GTK-03-01-12	-	6	4	checked_out	\N	950000.00	UNTUK EKSPANSI SYSTEM KE BRANCH | Vendor: SPARTA COMPUTINDO	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	OPERASIONAL	FARHANAN EKA	\N	I3-GEN 2	8 GB	-	128 GB	-	GTK-M-03-01-12	HP 19"	bagus	2025	\N	\N	\N
149	GTK-03-01-13	-	6	4	checked_out	\N	950000.00	UNTUK EKSPANSI SYSTEM KE BRANCH | Vendor: SPARTA COMPUTINDO	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	OPERASIONAL	FARHANAN EKA	\N	I3-GEN 2	8 GB	-	128 GB	-	GTK-M-03-01-13	HP 19"	bagus	2025	\N	\N	\N
150	GTK-03-01-14	-	6	4	checked_out	\N	950000.00	UNTUK EKSPANSI SYSTEM KE BRANCH | Vendor: SPARTA COMPUTINDO	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	OPERASIONAL	FARHANAN EKA	\N	I3-GEN 2	8 GB	-	128 GB	-	GTK-M-03-01-14	HP 19"	bagus	2025	\N	\N	\N
151	GTK-03-01-15	-	6	4	checked_out	\N	950000.00	UNTUK EKSPANSI SYSTEM KE BRANCH | Vendor: SPARTA COMPUTINDO	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	OPERASIONAL	FARHANAN EKA	\N	I3-GEN 2	8 GB	-	128 GB	-	GTK-M-03-01-15	HP 19"	bagus	2025	\N	\N	\N
152	GTK-03-01-16	-	6	4	checked_out	\N	950000.00	UNTUK EKSPANSI SYSTEM KE BRANCH | Vendor: SPARTA COMPUTINDO	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	OPERASIONAL	FARHANAN EKA	\N	I3-GEN 2	8 GB	-	128 GB	-	GTK-M-03-01-16	HP 19"	bagus	2025	\N	\N	\N
153	GTK-03-01-17	-	6	4	checked_out	\N	950000.00	UNTUK EKSPANSI SYSTEM KE BRANCH | Vendor: SPARTA COMPUTINDO	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	OPERASIONAL	FARHANAN EKA	\N	I3-GEN 2	8 GB	-	128 GB	-	GTK-M-03-01-17	HP 19"	bagus	2025	\N	\N	\N
154	GTK-03-01-18	-	6	4	checked_out	\N	950000.00	UNTUK EKSPANSI SYSTEM KE BRANCH | Vendor: SPARTA COMPUTINDO	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	OPERASIONAL	FARHANAN EKA	\N	I3-GEN 2	8 GB	-	128 GB	-	GTK-M-03-01-18	HP 19"	bagus	2025	\N	\N	\N
155	GTK-03-01-19	-	6	4	checked_out	\N	950000.00	UNTUK EKSPANSI SYSTEM KE BRANCH | Vendor: SPARTA COMPUTINDO	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	OPERASIONAL	FARHANAN EKA	\N	I3-GEN 2	8 GB	-	128 GB	-	GTK-M-03-01-19	HP 19"	bagus	2025	\N	\N	\N
234	GTK-02-08-06	-	79	1	checked_out	\N	630000.00	PENGGANTI UNIT GTK-02-08-10 | Vendor: MITRA SATU	2026-07-24 11:33:55	2026-07-24 11:33:55	\N	FACTORY GOODS	DIVISI FACTORY GOODS	\N	-	-	-	-	-	-	-	bagus	2025	\N	\N	\N
156	GTK-03-01-20	-	6	4	checked_out	\N	950000.00	UNTUK EKSPANSI SYSTEM KE BRANCH | Vendor: SPARTA COMPUTINDO	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	OPERASIONAL	FARHANAN EKA	\N	I3-GEN 2	8 GB	-	128 GB	-	GTK-M-03-01-20	HP 19"	bagus	2025	\N	\N	\N
157	GTK-03-01-21	-	6	4	checked_out	\N	950000.00	UNTUK EKSPANSI SYSTEM KE BRANCH | Vendor: SPARTA COMPUTINDO	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	OPERASIONAL	FARHANAN EKA	\N	I3-GEN 2	8 GB	-	128 GB	-	GTK-M-03-01-21	HP 19"	bagus	2025	\N	\N	\N
158	GTK-03-01-22	-	6	3	checked_out	\N	10260036.00	UNTUK LIVESTREAMING (MIZZU TIKTOK)	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	DIGITAL MARKETING	RIZKI METIADI	\N	AMD RYZEN 5	16 GB	-	128 GB	NVIDIA GEFORCE RTX 5050	GTK-M-03-01-23	LG 24"	bagus	2026	\N	\N	\N
159	GTK-03-03-01	-	39	2	in_stock	\N	\N	TRACKPAD TIDAK BISA DIGUNAKAN	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	INFORMATION & TECHNOLOGY	\N	AININ ABIDAH R	AMD RYZEN 3	8 GB	-	256 GB	-	-	-	bagus	2017	\N	\N	\N
160	GTK-03-03-02	37657/31YV00898	39	2	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	FINANCE & ACCOUNTING	ADIS NAURAH	MAGANG BMS	I3-GEN 11	8 GB	-	256 GB	-	-	-	bagus	2021	\N	\N	\N
161	GTK-03-03-03	-	39	3	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	DIGITAL MARKETING	MJ FADLOAN ASHARI	\N	AMD RYZEN 3	8 GB	-	512 GB	-	-	-	bagus	2017	\N	\N	\N
162	GTK-03-03-04	-	39	2	in_stock	\N	\N	BIAYA SERVIS > NILAI | Prioritas: P1	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	INFORMATION & TECHNOLOGY	\N	INTAN PRATIWI	AMD RYZEN 7	4 GB	-	-	-	-	-	mati total	2017	\N	\N	\N
163	GTK-03-03-05	-	39	2	in_stock	\N	\N	SUDAH DI SERVIS, TIDAK BISA HIDUP	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	INFORMATION & TECHNOLOGY	\N	HASAN JUNAIDI	I3-GEN 11	8 GB	-	256 GB	-	-	-	mati total	2021	\N	\N	\N
164	GTK-03-03-06	-	39	3	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	OPERASIONAL	PICKER		AMD-A9	8 GB	1000 GB	-	-	-	-	bagus	2016	\N	\N	\N
165	GTK-03-03-07	-	39	3	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	OPERASIONAL	FARHANAN EKA		AMD ATHLON SILVER	8 GB	-	256 GB	-	-	-	bagus	2021	\N	\N	\N
166	GTK-03-03-08	-	39	2	in_stock	\N	\N	TERKENA AIR HUJAN (GANTI RUGI USER)	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	DIGITAL MARKETING	\N	CANTIKA ORYZA S	INTEL CELERON	4 GB	-	512 GB	-	-	-	mati total	2020	\N	\N	\N
167	GTK-03-03-09	-	39	2	in_stock	\N	\N	BIAYA SERVIS > NILAI | Prioritas: P1	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	OPERASIONAL	\N	LOLA OKTALITA	I3-GEN 4	4 GB	512 GB	-	-	-	-	mati total	2012	\N	\N	\N
168	GTK-04-03-01	-	39	2	checked_out	\N	\N	PENAMBAHAN RAM	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	SALES	HENDRIK EVAYANTO	\N	AMD ATHLON GOLD	4 GB	-	512 GB	-	-	-	kurang optimal	2020	\N	\N	\N
169	GTK-04-03-02	NXHVWSN0050420D47F7600	39	2	in_stock	\N	\N	-	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	INFORMATION & TECHNOLOGY	\N	DERI DUBIDURAHMAN	AMD RYZEN 3	4 GB	-	256 GB	-	-	-	bagus	2017	\N	\N	\N
170	GTK-01-04-01	69757/15VR03557	49	5	checked_out	\N	1490000.00	UNTUK CONTENT CREATOR	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	MARKETING	WINDA PUTRI D	\N	OCTA CORE	6 GB	-	128 GB	-	-	-	bagus	2025	\N	\N	\N
171	GTK-01-04-02	69756/15VY01379	49	5	checked_out	\N	1490000.00	UNTUK CONTENT CREATOR	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	MARKETING	WINDA PUTRI D	\N	OCTA CORE	6 GB	-	128 GB	-	-	-	bagus	2025	\N	\N	\N
172	GTK-01-04-03	69757/15VK00356	49	5	checked_out	\N	1490000.00	UNTUK CONTENT CREATOR	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	MARKETING	WINDA PUTRI D	\N	OCTA CORE	6 GB	-	128 GB	-	-	-	bagus	2025	\N	\N	\N
173	GTK-01-04-04	69753/15VR03396	49	5	checked_out	\N	1490000.00	UNTUK CONTENT CREATOR	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	MARKETING	WINDA PUTRI D	\N	OCTA CORE	6 GB	-	128 GB	-	-	-	bagus	2025	\N	\N	\N
174	GTK-01-04-05	69756/15VY02263	49	5	checked_out	\N	1490000.00	UNTUK CONTENT CREATOR	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	MARKETING	WINDA PUTRI D	\N	OCTA CORE	6 GB	-	128 GB	-	-	-	bagus	2025	\N	\N	\N
175	GTK-01-04-06	-	49	3	checked_out	\N	1490000.00	UNTUK AFFILIATE STAFF	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	DIGITAL MARKETING	HASAN JUNAIDI	\N	OCTA CORE	6 GB	-	128 GB	-	-	-	bagus	2025	\N	\N	\N
176	GTK-01-04-07	-	49	3	checked_out	\N	1490000.00	UNTUK AFFILIATE STAFF	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	DIGITAL MARKETING	HASAN JUNAIDI	\N	OCTA CORE	6 GB	-	128 GB	-	-	-	bagus	2025	\N	\N	\N
177	GTK-01-04-08	-	49	3	checked_out	\N	1490000.00	UNTUK AFFILIATE STAFF	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	DIGITAL MARKETING	HASAN JUNAIDI	\N	OCTA CORE	6 GB	-	128 GB	-	-	-	bagus	2025	\N	\N	\N
178	GTK-01-04-09	-	49	3	checked_out	\N	1490000.00	UNTUK AFFILIATE STAFF	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	DIGITAL MARKETING	HASAN JUNAIDI	\N	OCTA CORE	6 GB	-	128 GB	-	-	-	bagus	2025	\N	\N	\N
179	GTK-01-04-10	YK9372408180100207	50	1	checked_out	\N	3250000.00	PDA SCANNER - UNTUK GUDANG RMPM | Vendor: PUTRA - CASHCOW	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	RMPM	REZA PERMADI	\N	OCTA CORE	4 GB	-	64 GB	-	-	-	bagus	2026	22/06/26	\N	\N
180	GTK-01-04-11	YK9372408180101980	50	1	checked_out	\N	3250000.00	PDA SCANNER - UNTUK GUDANG RMPM | Vendor: PUTRA - CASHCOW	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	RMPM	REZA PERMADI	\N	OCTA CORE	4 GB	-	64 GB	-	-	-	bagus	2026	22/06/26	\N	\N
181	GTK-01-05-01	-	51	2	checked_out	\N	\N	-	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	MARKETING	DIVISI MARKETING	\N	-	-	-	-	-	-	-	bagus	\N	\N	\N	\N
182	GTK-01-05-02	X8JX007784	52	2	checked_out	\N	\N	-	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	FINANCE & ACCOUNTING	DIVISI FINANCE & ACCOUNTING	\N	-	-	-	-	-	-	-	bagus	\N	\N	\N	\N
183	GTK-01-05-03	-	53	2	checked_out	\N	2180350.00	- | Vendor: FIXPRINT	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	SALES	DIVISI SALES	\N	-	-	-	-	-	-	-	bagus	2026	\N	\N	\N
184	GTK-01-05-04	-	54	2	checked_out	\N	\N	BIAYA SERVIS > NILAI | Prioritas: P1	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	INFORMATION & TECHNOLOGY	DIVISI INFORMATION & TECHNOLOGY	\N	-	-	-	-	-	-	-	mati total	\N	\N	\N	\N
185	GTK-01-05-05	-	55	2	checked_out	\N	\N	DIJUAL KEPADA ELVINA COMP NUSANTARA TANGGAL 18/02/2026	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	INFORMATION & TECHNOLOGY	DIVISI INFORMATION & TECHNOLOGY	\N	-	-	-	-	-	-	-	rusak berat	2010	\N	\N	\N
186	GTK-01-05-06	-	55	2	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	FINANCE & ACCOUNTING	DIVISI FINANCE & ACCOUNTING	\N	-	-	-	-	-	-	-	bagus	2010	\N	\N	\N
187	GTK-01-05-07	-	56	2	checked_out	\N	2300000.00	Vendor: FIXPRINT	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	MARKETING	DIVISI MARKETING	\N	\N	-	-	-	-	-	-	bagus	2025	\N	\N	\N
188	GTK-02-05-01	-	55	1	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	INFORMATION & TECHNOLOGY	DIVISI INFORMATION & TECHNOLOGY	\N	-	-	-	-	-	-	-	bagus	2010	\N	\N	\N
189	GTK-02-05-02	-	57	1	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	HCD	DIVISI HCD	\N	-	-	-	-	-	-	-	bagus	\N	\N	\N	\N
190	GTK-02-05-03	-	58	1	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	HCD	DIVISI HCD	\N	-	-	-	-	-	-	-	bagus	\N	\N	\N	\N
191	GTK-02-05-04	-	59	2	checked_out	\N	\N	INSIDEN KEBOCORAN ATAP | Prioritas: P1	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	INFORMATION & TECHNOLOGY	DIVISI INFORMATION & TECHNOLOGY	\N	-	-	-	-	-	-	-	mati total	\N	\N	\N	\N
192	GTK-02-05-05	-	55	1	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	RMPM	DIVISI RMPM	\N	-	-	-	-	-	-	-	bagus	2010	\N	\N	\N
193	GTK-02-05-06	-	55	1	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	PRODUKSI	DIVISI PRODUKSI	\N	-	-	-	-	-	-	-	bagus	2010	\N	\N	\N
235	GTK-02-08-07	-	75	1	checked_out	\N	\N	AREA SUDAH TERCOVER WIRELESS	2026-07-24 11:33:55	2026-07-24 11:33:55	\N	RMPM	DIVISI RMPM	\N	-	-	-	-	-	-	-	bagus	\N	\N	\N	\N
194	GTK-02-05-07	-	60	1	checked_out	\N	\N	DIJUAL KEPADA ELVINA COMP NUSANTARA TANGGAL 18/02/2026	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	INFORMATION & TECHNOLOGY	DIVISI INFORMATION & TECHNOLOGY	\N	-	-	-	-	-	-	-	mati total	2003	\N	\N	\N
195	GTK-02-05-08	-	61	1	checked_out	\N	\N	DIJUAL KEPADA ELVINA COMP NUSANTARA TANGGAL 18/02/2026	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	INFORMATION & TECHNOLOGY	DIVISI INFORMATION & TECHNOLOGY	\N	-	-	-	-	-	-	-	mati total	2015	\N	\N	\N
196	GTK-02-05-09	-	62	1	checked_out	\N	\N	DIJUAL KEPADA ELVINA COMP NUSANTARA TANGGAL 18/02/2026	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	INFORMATION & TECHNOLOGY	DIVISI INFORMATION & TECHNOLOGY	\N	-	-	-	-	-	-	-	mati total	2010	\N	\N	\N
197	GTK-02-05-10	-	62	1	checked_out	\N	\N	DIJUAL KEPADA ELVINA COMP NUSANTARA TANGGAL 18/02/2026	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	INFORMATION & TECHNOLOGY	DIVISI INFORMATION & TECHNOLOGY	\N	-	-	-	-	-	-	-	mati total	2001	\N	\N	\N
198	GTK-02-05-11	-	63	1	checked_out	\N	\N	DIJUAL KEPADA ELVINA COMP NUSANTARA TANGGAL 18/02/2026	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	INFORMATION & TECHNOLOGY	DIVISI INFORMATION & TECHNOLOGY	\N	-	-	-	-	-	-	-	mati total	2008	\N	\N	\N
199	GTK-02-05-12	-	64	1	checked_out	\N	\N	DIJUAL KEPADA ELVINA COMP NUSANTARA TANGGAL 18/02/2026	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	INFORMATION & TECHNOLOGY	DIVISI INFORMATION & TECHNOLOGY	\N	-	-	-	-	-	-	-	mati total	2012	\N	\N	\N
200	GTK-02-05-13	-	65	1	checked_out	\N	428000.00	PENGGANTI UNIT GTK-02-05-04 | Vendor: AFNISNOW (SHOPPE)	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	RMPM	DIVISI RMPM	\N	-	-	-	-	-	-	-	bagus	2026	\N	\N	\N
201	GTK-03-05-01	-	66	3	checked_out	\N	\N	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	OPERASIONAL	DIVISI OPERASIONAL	\N	-	-	-	-	-	-	-	bagus	\N	\N	\N	\N
202	GTK-03-06-01	-	67	3	checked_out	\N	11400000.00	UNTUK LIVESTREAMING (NATUR TIKTOK)	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	DIGITAL MARKETING	DIVISI DIGITAL MARKETING	\N	-	-	-	-	-	-	-	bagus	2025	\N	\N	\N
203	GTK-03-06-02	-	67	3	checked_out	\N	8189000.00	UNTUK LIVESTREAMING (NATUR SHOPPE)	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	DIGITAL MARKETING	DIVISI DIGITAL MARKETING	\N	-	-	-	-	-	-	-	bagus	2025	\N	\N	\N
204	GTK-03-06-03	-	67	3	checked_out	\N	10500000.00	UNTUK LIVESTREAMING (MIZZU TIKTOK) | Vendor: SHOPPE	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	DIGITAL MARKETING	DIVISI DIGITAL MARKETING	\N	-	-	-	-	-	-	-	bagus	2026	\N	\N	\N
205	GTK-01-07-01	-	68	2	checked_out	\N	532800.00	- | Vendor: SHOPPE	2026-07-24 11:33:54	2026-07-24 11:33:54	\N	SALES	DIVISI SALES	\N	-	-	-	-	-	-	-	bagus	2024	\N	\N	\N
206	GTK-01-07-02	-	68	2	checked_out	\N	504999.00	- | Vendor: SHOPPE	2026-07-24 11:33:55	2026-07-24 11:33:55	\N	MARKETING	DIVISI MARKETING	\N	-	-	-	-	-	-	-	bagus	2024	\N	\N	\N
207	GTK-01-07-03	-	68	2	checked_out	\N	\N	Vendor: SHOPPE	2026-07-24 11:33:55	2026-07-24 11:33:55	\N	SALES	DIVISI SALES	\N	-	-	-	-	-	-	-	bagus	\N	\N	\N	\N
208	GTK-01-07-04	-	68	2	checked_out	\N	\N	Vendor: SHOPPE	2026-07-24 11:33:55	2026-07-24 11:33:55	\N	MARKETING	DIVISI MARKETING	\N	-	-	-	-	-	-	-	bagus	\N	\N	\N	\N
209	GTK-01-07-05	-	69	3	checked_out	\N	\N	\N	2026-07-24 11:33:55	2026-07-24 11:33:55	\N	OPERASIONAL	DIVISI OPERASIONAL	DIVISI SALES	-	-	-	-	-	-	-	bagus	2021	\N	\N	\N
210	GTK-01-07-06	-	69	2	checked_out	\N	\N	\N	2026-07-24 11:33:55	2026-07-24 11:33:55	\N	BOD	BOD	\N	-	-	-	-	-	-	-	bagus	2021	\N	\N	\N
211	GTK-01-07-07	-	69	2	checked_out	\N	\N	\N	2026-07-24 11:33:55	2026-07-24 11:33:55	\N	OPERASIONAL	DIVISI MARKETING	\N	-	-	-	-	-	-	-	bagus	2021	\N	\N	\N
212	GTK-02-07-01	-	68	1	checked_out	\N	505000.00	UNTUK PERLUAS COVERAGE WI-FI PABRIK | Vendor: SHOPPE	2026-07-24 11:33:55	2026-07-24 11:33:55	\N	FACTORY GOODS	DIVISI FACTORY GOODS	\N	-	-	-	-	-	-	-	bagus	2024	\N	\N	\N
213	GTK-02-07-02	-	68	3	checked_out	\N	505000.00	UNTUK PERLUAS COVERAGE WI-FI PABRIK | Vendor: SHOPPE	2026-07-24 11:33:55	2026-07-24 11:33:55	\N	DIGITAL MARKETING	DIVISI DIGITAL MARKETING	DIVISI PRODUKSI	-	-	-	-	-	-	-	bagus	2024	\N	\N	\N
214	GTK-02-07-03	-	70	1	checked_out	\N	1256180.00	UNTUK PERLUAS COVERAGE WI-FI PABRIK | Vendor: SHOPPE	2026-07-24 11:33:55	2026-07-24 11:33:55	\N	MAINTENANCE	DIVISI MAINTENANCE	\N	-	-	-	-	-	-	-	bagus	2024	\N	\N	\N
215	GTK-02-07-04	-	70	1	checked_out	\N	1256180.00	UNTUK PERLUAS COVERAGE WI-FI PABRIK | Vendor: SHOPPE	2026-07-24 11:33:55	2026-07-24 11:33:55	\N	RMPM	DIVISI RMPM	\N	-	-	-	-	-	-	-	bagus	2024	\N	\N	\N
216	GTK-02-07-05	-	71	1	checked_out	\N	\N	-	2026-07-24 11:33:55	2026-07-24 11:33:55	\N	INFORMATION & TECHNOLOGY	DIVISI INFORMATION & TECHNOLOGY	\N	-	-	-	-	-	-	-	bagus	\N	\N	\N	\N
217	GTK-03-07-01	-	72	3	checked_out	\N	267000.00	- | Vendor: SHOPPE	2026-07-24 11:33:55	2026-07-24 11:33:55	\N	OPERASIONAL	DIVISI OPERASIONAL	\N	-	-	-	-	-	-	-	bagus	2024	\N	\N	\N
218	GTK-03-07-02	-	72	3	checked_out	\N	267000.00	- | Vendor: SHOPPE	2026-07-24 11:33:55	2026-07-24 11:33:55	\N	DIGITAL MARKETING	DIVISI DIGITAL MARKETING	\N	-	-	-	-	-	-	-	bagus	2024	\N	\N	\N
219	GTK-03-07-03	-	72	5	checked_out	\N	267000.00	- | Vendor: SHOPPE	2026-07-24 11:33:55	2026-07-24 11:33:55	\N	MARKETING	DIVISI MARKETING	BMS OFFICE	-	-	-	-	-	-	-	bagus	2024	\N	\N	\N
220	GTK-03-07-04	-	73	3	checked_out	\N	839100.00	- | Vendor: SHOPPE	2026-07-24 11:33:55	2026-07-24 11:33:55	\N	OPERASIONAL	DIVISI OPERASIONAL	\N	-	-	-	-	-	-	-	bagus	2025	\N	\N	\N
221	GTK-04-07-01	-	68	5	checked_out	\N	556999.00	- | Vendor: SHOPPE	2026-07-24 11:33:55	2026-07-24 11:33:55	\N	MARKETING	DIVISI MARKETING	\N	-	-	-	-	-	-	-	bagus	2025	\N	\N	\N
222	GTK-04-07-02	-	68	5	checked_out	\N	556999.00	- | Vendor: SHOPPE	2026-07-24 11:33:55	2026-07-24 11:33:55	\N	MARKETING	DIVISI MARKETING	\N	-	-	-	-	-	-	-	bagus	2025	\N	\N	\N
223	GTK-04-07-03	-	73	5	checked_out	\N	883900.00	- | Vendor: SHOPPE	2026-07-24 11:33:55	2026-07-24 11:33:55	\N	MARKETING	DIVISI MARKETING	\N	-	-	-	-	-	-	-	bagus	2025	\N	\N	\N
224	GTK-01-08-01	-	74	2	checked_out	\N	\N	-	2026-07-24 11:33:55	2026-07-24 11:33:55	\N	INFORMATION & TECHNOLOGY	DIVISI INFORMATION & TECHNOLOGY	\N	-	-	-	-	-	-	-	bagus	\N	\N	\N	\N
225	GTK-01-08-02	-	75	2	checked_out	\N	122100.00	- | Vendor: SHOPPE	2026-07-24 11:33:55	2026-07-24 11:33:55	\N	FINANCE & ACCOUNTING	DIVISI FINANCE & ACCOUNTING	\N	-	-	-	-	-	-	-	bagus	2024	\N	\N	\N
226	GTK-01-08-03	-	76	2	checked_out	\N	265000.00	- | Vendor: TOKOPEDIA	2026-07-24 11:33:55	2026-07-24 11:33:55	\N	FINANCE & ACCOUNTING	DIVISI FINANCE & ACCOUNTING	\N	-	-	-	-	-	-	-	bagus	2025	\N	\N	\N
227	GTK-01-08-04	-	75	2	checked_out	\N	122100.00	- | Vendor: SHOPPE	2026-07-24 11:33:55	2026-07-24 11:33:55	\N	MARKETING	DIVISI MARKETING	\N	-	-	-	-	-	-	-	bagus	2024	\N	\N	\N
228	GTK-01-08-05	-	75	2	checked_out	\N	79000.00	- | Vendor: MITRA SATU	2026-07-24 11:33:55	2026-07-24 11:33:55	\N	RESEARCH N DEVELOPMENT	DIVISI RESEARCH N DEVELOPMENT	\N	-	-	-	-	-	-	-	bagus	2024	\N	\N	\N
229	GTK-02-08-01	-	75	1	checked_out	\N	\N	-	2026-07-24 11:33:55	2026-07-24 11:33:55	\N	QUALITY CONTROL	DIVISI QUALITY CONTROL	\N	-	-	-	-	-	-	-	bagus	\N	\N	\N	\N
230	GTK-02-08-02	-	75	1	checked_out	\N	\N	-	2026-07-24 11:33:55	2026-07-24 11:33:55	\N	HCD	DIVISI HCD	\N	-	-	-	-	-	-	-	bagus	\N	\N	\N	\N
231	GTK-02-08-03	-	77	1	checked_out	\N	\N	-	2026-07-24 11:33:55	2026-07-24 11:33:55	\N	PRODUKSI	DIVISI PRODUKSI	\N	-	-	-	-	-	-	-	bagus	\N	\N	\N	\N
232	GTK-02-08-04	-	78	1	checked_out	\N	180000.00	PENGGANTI UNIT GTK-02-08-09 | Vendor: OFFLINE	2026-07-24 11:33:55	2026-07-24 11:33:55	\N	MAINTENANCE	DIVISI MAINTENANCE	\N	-	-	-	-	-	-	-	bagus	2025	\N	\N	\N
233	GTK-02-08-05	-	75	1	checked_out	\N	\N	\N	2026-07-24 11:33:55	2026-07-24 11:33:55	\N	MAINTENANCE	DIVISI MAINTENANCE	\N	-	-	-	-	-	-	-	bagus	\N	\N	\N	\N
236	GTK-02-08-08	-	75	1	checked_out	\N	\N	\N	2026-07-24 11:33:55	2026-07-24 11:33:55	\N	FACTORY GOODS	DIVISI FACTORY GOODS	\N	-	-	-	-	-	-	-	bagus	\N	\N	\N	\N
237	GTK-02-08-09	-	75	1	checked_out	\N	\N	DIJUAL KEPADA ELVINA COMP NUSANTARA TANGGAL 18/02/2026	2026-07-24 11:33:55	2026-07-24 11:33:55	\N	MAINTENANCE	DIVISI MAINTENANCE	\N	-	-	-	-	-	-	-	rusak berat	2012	\N	\N	\N
238	GTK-02-08-10	-	79	1	checked_out	\N	\N	DIJUAL KEPADA ELVINA COMP NUSANTARA TANGGAL 18/02/2026	2026-07-24 11:33:55	2026-07-24 11:33:55	\N	FACTORY GOODS	DIVISI FACTORY GOODS	\N	-	-	-	-	-	-	-	rusak berat	2012	\N	\N	\N
239	GTK-02-08-11	-	74	1	checked_out	\N	\N	\N	2026-07-24 11:33:55	2026-07-24 11:33:55	\N	INFORMATION & TECHNOLOGY	DIVISI INFORMATION & TECHNOLOGY	\N	-	-	-	-	-	-	-	bagus	\N	\N	\N	\N
240	GTK-03-08-01	-	75	3	checked_out	\N	350380.00	\N	2026-07-24 11:33:55	2026-07-24 11:33:55	\N	DIGITAL MARKETING	DIVISI DIGITAL MARKETING	\N	-	-	-	-	-	-	-	bagus	2025	\N	\N	\N
241	GTK-03-08-02	-	80	3	checked_out	\N	99000.00	\N	2026-07-24 11:33:55	2026-07-24 11:33:55	\N	DIGITAL MARKETING	DIVISI DIGITAL MARKETING	\N	-	-	-	-	-	-	-	bagus	2025	\N	\N	\N
133	GTK-02-03-61	PF1LJ67D	47	2	checked_out	\N	2787162.00	KERUSAKAN DI IC & SSD CARD | Vendor: ALPRASOFT | Sudah Fix per tanggal 22 Juli 2026	2026-07-24 11:33:54	2026-07-24 15:37:27	\N	INFORMATION & TECHNOLOGY	Muhamad Farhan	\N	I5-GEN 8	8 GB	-	256 GB	-	-	-	bagus	2025	\N	[]	\N
243	GTK-100-01-2010	TEST	48	2	in_stock	2023-06-26	5600000.00	Test untuk Service Tickets	2026-07-27 15:29:39	2026-07-27 15:29:39	Head Office	\N	\N	\N	i3 10400	4GB	\N	256GB	\N	\N	\N	bagus	2023	1 Tahun	[]	\N
242	GTK-100-01-2009	01-test-asset-for-dispose-09	81	2	disposed	2026-01-01	52000000.00	Test Asset untuk disposed	2026-07-27 14:31:39	2026-07-27 15:51:19	IT	IT	\N	\N	i9 13900K	64 GB DDR5	\N	8TB	RTX 5090 32GB	GTK-M-0101	SMASNUG Odyssey G8 4K OLED 32"	bagus	2026	5 Tahun	[]	\N
113	GTK-02-03-41	5CG12176K9	36	2	in_stock	\N	\N	Diagnosa processor rusak oleh Bambang Yulianto\nDiagnosa lanjutan :\nEngsel bermasalah\nExisting cosmetic damage\n- Storage bermasalah, processor masih bisa berfungsi. \nTindakan lanjutan :\nStorage (SSD) baru\nInstalasi OS baru\nReparasi engsel mandiri\n\n	2026-07-24 11:33:54	2026-07-28 15:30:46	LEMARI IT	SALES	\N	\N	AMD RYZEN 3	8 GB	-	256 GB	-	-	-	bagus	2017	\N	[]	\N
\.


--
-- TOC entry 5248 (class 0 OID 40966)
-- Dependencies: 243
-- Data for Name: berita_acaras; Type: TABLE DATA; Schema: public; Owner: ams_user
--

COPY public.berita_acaras (id, letter_number, letter_date, category, title, asset_id, asset_tag, asset_name, quantity, completeness, party1_name, party1_title, party1_department, party2_name, party2_title, party2_department, approver_name, approver_title, description_points, attachments, created_by, created_at, updated_at) FROM stdin;
2	BA/IT/2026/07/001	2026-07-24	kerusakan_sparepart	Berita Acara Perbaikan / Spare Part Asset IT	113	GTK-02-03-41	Custom / General HP 14-DK1XXX	1 Unit	1 Unit Laptop	Muhamad Farhan	IT STAFF	INFORMATION & TECHNOLOGY	Bambang Yulianto	IT	IT	SETYADI CANDRAWINATA	GM Finance & Operations	1. Bahwa PIHAK KEDUA melaporkan adanya indikasi kerusakan/penurunan performa pada unit aset IT.\n2. Bahwa PIHAK PERTAMA telah melakukan pemeriksaan teknis dan mengkonfirmasi perbaikan/penggantian komponen spare part.\n3. Bahwa unit aset telah selesai diperbaiki dan siap digunakan kembali untuk mendukung operasional perusahaan.	[]	34	2026-07-24 17:00:07	2026-07-24 17:00:07
\.


--
-- TOC entry 5230 (class 0 OID 16435)
-- Dependencies: 225
-- Data for Name: cache; Type: TABLE DATA; Schema: public; Owner: ams_user
--

COPY public.cache (key, value, expiration) FROM stdin;
laravel-cache-356a192b7913b04c54574d18c28d46e6395428ab:timer	i:1784865892;	1784865892
laravel-cache-356a192b7913b04c54574d18c28d46e6395428ab	i:2;	1784865892
laravel-cache-livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3:timer	i:1784878052;	1784878052
laravel-cache-livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3	i:1;	1784878052
\.


--
-- TOC entry 5231 (class 0 OID 16446)
-- Dependencies: 226
-- Data for Name: cache_locks; Type: TABLE DATA; Schema: public; Owner: ams_user
--

COPY public.cache_locks (key, owner, expiration) FROM stdin;
\.


--
-- TOC entry 5238 (class 0 OID 16508)
-- Dependencies: 233
-- Data for Name: categories; Type: TABLE DATA; Schema: public; Owner: ams_user
--

COPY public.categories (id, name, type, created_at, updated_at) FROM stdin;
1	PC	asset	2026-07-24 03:20:12	2026-07-24 03:20:12
2	Laptop	asset	2026-07-24 03:20:12	2026-07-24 03:20:12
3	Monitor	asset	2026-07-24 03:20:12	2026-07-24 03:20:12
4	Printer	asset	2026-07-24 03:20:12	2026-07-24 03:20:12
5	-	asset	2026-07-24 03:22:06	2026-07-24 03:22:06
6	LAPTOP	asset	2026-07-24 11:33:53	2026-07-24 11:33:53
7	SELULLER	asset	2026-07-24 11:33:54	2026-07-24 11:33:54
8	PRINTER	asset	2026-07-24 11:33:54	2026-07-24 11:33:54
9	CAMERA	asset	2026-07-24 11:33:54	2026-07-24 11:33:54
10	ROUTER	asset	2026-07-24 11:33:54	2026-07-24 11:33:54
11	ACCESS POINT	asset	2026-07-24 11:33:55	2026-07-24 11:33:55
12	SWITCH	asset	2026-07-24 11:33:55	2026-07-24 11:33:55
13	HUB	asset	2026-07-24 11:33:55	2026-07-24 11:33:55
\.


--
-- TOC entry 5246 (class 0 OID 16577)
-- Dependencies: 241
-- Data for Name: checkouts; Type: TABLE DATA; Schema: public; Owner: ams_user
--

COPY public.checkouts (id, asset_id, user_id, checked_out_by, checked_in_by, checked_out_at, checked_in_at, checkout_notes, checkin_notes, created_at, updated_at, primary_user, secondary_user, checkout_attachment, checkin_attachment, checkout_attachments, checkin_attachments, component_checklist) FROM stdin;
9	7	\N	1	\N	2026-07-24 11:14:52	\N	Import dari master data CSV	\N	2026-07-24 11:14:52	2026-07-24 11:14:52	SEPTIANY	OCULIANA RIEKA R	\N	\N	\N	\N	\N
10	8	\N	1	\N	2026-07-24 11:33:53	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	DEWI ELIYANTI	AYU	\N	\N	\N	\N	\N
11	9	\N	1	\N	2026-07-24 11:33:53	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	YENNY TJAHYADI	ANINGTYAS	\N	\N	\N	\N	\N
12	10	\N	1	\N	2026-07-24 11:33:53	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	YUNI SAMSUDIN	\N	\N	\N	\N	\N	\N
13	11	\N	1	\N	2026-07-24 11:33:53	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	GABI	REFURBISH	\N	\N	\N	\N	\N
14	12	\N	1	\N	2026-07-24 11:33:53	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	NURUL AZIZAH F	SRI LESTARI	\N	\N	\N	\N	\N
15	13	\N	1	\N	2026-07-24 11:33:53	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	USWATI HASANAH	\N	\N	\N	\N	\N	\N
16	15	\N	1	\N	2026-07-24 11:33:53	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	AHMAD HERMANSYAH	\N	\N	\N	\N	\N	\N
17	16	\N	1	\N	2026-07-24 11:33:53	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	ROHIMAN	\N	\N	\N	\N	\N	\N
18	17	\N	1	\N	2026-07-24 11:33:53	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	HARIAN	RISSA ILLA S	\N	\N	\N	\N	\N
19	18	\N	1	\N	2026-07-24 11:33:53	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	INTAN NURAENI	\N	\N	\N	\N	\N	\N
20	19	\N	1	\N	2026-07-24 11:33:53	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	DITA	AHMAD S	\N	\N	\N	\N	\N
21	20	\N	1	\N	2026-07-24 11:33:53	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	ABUNATA	\N	\N	\N	\N	\N	\N
22	21	\N	1	\N	2026-07-24 11:33:53	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	JANNY AISYAH P	\N	\N	\N	\N	\N	\N
23	23	\N	1	\N	2026-07-24 11:33:53	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	NISSA	PUTRI INTAN NADIRA	\N	\N	\N	\N	\N
24	24	\N	1	\N	2026-07-24 11:33:53	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	METRIZAL BUCHARI	\N	\N	\N	\N	\N	\N
25	26	\N	1	\N	2026-07-24 11:33:53	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	HARI	\N	\N	\N	\N	\N	\N
26	27	\N	1	\N	2026-07-24 11:33:53	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	DIVISI INFORMATION & TECHNOLOGY	\N	\N	\N	\N	\N	\N
27	28	\N	1	\N	2026-07-24 11:33:53	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	DIVISI INFORMATION & TECHNOLOGY	\N	\N	\N	\N	\N	\N
28	29	\N	1	\N	2026-07-24 11:33:53	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	JESSICA CLAUDIA	IMAM FAISAL	\N	\N	\N	\N	\N
29	30	\N	1	\N	2026-07-24 11:33:53	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	LIDWINA DIAH P	\N	\N	\N	\N	\N	\N
30	32	\N	1	\N	2026-07-24 11:33:53	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	DIANA SWY HASNIKA	ADEL	\N	\N	\N	\N	\N
31	34	\N	1	\N	2026-07-24 11:33:53	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	YUHENDI	\N	\N	\N	\N	\N	\N
32	35	\N	1	\N	2026-07-24 11:33:53	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	YUNI SAMSUDIN	\N	\N	\N	\N	\N	\N
33	36	\N	1	\N	2026-07-24 11:33:53	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	DIMAS PRAYOGA W	\N	\N	\N	\N	\N	\N
34	37	\N	1	\N	2026-07-24 11:33:53	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	HARIAN	\N	\N	\N	\N	\N	\N
35	38	\N	1	\N	2026-07-24 11:33:53	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	RIYATUL	NURUL	\N	\N	\N	\N	\N
36	40	\N	1	\N	2026-07-24 11:33:53	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	Pengguna Asset	HARIAN	\N	\N	\N	\N	\N
37	42	\N	1	\N	2026-07-24 11:33:53	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	DIDIK	WAHYU DWI S	\N	\N	\N	\N	\N
38	43	\N	1	\N	2026-07-24 11:33:53	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	REZA RIZKY PERMADI	DEO	\N	\N	\N	\N	\N
39	44	\N	1	\N	2026-07-24 11:33:53	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	OPI		\N	\N	\N	\N	\N
40	45	\N	1	\N	2026-07-24 11:33:53	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:53	2026-07-24 11:33:53	Pengguna Asset	HARIAN	\N	\N	\N	\N	\N
41	46	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	ANISAH	\N	\N	\N	\N	\N	\N
42	47	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	MIRZA NOVIYANTI	EDI PURNOMO	\N	\N	\N	\N	\N
43	48	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	MARISSA SHANTI	GABI KANIA	\N	\N	\N	\N	\N
44	49	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	FATIMAH AZZAHRA	\N	\N	\N	\N	\N	\N
45	50	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	HANIFA RIFQAH	ERLIZA PUTRI U	\N	\N	\N	\N	\N
46	51	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	Pengguna Asset	NUR	\N	\N	\N	\N	\N
47	52	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	JESSICA NOVA SARI	\N	\N	\N	\N	\N	\N
48	53	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	DHIAYUN NAQIYAH	GABI KANIA	\N	\N	\N	\N	\N
49	54	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	SEBASTIANA A	OLGA	\N	\N	\N	\N	\N
50	55	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	RIZKA RAHMAWATI	NILAM	\N	\N	\N	\N	\N
51	56	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	HANNA	ATIQAH	\N	\N	\N	\N	\N
52	58	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	RAKA JENTA	\N	\N	\N	\N	\N	\N
53	59	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	UMAR FATIH	\N	\N	\N	\N	\N	\N
54	60	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	ZIYAH PARAMITA S	\N	\N	\N	\N	\N	\N
55	61	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	RIZKY METIADI	RIANTO	\N	\N	\N	\N	\N
56	62	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	MARIA M R	AMESYA HANAN S	\N	\N	\N	\N	\N
57	63	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	ALDILA WIDYA P	\N	\N	\N	\N	\N	\N
58	68	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	HARPAH NOVIANI	\N	\N	\N	\N	\N	\N
59	69	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	SRI LESTARI	IDA MARSELA	\N	\N	\N	\N	\N
60	70	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	VANIA A W	\N	\N	\N	\N	\N	\N
61	71	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	IT	\N	\N	\N	\N	\N	\N
62	72	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	EDITH	\N	\N	\N	\N	\N	\N
63	73	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	BAYU ANDI S	MITA	\N	\N	\N	\N	\N
64	74	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	TASIYA	\N	\N	\N	\N	\N	\N
65	75	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	IMAX	FARADILLA PUTRI M	\N	\N	\N	\N	\N
66	76	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	GLENN GOUWARDI	ARDIANSYAH	\N	\N	\N	\N	\N
67	79	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	DEWI ELIYANTI	RATU NADHIFA K	\N	\N	\N	\N	\N
68	80	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	ADINDA FATIMAH A	IHSAN GUSWANSYAH	\N	\N	\N	\N	\N
69	81	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	ANINGTYAS	DEWI ELIYANTI	\N	\N	\N	\N	\N
70	82	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	WINDA PUTRI D	ANISA MAULIDA	\N	\N	\N	\N	\N
71	83	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	EKO	ANISA NURUL IZZA	\N	\N	\N	\N	\N
72	84	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	GUSTI RISTA Y	AUDREY GRISEILA	\N	\N	\N	\N	\N
73	85	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	BADZLINA TSAABITAH R	TSALSABILA	\N	\N	\N	\N	\N
74	86	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	REYNARD DZAKY N	BADZLINA TSAABITAH R	\N	\N	\N	\N	\N
75	87	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	AIDAH NURUL H	ANGEL	\N	\N	\N	\N	\N
76	88	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	GABI KANIA	EDI PURNOMO	\N	\N	\N	\N	\N
77	89	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	DIKDIK IRAWAN	DANANG	\N	\N	\N	\N	\N
78	91	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	FEBRI SANTOSO	\N	\N	\N	\N	\N	\N
79	92	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	SAYYIDAH BALQIS	\N	\N	\N	\N	\N	\N
80	93	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	NESHA	FAUZAN	\N	\N	\N	\N	\N
81	94	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	LAURA MALAU	\N	\N	\N	\N	\N	\N
82	95	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	SETIOWATI	JULIANTO PRAJA	\N	\N	\N	\N	\N
83	96	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	BAMBANG YULIANTO	\N	\N	\N	\N	\N	\N
84	97	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	MUHAMMAD IDHAM	\N	\N	\N	\N	\N	\N
86	99	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	RATNO TIMOR	\N	\N	\N	\N	\N	\N
87	100	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	CANTIKA	NADA DEWANDA	\N	\N	\N	\N	\N
88	102	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	HASAN JUNAIDI	RIEZKI ANDITYA	\N	\N	\N	\N	\N
89	103	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	BARIQ NABIL R	HENDRA KURNIAWAN	\N	\N	\N	\N	\N
90	104	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	HANTI SUPARMI	EDY CHANDRA	\N	\N	\N	\N	\N
91	105	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	OKI SUSANTO	YANTO	\N	\N	\N	\N	\N
92	108	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	HR KARSA	LITANIA	\N	\N	\N	\N	\N
93	109	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	SETYADI CANDRAWINATA	\N	\N	\N	\N	\N	\N
94	110	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	RIANTO	INTAN PRATIWI	\N	\N	\N	\N	\N
95	111	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	GEAN GHOFARIO	JUAN CHRISTIAN	\N	\N	\N	\N	\N
96	112	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	ANISAH	TAMI	\N	\N	\N	\N	\N
98	114	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	NESHA	\N	\N	\N	\N	\N	\N
99	115	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	SALFA SALSABILA M	CITRA	\N	\N	\N	\N	\N
100	116	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	Pengguna Asset	SETYADI CANDRAWINATA	\N	\N	\N	\N	\N
101	117	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	FAUZAN HIDAYAT	IHSAN GUSWANSYAH	\N	\N	\N	\N	\N
102	124	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	HARIAN	\N	\N	\N	\N	\N	\N
103	126	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	VERDYANSYAH	\N	\N	\N	\N	\N	\N
104	130	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	CHAHYONO	\N	\N	\N	\N	\N	\N
105	132	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	EDI PURNOMO	\N	\N	\N	\N	\N	\N
106	134	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	NADA DEWANDA	\N	\N	\N	\N	\N	\N
107	135	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	INDRA	TIKA SUMARYA	\N	\N	\N	\N	\N
108	136	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	HARRY	INDRA	\N	\N	\N	\N	\N
109	137	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	ICAD	YOGI	\N	\N	\N	\N	\N
110	138	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	FARHANAN EKA	YOGI	\N	\N	\N	\N	\N
111	139	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	FARHANAN EKA	YOGI	\N	\N	\N	\N	\N
112	140	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	FARHANAN EKA	YOGI	\N	\N	\N	\N	\N
113	141	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	RIZKI METIADI	\N	\N	\N	\N	\N	\N
114	142	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	FARHANAN EKA	\N	\N	\N	\N	\N	\N
115	143	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	FARHANAN EKA	\N	\N	\N	\N	\N	\N
116	144	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	FARHANAN EKA	\N	\N	\N	\N	\N	\N
117	145	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	FARHANAN EKA	\N	\N	\N	\N	\N	\N
118	146	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	FARHANAN EKA	\N	\N	\N	\N	\N	\N
119	147	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	FARHANAN EKA	\N	\N	\N	\N	\N	\N
120	148	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	FARHANAN EKA	\N	\N	\N	\N	\N	\N
121	149	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	FARHANAN EKA	\N	\N	\N	\N	\N	\N
122	150	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	FARHANAN EKA	\N	\N	\N	\N	\N	\N
123	151	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	FARHANAN EKA	\N	\N	\N	\N	\N	\N
124	152	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	FARHANAN EKA	\N	\N	\N	\N	\N	\N
125	153	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	FARHANAN EKA	\N	\N	\N	\N	\N	\N
126	154	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	FARHANAN EKA	\N	\N	\N	\N	\N	\N
127	155	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	FARHANAN EKA	\N	\N	\N	\N	\N	\N
128	156	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	FARHANAN EKA	\N	\N	\N	\N	\N	\N
129	157	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	FARHANAN EKA	\N	\N	\N	\N	\N	\N
130	158	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	RIZKI METIADI	\N	\N	\N	\N	\N	\N
131	160	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	ADIS NAURAH	MAGANG BMS	\N	\N	\N	\N	\N
132	161	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	MJ FADLOAN ASHARI	\N	\N	\N	\N	\N	\N
133	164	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	PICKER		\N	\N	\N	\N	\N
134	165	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	FARHANAN EKA		\N	\N	\N	\N	\N
135	168	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	HENDRIK EVAYANTO	\N	\N	\N	\N	\N	\N
136	170	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	WINDA PUTRI D	\N	\N	\N	\N	\N	\N
137	171	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	WINDA PUTRI D	\N	\N	\N	\N	\N	\N
138	172	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	WINDA PUTRI D	\N	\N	\N	\N	\N	\N
139	173	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	WINDA PUTRI D	\N	\N	\N	\N	\N	\N
140	174	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	WINDA PUTRI D	\N	\N	\N	\N	\N	\N
141	175	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	HASAN JUNAIDI	\N	\N	\N	\N	\N	\N
142	176	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	HASAN JUNAIDI	\N	\N	\N	\N	\N	\N
143	177	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	HASAN JUNAIDI	\N	\N	\N	\N	\N	\N
144	178	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	HASAN JUNAIDI	\N	\N	\N	\N	\N	\N
145	179	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	REZA PERMADI	\N	\N	\N	\N	\N	\N
146	180	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	REZA PERMADI	\N	\N	\N	\N	\N	\N
147	181	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	DIVISI MARKETING	\N	\N	\N	\N	\N	\N
148	182	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	DIVISI FINANCE & ACCOUNTING	\N	\N	\N	\N	\N	\N
149	183	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	DIVISI SALES	\N	\N	\N	\N	\N	\N
150	184	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	DIVISI INFORMATION & TECHNOLOGY	\N	\N	\N	\N	\N	\N
151	185	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	DIVISI INFORMATION & TECHNOLOGY	\N	\N	\N	\N	\N	\N
152	186	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	DIVISI FINANCE & ACCOUNTING	\N	\N	\N	\N	\N	\N
153	187	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	DIVISI MARKETING	\N	\N	\N	\N	\N	\N
154	188	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	DIVISI INFORMATION & TECHNOLOGY	\N	\N	\N	\N	\N	\N
155	189	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	DIVISI HCD	\N	\N	\N	\N	\N	\N
156	190	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	DIVISI HCD	\N	\N	\N	\N	\N	\N
157	191	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	DIVISI INFORMATION & TECHNOLOGY	\N	\N	\N	\N	\N	\N
158	192	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	DIVISI RMPM	\N	\N	\N	\N	\N	\N
159	193	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	DIVISI PRODUKSI	\N	\N	\N	\N	\N	\N
160	194	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	DIVISI INFORMATION & TECHNOLOGY	\N	\N	\N	\N	\N	\N
161	195	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	DIVISI INFORMATION & TECHNOLOGY	\N	\N	\N	\N	\N	\N
162	196	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	DIVISI INFORMATION & TECHNOLOGY	\N	\N	\N	\N	\N	\N
163	197	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	DIVISI INFORMATION & TECHNOLOGY	\N	\N	\N	\N	\N	\N
164	198	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	DIVISI INFORMATION & TECHNOLOGY	\N	\N	\N	\N	\N	\N
165	199	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	DIVISI INFORMATION & TECHNOLOGY	\N	\N	\N	\N	\N	\N
166	200	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	DIVISI RMPM	\N	\N	\N	\N	\N	\N
167	201	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	DIVISI OPERASIONAL	\N	\N	\N	\N	\N	\N
168	202	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	DIVISI DIGITAL MARKETING	\N	\N	\N	\N	\N	\N
169	203	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	DIVISI DIGITAL MARKETING	\N	\N	\N	\N	\N	\N
170	204	\N	1	\N	2026-07-24 11:33:54	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-24 11:33:54	DIVISI DIGITAL MARKETING	\N	\N	\N	\N	\N	\N
171	205	\N	1	\N	2026-07-24 11:33:55	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:55	2026-07-24 11:33:55	DIVISI SALES	\N	\N	\N	\N	\N	\N
172	206	\N	1	\N	2026-07-24 11:33:55	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:55	2026-07-24 11:33:55	DIVISI MARKETING	\N	\N	\N	\N	\N	\N
173	207	\N	1	\N	2026-07-24 11:33:55	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:55	2026-07-24 11:33:55	DIVISI SALES	\N	\N	\N	\N	\N	\N
174	208	\N	1	\N	2026-07-24 11:33:55	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:55	2026-07-24 11:33:55	DIVISI MARKETING	\N	\N	\N	\N	\N	\N
175	209	\N	1	\N	2026-07-24 11:33:55	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:55	2026-07-24 11:33:55	DIVISI OPERASIONAL	DIVISI SALES	\N	\N	\N	\N	\N
176	210	\N	1	\N	2026-07-24 11:33:55	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:55	2026-07-24 11:33:55	BOD	\N	\N	\N	\N	\N	\N
177	211	\N	1	\N	2026-07-24 11:33:55	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:55	2026-07-24 11:33:55	DIVISI MARKETING	\N	\N	\N	\N	\N	\N
178	212	\N	1	\N	2026-07-24 11:33:55	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:55	2026-07-24 11:33:55	DIVISI FACTORY GOODS	\N	\N	\N	\N	\N	\N
179	213	\N	1	\N	2026-07-24 11:33:55	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:55	2026-07-24 11:33:55	DIVISI DIGITAL MARKETING	DIVISI PRODUKSI	\N	\N	\N	\N	\N
180	214	\N	1	\N	2026-07-24 11:33:55	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:55	2026-07-24 11:33:55	DIVISI MAINTENANCE	\N	\N	\N	\N	\N	\N
181	215	\N	1	\N	2026-07-24 11:33:55	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:55	2026-07-24 11:33:55	DIVISI RMPM	\N	\N	\N	\N	\N	\N
182	216	\N	1	\N	2026-07-24 11:33:55	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:55	2026-07-24 11:33:55	DIVISI INFORMATION & TECHNOLOGY	\N	\N	\N	\N	\N	\N
183	217	\N	1	\N	2026-07-24 11:33:55	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:55	2026-07-24 11:33:55	DIVISI OPERASIONAL	\N	\N	\N	\N	\N	\N
184	218	\N	1	\N	2026-07-24 11:33:55	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:55	2026-07-24 11:33:55	DIVISI DIGITAL MARKETING	\N	\N	\N	\N	\N	\N
185	219	\N	1	\N	2026-07-24 11:33:55	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:55	2026-07-24 11:33:55	DIVISI MARKETING	BMS OFFICE	\N	\N	\N	\N	\N
186	220	\N	1	\N	2026-07-24 11:33:55	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:55	2026-07-24 11:33:55	DIVISI OPERASIONAL	\N	\N	\N	\N	\N	\N
187	221	\N	1	\N	2026-07-24 11:33:55	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:55	2026-07-24 11:33:55	DIVISI MARKETING	\N	\N	\N	\N	\N	\N
188	222	\N	1	\N	2026-07-24 11:33:55	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:55	2026-07-24 11:33:55	DIVISI MARKETING	\N	\N	\N	\N	\N	\N
189	223	\N	1	\N	2026-07-24 11:33:55	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:55	2026-07-24 11:33:55	DIVISI MARKETING	\N	\N	\N	\N	\N	\N
190	224	\N	1	\N	2026-07-24 11:33:55	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:55	2026-07-24 11:33:55	DIVISI INFORMATION & TECHNOLOGY	\N	\N	\N	\N	\N	\N
191	225	\N	1	\N	2026-07-24 11:33:55	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:55	2026-07-24 11:33:55	DIVISI FINANCE & ACCOUNTING	\N	\N	\N	\N	\N	\N
192	226	\N	1	\N	2026-07-24 11:33:55	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:55	2026-07-24 11:33:55	DIVISI FINANCE & ACCOUNTING	\N	\N	\N	\N	\N	\N
193	227	\N	1	\N	2026-07-24 11:33:55	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:55	2026-07-24 11:33:55	DIVISI MARKETING	\N	\N	\N	\N	\N	\N
194	228	\N	1	\N	2026-07-24 11:33:55	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:55	2026-07-24 11:33:55	DIVISI RESEARCH N DEVELOPMENT	\N	\N	\N	\N	\N	\N
195	229	\N	1	\N	2026-07-24 11:33:55	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:55	2026-07-24 11:33:55	DIVISI QUALITY CONTROL	\N	\N	\N	\N	\N	\N
196	230	\N	1	\N	2026-07-24 11:33:55	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:55	2026-07-24 11:33:55	DIVISI HCD	\N	\N	\N	\N	\N	\N
197	231	\N	1	\N	2026-07-24 11:33:55	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:55	2026-07-24 11:33:55	DIVISI PRODUKSI	\N	\N	\N	\N	\N	\N
198	232	\N	1	\N	2026-07-24 11:33:55	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:55	2026-07-24 11:33:55	DIVISI MAINTENANCE	\N	\N	\N	\N	\N	\N
199	233	\N	1	\N	2026-07-24 11:33:55	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:55	2026-07-24 11:33:55	DIVISI MAINTENANCE	\N	\N	\N	\N	\N	\N
200	234	\N	1	\N	2026-07-24 11:33:55	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:55	2026-07-24 11:33:55	DIVISI FACTORY GOODS	\N	\N	\N	\N	\N	\N
201	235	\N	1	\N	2026-07-24 11:33:55	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:55	2026-07-24 11:33:55	DIVISI RMPM	\N	\N	\N	\N	\N	\N
202	236	\N	1	\N	2026-07-24 11:33:55	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:55	2026-07-24 11:33:55	DIVISI FACTORY GOODS	\N	\N	\N	\N	\N	\N
203	237	\N	1	\N	2026-07-24 11:33:55	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:55	2026-07-24 11:33:55	DIVISI MAINTENANCE	\N	\N	\N	\N	\N	\N
204	238	\N	1	\N	2026-07-24 11:33:55	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:55	2026-07-24 11:33:55	DIVISI FACTORY GOODS	\N	\N	\N	\N	\N	\N
205	239	\N	1	\N	2026-07-24 11:33:55	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:55	2026-07-24 11:33:55	DIVISI INFORMATION & TECHNOLOGY	\N	\N	\N	\N	\N	\N
206	240	\N	1	\N	2026-07-24 11:33:55	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:55	2026-07-24 11:33:55	DIVISI DIGITAL MARKETING	\N	\N	\N	\N	\N	\N
207	241	\N	1	\N	2026-07-24 11:33:55	\N	Import dari master data Excel/CSV	\N	2026-07-24 11:33:55	2026-07-24 11:33:55	DIVISI DIGITAL MARKETING	\N	\N	\N	\N	\N	\N
208	133	\N	34	\N	2026-07-24 15:35:53	\N	\N	\N	2026-07-24 15:35:53	2026-07-24 15:35:53	Muhamad Farhan	\N	checkout-attachments/01KY9M8J5KTZP5N4Q1JBZ4BBHR.jpg	\N	["checkout-attachments\\/01KY9M8J5KTZP5N4Q1JBZ4BBHR.jpg","checkout-attachments\\/01KY9M8J5QP7ZBC802K4DF60KP.jpg"]	\N	\N
209	242	\N	34	\N	2026-07-27 14:31:39	2026-07-27 15:10:35	Log dari pembuatan asset awal	Automatic checkin due to IT Asset Disposal (DISP/IT/2026/07/001)	2026-07-27 14:31:39	2026-07-27 15:10:35	MUHAMAD FARHAN	\N	\N	\N	\N	\N	\N
97	113	\N	1	34	2026-07-24 11:33:54	2026-07-27 09:37:23	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-29 09:52:41	YANTO	\N	\N	\N	\N	[]	{"layar_status":"baik","layar_notes":"Engsel terdapat internal damage | Terdapat dent di bagian luar","keyboard_status":"baik","keyboard_notes":"Normal","ram_status":"baik","ram_notes":"6GB DDR4","ssd_status":"baik","ssd_notes":"128GB - Perlu peremajaan","trackpad_status":"baik","trackpad_notes":"Normal","baterai_status":"baik","baterai_notes":"Degradasi kapasitas normal","hardware_status":"baik","hardware_notes":"Ryzen 3 3250U","charger_status":"baik","charger_notes":"Tidak dengan kabel charger"}
85	98	\N	1	34	2026-07-24 11:33:54	2026-07-29 13:30:32	Import dari master data Excel/CSV	\N	2026-07-24 11:33:54	2026-07-29 13:30:32	ARDIANSYAH	\N	\N	\N	\N	[]	{"layar_status":"baik","layar_notes":"Normal","keyboard_status":"baik","keyboard_notes":"Normal","ram_status":"baik","ram_notes":"8GB DDR4","ssd_status":"baik","ssd_notes":"256GB | Tidak dapat dibaca","trackpad_status":"baik","trackpad_notes":"Normal","baterai_status":"baik","baterai_notes":"Berfungsi baik","hardware_status":"baik","hardware_notes":"Normal","charger_status":"baik","charger_notes":"Lengkap dengan kabel power"}
\.


--
-- TOC entry 5252 (class 0 OID 41041)
-- Dependencies: 247
-- Data for Name: dispose_asets; Type: TABLE DATA; Schema: public; Owner: ams_user
--

COPY public.dispose_asets (id, disposal_number, disposal_date, asset_id, asset_tag, asset_name, disposal_reason, disposal_type, status, estimated_salvage_value, created_by_name, spv_name, manager_name, ga_recipient_name, attachments, created_by, created_at, updated_at) FROM stdin;
1	DISP/IT/2026/07/001	2026-07-27	242	GTK-100-01-2009	Cusome PC Desktop	UCAK UCAK	destruction	completed	\N	MUHAMAD FARHAN	Supervisor IT	SETYADI CANDRAWINATA	General Affairs (GA)	[]	34	2026-07-27 14:35:44	2026-07-27 15:10:35
\.


--
-- TOC entry 5236 (class 0 OID 16488)
-- Dependencies: 231
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: ams_user
--

COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
\.


--
-- TOC entry 5234 (class 0 OID 16473)
-- Dependencies: 229
-- Data for Name: job_batches; Type: TABLE DATA; Schema: public; Owner: ams_user
--

COPY public.job_batches (id, name, total_jobs, pending_jobs, failed_jobs, failed_job_ids, options, cancelled_at, created_at, finished_at) FROM stdin;
\.


--
-- TOC entry 5233 (class 0 OID 16458)
-- Dependencies: 228
-- Data for Name: jobs; Type: TABLE DATA; Schema: public; Owner: ams_user
--

COPY public.jobs (id, queue, payload, attempts, reserved_at, available_at, created_at) FROM stdin;
\.


--
-- TOC entry 5240 (class 0 OID 16521)
-- Dependencies: 235
-- Data for Name: locations; Type: TABLE DATA; Schema: public; Owner: ams_user
--

COPY public.locations (id, name, address, created_at, updated_at) FROM stdin;
1	FACTORY	Factory Area	2026-07-24 03:20:12	2026-07-24 03:20:12
2	HEAD OFFICE	Head Office Building	2026-07-24 03:20:12	2026-07-24 03:20:12
3	BMS	\N	2026-07-24 03:21:09	2026-07-24 03:21:09
4	AREA	AREA	2026-07-24 11:33:54	2026-07-24 11:33:54
5	CREATIVE	CREATIVE	2026-07-24 11:33:54	2026-07-24 11:33:54
\.


--
-- TOC entry 5225 (class 0 OID 16390)
-- Dependencies: 220
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: ams_user
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	0001_01_01_000000_create_users_table	1
2	0001_01_01_000001_create_cache_table	1
3	0001_01_01_000002_create_jobs_table	1
4	2025_01_01_000001_create_categories_table	1
5	2025_01_01_000002_create_locations_table	1
6	2025_01_01_000003_create_asset_models_table	1
7	2025_01_01_000004_create_assets_table	1
8	2025_01_01_000005_create_checkouts_table	1
9	2025_01_01_000006_add_master_fields_to_assets_table	2
10	2025_01_01_000007_add_users_to_checkouts_table	3
11	2025_01_01_000008_add_attachment_to_checkouts_table	4
12	2025_01_01_000009_update_attachments_to_json_in_checkouts_table	5
13	2025_01_01_000010_add_attachments_to_assets_table	6
14	2025_01_01_000011_add_component_checklist_to_checkouts_table	7
15	2025_01_01_000012_create_berita_acaras_table	8
16	2025_01_01_000013_make_title_nullable_in_berita_acaras_table	9
17	2025_01_01_000014_create_pengajuan_asets_table	10
18	2025_01_01_000015_create_dispose_asets_table	11
19	2025_01_01_000016_add_disposed_to_assets_status_enum	12
20	2025_01_01_000017_create_tickets_table	13
21	2025_01_01_000018_create_asset_maintenance_logs_table	13
22	2025_01_01_000019_add_area_to_pengajuan_asets_table	14
23	2025_01_01_000020_add_operating_system_to_assets_table	15
24	2025_01_01_000021_add_room_notes_to_tickets_table	16
\.


--
-- TOC entry 5228 (class 0 OID 16414)
-- Dependencies: 223
-- Data for Name: password_reset_tokens; Type: TABLE DATA; Schema: public; Owner: ams_user
--

COPY public.password_reset_tokens (email, token, created_at) FROM stdin;
\.


--
-- TOC entry 5250 (class 0 OID 41006)
-- Dependencies: 245
-- Data for Name: pengajuan_asets; Type: TABLE DATA; Schema: public; Owner: ams_user
--

COPY public.pengajuan_asets (id, request_number, request_date, title, requester_name, requester_department, item_type, quantity, priority, status, reason, specification_requested, estimated_cost, approver_name, approver_title, attachments, created_by, created_at, updated_at, area) FROM stdin;
1	REQ/IT/2026/07/001	2026-07-27	SSD NVME M.2 128GB KYO KAIZEN SSD NVMe PCIe Gen3 GARANSI RESMI	BAMBANG YULIANTO	IT	Aksesoris IT	1	medium	pending	Storage untuk laptop	\N	454500.00	SETYADI CANDRAWINATA	\N	["pengajuan-aset-attachments\\/01KYH457ZVBPTCYP6RZGSYQG8D.pdf","pengajuan-aset-attachments\\/01KYH4580KFY4EYREY2JV2PTPS.jpeg"]	34	2026-07-27 13:28:23	2026-07-27 13:28:23	\N
3	REQ/IT/2026/07/003	2026-07-29	Pengajuan SSD 128GB M.2 SATA	MUHAMAD FARHAN	IT	Komponen Utama PC	2	high	pending	Untuk 2 unit laptop Redmibook 15 GTK-02-03-26 & GTK-02-03-29 | \nSSD sebelumnya mengalami kerusakan (Undetected)	SSD M.2 SATA 128GB	409000.00	SETYADI CANDRAWINATA	\N	["pengajuan-aset-attachments\\/01KYP9V7DNVZG41ERBZN1S4STH.jpeg"]	34	2026-07-29 13:43:58	2026-07-29 13:55:51	HO
2	REQ/IT/2026/07/002	2026-07-28	Pengajuan Adapter Power Mikrotik di Buana	MUHAMAD FARHAN	Staff IT	Komponen Utama	1	medium	pending	Adapter Mikrotik untuk BMS, adapter sebelumnya putus digigit tikus	Adapter Mikrotik Output 24V 0,38A	125900.00	SETYADI CANDRAWINATA	\N	["pengajuan-aset-attachments\\/01KYHDYEM086NQSSMBBPT0H4CZ.jpeg"]	34	2026-07-27 16:19:26	2026-07-29 15:56:26	HO
\.


--
-- TOC entry 5229 (class 0 OID 16423)
-- Dependencies: 224
-- Data for Name: sessions; Type: TABLE DATA; Schema: public; Owner: ams_user
--

COPY public.sessions (id, user_id, ip_address, user_agent, payload, last_activity) FROM stdin;
vsExDOz77ntRuoBQKt8DFjF29vXGDVXv36AnHYXv	1	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0	eyJfdG9rZW4iOiJFd2F4REVlZG1FVVdXUmc0V25yMjlSWHJGREVGaUpFUDRGQlY1emlOIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvbG9jYWxob3N0OjgwMDBcL2FkbWluIiwicm91dGUiOiJmaWxhbWVudC5hZG1pbi5wYWdlcy5kYXNoYm9hcmQifSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjEsInBhc3N3b3JkX2hhc2hfd2ViIjoiOTA2YzNhZjljNDhkN2QzYTIwZDg3NDE2ZjRkNjQ4MzRjODNjMzZhNTc2OTE5ZWIwNzIyZmNhM2Y5OWYxODgwOCJ9	1784873737
IUFIakhpJJbx3tNtOUptomcpeiVDXiDtwP9iNfIE	1	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:153.0) Gecko/20100101 Firefox/153.0	eyJfdG9rZW4iOiJMWHBOTkg0OHhaR1dwbHB3TVFuMVRpVTdIc3J1UnV0Zksybmd2OWpxIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAwXC9hZG1pblwvYXNzZXRzIiwicm91dGUiOiJmaWxhbWVudC5hZG1pbi5yZXNvdXJjZXMuYXNzZXRzLmluZGV4In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwidXJsIjpbXSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjEsInBhc3N3b3JkX2hhc2hfd2ViIjoiOTA2YzNhZjljNDhkN2QzYTIwZDg3NDE2ZjRkNjQ4MzRjODNjMzZhNTc2OTE5ZWIwNzIyZmNhM2Y5OWYxODgwOCJ9	1784878103
\.


--
-- TOC entry 5254 (class 0 OID 41080)
-- Dependencies: 249
-- Data for Name: tickets; Type: TABLE DATA; Schema: public; Owner: ams_user
--

COPY public.tickets (id, ticket_number, reporter_name, reporter_department, contact_number, location_id, room, asset_id, asset_tag, asset_name, category, subject, description, scheduled_date, scheduled_time_slot, due_date, priority, assigned_to, assigned_to_name, status, reschedule_reason, resolution_notes, resolved_at, pengajuan_aset_id, dispose_aset_id, berita_acara_id, attachments, created_by, created_at, updated_at, room_notes) FROM stdin;
15	TCK/IT/2026/07/001	Angela Nesha	HCD	085641104041	2	RUANGAN HCD	93	GTK-02-03-21	Custom / General ACER ASPIRE E5-476	hardware	Install driver printer HP P1102	Install driver printer ke laptop	2026-07-28	10:00 - 12:00	2026-07-28	critical	34	\N	resolved	\N	Instalasi driver HP P1102 dari firmware printer	2026-07-28 10:18:00	\N	\N	\N	[]	34	2026-07-28 11:00:37	2026-07-28 11:01:51	\N
19	TCK/IT/2026/07/003	MUHAMAD FARHAN	IT	\N	2	LEMARI IT	113	GTK-02-03-41	Custom / General HP 14-DK1XXX	hardware	Perbaikan Engsel	Diketahui engsel bagian kiri terdapat patahan di bagian engsel. Sehinga dibutuhkan penggeseran baut dari body ke engsel agar bisa menopang berat engsel.\nEngsel bagian kanan juga diberikan satu buah baut agar seimbang	2026-07-28	10:00 - 12:00	2026-07-30	medium	34	\N	resolved	\N	Diketahui engsel bagian kiri terdapat patahan di bagian engsel. Sehinga dibutuhkan penggeseran baut dari body ke engsel agar bisa menopang berat engsel.\nEngsel bagian kanan juga diberikan satu buah baut agar seimbang	2026-07-28 14:30:00	\N	\N	\N	["ticket-attachments\\/01KYKZ0BWY8N56DRTQ5W369CSN.png","ticket-attachments\\/01KYKZ0BX3Y1QT6T6JYBNZSNWN.png","ticket-attachments\\/01KYKZ0BX8JB7ES7HREXWJ4RQY.png"]	34	2026-07-28 15:56:03	2026-07-28 15:56:03	\N
18	TCK/IT/2026/07/002	SAYYIDAH BALQIS	HCD	\N	2	HCD	92	GTK-02-03-20	Custom / General LENOVO 81NA	hardware	Laptop Freeze	Laptop Freeze	2026-07-28	13:00 - 15:00	2026-07-28	critical	34	\N	resolved	\N	Arahkan user untuk "Ctrl + Shift + Esc" agar memunculkan Task manager, File Explorer bisa trigger restart dari sana.\nOS tidak merespon, solusi darurat yaitu menekan tombol power, hingga shutdown. Setelah dilakukan pengecekan, banyak service yang tidak dibutuhkan berjalan.\nInstall HDD Sentinel untuk melihat "Health" dari SSD/HDD. \nDiketahui Health tidak bisa dibaca, Power On Time tidak akurat, ada kemungkinan kerusakan di firmware level. Akibat dari usia pemakaian	2026-07-28 15:00:00	\N	\N	\N	["ticket-attachments\\/01KYKRS6CWRB64AAQCR4DKPDN7.jpg","ticket-attachments\\/01KYKRS6DSVTTE5HE39V00Y2HY.jpg","ticket-attachments\\/01KYKRS6DWH38FSVES0R7MH0GR.jpg","ticket-attachments\\/01KYKVN41G9R2S6MX7H3AEKWDM.jpg"]	34	2026-07-28 14:07:17	2026-07-29 09:36:14	\N
\.


--
-- TOC entry 5227 (class 0 OID 16400)
-- Dependencies: 222
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: ams_user
--

COPY public.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at) FROM stdin;
1	Admin	admin@ams.test	\N	$2y$12$Xw3WSbWvUBkUlHkISGK73uFZ2qgNZK7CaolxGBlAklkXIROVhRjpC	J9tnSUK59N0AgBL81lpY5PZFyWbN7hBPBrS38I0oQ92uHZpuwj5Vgrz51KfG	2026-07-23 04:59:13	2026-07-23 04:59:13
34	MUHAMAD FARHAN	muhamad.farhan@gondowangi.com	\N	$2y$12$4sFkBX99HSp7xqLkiQBI5u3lUbaFhjMHzzuE1wRQ/RzZIz7.2MI/u	VZIuzmZAb6d8LP9GwroOi3spzzlevAThjw6PgrAUMflRRvdgtClwtXEZPFtS	2026-07-24 15:34:32	2026-07-27 10:43:42
\.


--
-- TOC entry 5277 (class 0 OID 0)
-- Dependencies: 250
-- Name: asset_maintenance_logs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ams_user
--

SELECT pg_catalog.setval('public.asset_maintenance_logs_id_seq', 1, false);


--
-- TOC entry 5278 (class 0 OID 0)
-- Dependencies: 236
-- Name: asset_models_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ams_user
--

SELECT pg_catalog.setval('public.asset_models_id_seq', 81, true);


--
-- TOC entry 5279 (class 0 OID 0)
-- Dependencies: 238
-- Name: assets_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ams_user
--

SELECT pg_catalog.setval('public.assets_id_seq', 243, true);


--
-- TOC entry 5280 (class 0 OID 0)
-- Dependencies: 242
-- Name: berita_acaras_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ams_user
--

SELECT pg_catalog.setval('public.berita_acaras_id_seq', 2, true);


--
-- TOC entry 5281 (class 0 OID 0)
-- Dependencies: 232
-- Name: categories_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ams_user
--

SELECT pg_catalog.setval('public.categories_id_seq', 13, true);


--
-- TOC entry 5282 (class 0 OID 0)
-- Dependencies: 240
-- Name: checkouts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ams_user
--

SELECT pg_catalog.setval('public.checkouts_id_seq', 209, true);


--
-- TOC entry 5283 (class 0 OID 0)
-- Dependencies: 246
-- Name: dispose_asets_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ams_user
--

SELECT pg_catalog.setval('public.dispose_asets_id_seq', 1, true);


--
-- TOC entry 5284 (class 0 OID 0)
-- Dependencies: 230
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ams_user
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- TOC entry 5285 (class 0 OID 0)
-- Dependencies: 227
-- Name: jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ams_user
--

SELECT pg_catalog.setval('public.jobs_id_seq', 1, false);


--
-- TOC entry 5286 (class 0 OID 0)
-- Dependencies: 234
-- Name: locations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ams_user
--

SELECT pg_catalog.setval('public.locations_id_seq', 5, true);


--
-- TOC entry 5287 (class 0 OID 0)
-- Dependencies: 219
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ams_user
--

SELECT pg_catalog.setval('public.migrations_id_seq', 24, true);


--
-- TOC entry 5288 (class 0 OID 0)
-- Dependencies: 244
-- Name: pengajuan_asets_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ams_user
--

SELECT pg_catalog.setval('public.pengajuan_asets_id_seq', 3, true);


--
-- TOC entry 5289 (class 0 OID 0)
-- Dependencies: 248
-- Name: tickets_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ams_user
--

SELECT pg_catalog.setval('public.tickets_id_seq', 19, true);


--
-- TOC entry 5290 (class 0 OID 0)
-- Dependencies: 221
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ams_user
--

SELECT pg_catalog.setval('public.users_id_seq', 34, true);


--
-- TOC entry 5052 (class 2606 OID 41158)
-- Name: asset_maintenance_logs asset_maintenance_logs_pkey; Type: CONSTRAINT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.asset_maintenance_logs
    ADD CONSTRAINT asset_maintenance_logs_pkey PRIMARY KEY (id);


--
-- TOC entry 5026 (class 2606 OID 16542)
-- Name: asset_models asset_models_pkey; Type: CONSTRAINT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.asset_models
    ADD CONSTRAINT asset_models_pkey PRIMARY KEY (id);


--
-- TOC entry 5028 (class 2606 OID 16575)
-- Name: assets assets_asset_tag_unique; Type: CONSTRAINT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.assets
    ADD CONSTRAINT assets_asset_tag_unique UNIQUE (asset_tag);


--
-- TOC entry 5030 (class 2606 OID 16562)
-- Name: assets assets_pkey; Type: CONSTRAINT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.assets
    ADD CONSTRAINT assets_pkey PRIMARY KEY (id);


--
-- TOC entry 5036 (class 2606 OID 41004)
-- Name: berita_acaras berita_acaras_letter_number_unique; Type: CONSTRAINT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.berita_acaras
    ADD CONSTRAINT berita_acaras_letter_number_unique UNIQUE (letter_number);


--
-- TOC entry 5038 (class 2606 OID 40992)
-- Name: berita_acaras berita_acaras_pkey; Type: CONSTRAINT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.berita_acaras
    ADD CONSTRAINT berita_acaras_pkey PRIMARY KEY (id);


--
-- TOC entry 5010 (class 2606 OID 16455)
-- Name: cache_locks cache_locks_pkey; Type: CONSTRAINT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);


--
-- TOC entry 5007 (class 2606 OID 16444)
-- Name: cache cache_pkey; Type: CONSTRAINT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);


--
-- TOC entry 5022 (class 2606 OID 16519)
-- Name: categories categories_pkey; Type: CONSTRAINT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.categories
    ADD CONSTRAINT categories_pkey PRIMARY KEY (id);


--
-- TOC entry 5034 (class 2606 OID 16588)
-- Name: checkouts checkouts_pkey; Type: CONSTRAINT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.checkouts
    ADD CONSTRAINT checkouts_pkey PRIMARY KEY (id);


--
-- TOC entry 5044 (class 2606 OID 41077)
-- Name: dispose_asets dispose_asets_disposal_number_unique; Type: CONSTRAINT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.dispose_asets
    ADD CONSTRAINT dispose_asets_disposal_number_unique UNIQUE (disposal_number);


--
-- TOC entry 5046 (class 2606 OID 41065)
-- Name: dispose_asets dispose_asets_pkey; Type: CONSTRAINT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.dispose_asets
    ADD CONSTRAINT dispose_asets_pkey PRIMARY KEY (id);


--
-- TOC entry 5018 (class 2606 OID 16503)
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- TOC entry 5020 (class 2606 OID 16506)
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- TOC entry 5015 (class 2606 OID 16486)
-- Name: job_batches job_batches_pkey; Type: CONSTRAINT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.job_batches
    ADD CONSTRAINT job_batches_pkey PRIMARY KEY (id);


--
-- TOC entry 5012 (class 2606 OID 16471)
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- TOC entry 5024 (class 2606 OID 16530)
-- Name: locations locations_pkey; Type: CONSTRAINT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.locations
    ADD CONSTRAINT locations_pkey PRIMARY KEY (id);


--
-- TOC entry 4994 (class 2606 OID 16398)
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- TOC entry 5000 (class 2606 OID 16422)
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- TOC entry 5040 (class 2606 OID 41032)
-- Name: pengajuan_asets pengajuan_asets_pkey; Type: CONSTRAINT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.pengajuan_asets
    ADD CONSTRAINT pengajuan_asets_pkey PRIMARY KEY (id);


--
-- TOC entry 5042 (class 2606 OID 41039)
-- Name: pengajuan_asets pengajuan_asets_request_number_unique; Type: CONSTRAINT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.pengajuan_asets
    ADD CONSTRAINT pengajuan_asets_request_number_unique UNIQUE (request_number);


--
-- TOC entry 5003 (class 2606 OID 16432)
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- TOC entry 5048 (class 2606 OID 41105)
-- Name: tickets tickets_pkey; Type: CONSTRAINT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.tickets
    ADD CONSTRAINT tickets_pkey PRIMARY KEY (id);


--
-- TOC entry 5050 (class 2606 OID 41142)
-- Name: tickets tickets_ticket_number_unique; Type: CONSTRAINT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.tickets
    ADD CONSTRAINT tickets_ticket_number_unique UNIQUE (ticket_number);


--
-- TOC entry 4996 (class 2606 OID 16413)
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- TOC entry 4998 (class 2606 OID 16411)
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- TOC entry 5031 (class 1259 OID 16573)
-- Name: assets_status_index; Type: INDEX; Schema: public; Owner: ams_user
--

CREATE INDEX assets_status_index ON public.assets USING btree (status);


--
-- TOC entry 5005 (class 1259 OID 16445)
-- Name: cache_expiration_index; Type: INDEX; Schema: public; Owner: ams_user
--

CREATE INDEX cache_expiration_index ON public.cache USING btree (expiration);


--
-- TOC entry 5008 (class 1259 OID 16456)
-- Name: cache_locks_expiration_index; Type: INDEX; Schema: public; Owner: ams_user
--

CREATE INDEX cache_locks_expiration_index ON public.cache_locks USING btree (expiration);


--
-- TOC entry 5032 (class 1259 OID 16609)
-- Name: checkouts_asset_id_checked_in_at_index; Type: INDEX; Schema: public; Owner: ams_user
--

CREATE INDEX checkouts_asset_id_checked_in_at_index ON public.checkouts USING btree (asset_id, checked_in_at);


--
-- TOC entry 5016 (class 1259 OID 16504)
-- Name: failed_jobs_connection_queue_failed_at_index; Type: INDEX; Schema: public; Owner: ams_user
--

CREATE INDEX failed_jobs_connection_queue_failed_at_index ON public.failed_jobs USING btree (connection, queue, failed_at);


--
-- TOC entry 5013 (class 1259 OID 16472)
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: ams_user
--

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


--
-- TOC entry 5001 (class 1259 OID 16434)
-- Name: sessions_last_activity_index; Type: INDEX; Schema: public; Owner: ams_user
--

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);


--
-- TOC entry 5004 (class 1259 OID 16433)
-- Name: sessions_user_id_index; Type: INDEX; Schema: public; Owner: ams_user
--

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


--
-- TOC entry 5072 (class 2606 OID 41159)
-- Name: asset_maintenance_logs asset_maintenance_logs_asset_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.asset_maintenance_logs
    ADD CONSTRAINT asset_maintenance_logs_asset_id_foreign FOREIGN KEY (asset_id) REFERENCES public.assets(id) ON DELETE CASCADE;


--
-- TOC entry 5073 (class 2606 OID 41179)
-- Name: asset_maintenance_logs asset_maintenance_logs_berita_acara_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.asset_maintenance_logs
    ADD CONSTRAINT asset_maintenance_logs_berita_acara_id_foreign FOREIGN KEY (berita_acara_id) REFERENCES public.berita_acaras(id) ON DELETE SET NULL;


--
-- TOC entry 5074 (class 2606 OID 41174)
-- Name: asset_maintenance_logs asset_maintenance_logs_dispose_aset_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.asset_maintenance_logs
    ADD CONSTRAINT asset_maintenance_logs_dispose_aset_id_foreign FOREIGN KEY (dispose_aset_id) REFERENCES public.dispose_asets(id) ON DELETE SET NULL;


--
-- TOC entry 5075 (class 2606 OID 41169)
-- Name: asset_maintenance_logs asset_maintenance_logs_pengajuan_aset_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.asset_maintenance_logs
    ADD CONSTRAINT asset_maintenance_logs_pengajuan_aset_id_foreign FOREIGN KEY (pengajuan_aset_id) REFERENCES public.pengajuan_asets(id) ON DELETE SET NULL;


--
-- TOC entry 5076 (class 2606 OID 41164)
-- Name: asset_maintenance_logs asset_maintenance_logs_ticket_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.asset_maintenance_logs
    ADD CONSTRAINT asset_maintenance_logs_ticket_id_foreign FOREIGN KEY (ticket_id) REFERENCES public.tickets(id) ON DELETE SET NULL;


--
-- TOC entry 5053 (class 2606 OID 16543)
-- Name: asset_models asset_models_category_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.asset_models
    ADD CONSTRAINT asset_models_category_id_foreign FOREIGN KEY (category_id) REFERENCES public.categories(id) ON DELETE CASCADE;


--
-- TOC entry 5054 (class 2606 OID 16563)
-- Name: assets assets_asset_model_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.assets
    ADD CONSTRAINT assets_asset_model_id_foreign FOREIGN KEY (asset_model_id) REFERENCES public.asset_models(id) ON DELETE CASCADE;


--
-- TOC entry 5055 (class 2606 OID 16568)
-- Name: assets assets_location_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.assets
    ADD CONSTRAINT assets_location_id_foreign FOREIGN KEY (location_id) REFERENCES public.locations(id) ON DELETE SET NULL;


--
-- TOC entry 5060 (class 2606 OID 40993)
-- Name: berita_acaras berita_acaras_asset_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.berita_acaras
    ADD CONSTRAINT berita_acaras_asset_id_foreign FOREIGN KEY (asset_id) REFERENCES public.assets(id) ON DELETE SET NULL;


--
-- TOC entry 5061 (class 2606 OID 40998)
-- Name: berita_acaras berita_acaras_created_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.berita_acaras
    ADD CONSTRAINT berita_acaras_created_by_foreign FOREIGN KEY (created_by) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- TOC entry 5056 (class 2606 OID 16589)
-- Name: checkouts checkouts_asset_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.checkouts
    ADD CONSTRAINT checkouts_asset_id_foreign FOREIGN KEY (asset_id) REFERENCES public.assets(id) ON DELETE CASCADE;


--
-- TOC entry 5057 (class 2606 OID 16604)
-- Name: checkouts checkouts_checked_in_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.checkouts
    ADD CONSTRAINT checkouts_checked_in_by_foreign FOREIGN KEY (checked_in_by) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- TOC entry 5058 (class 2606 OID 16599)
-- Name: checkouts checkouts_checked_out_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.checkouts
    ADD CONSTRAINT checkouts_checked_out_by_foreign FOREIGN KEY (checked_out_by) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- TOC entry 5059 (class 2606 OID 24583)
-- Name: checkouts checkouts_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.checkouts
    ADD CONSTRAINT checkouts_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- TOC entry 5063 (class 2606 OID 41066)
-- Name: dispose_asets dispose_asets_asset_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.dispose_asets
    ADD CONSTRAINT dispose_asets_asset_id_foreign FOREIGN KEY (asset_id) REFERENCES public.assets(id) ON DELETE SET NULL;


--
-- TOC entry 5064 (class 2606 OID 41071)
-- Name: dispose_asets dispose_asets_created_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.dispose_asets
    ADD CONSTRAINT dispose_asets_created_by_foreign FOREIGN KEY (created_by) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- TOC entry 5062 (class 2606 OID 41033)
-- Name: pengajuan_asets pengajuan_asets_created_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.pengajuan_asets
    ADD CONSTRAINT pengajuan_asets_created_by_foreign FOREIGN KEY (created_by) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- TOC entry 5065 (class 2606 OID 41111)
-- Name: tickets tickets_asset_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.tickets
    ADD CONSTRAINT tickets_asset_id_foreign FOREIGN KEY (asset_id) REFERENCES public.assets(id) ON DELETE SET NULL;


--
-- TOC entry 5066 (class 2606 OID 41116)
-- Name: tickets tickets_assigned_to_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.tickets
    ADD CONSTRAINT tickets_assigned_to_foreign FOREIGN KEY (assigned_to) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- TOC entry 5067 (class 2606 OID 41131)
-- Name: tickets tickets_berita_acara_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.tickets
    ADD CONSTRAINT tickets_berita_acara_id_foreign FOREIGN KEY (berita_acara_id) REFERENCES public.berita_acaras(id) ON DELETE SET NULL;


--
-- TOC entry 5068 (class 2606 OID 41136)
-- Name: tickets tickets_created_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.tickets
    ADD CONSTRAINT tickets_created_by_foreign FOREIGN KEY (created_by) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- TOC entry 5069 (class 2606 OID 41126)
-- Name: tickets tickets_dispose_aset_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.tickets
    ADD CONSTRAINT tickets_dispose_aset_id_foreign FOREIGN KEY (dispose_aset_id) REFERENCES public.dispose_asets(id) ON DELETE SET NULL;


--
-- TOC entry 5070 (class 2606 OID 41106)
-- Name: tickets tickets_location_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.tickets
    ADD CONSTRAINT tickets_location_id_foreign FOREIGN KEY (location_id) REFERENCES public.locations(id) ON DELETE SET NULL;


--
-- TOC entry 5071 (class 2606 OID 41121)
-- Name: tickets tickets_pengajuan_aset_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ams_user
--

ALTER TABLE ONLY public.tickets
    ADD CONSTRAINT tickets_pengajuan_aset_id_foreign FOREIGN KEY (pengajuan_aset_id) REFERENCES public.pengajuan_asets(id) ON DELETE SET NULL;


--
-- TOC entry 5262 (class 0 OID 0)
-- Dependencies: 5
-- Name: SCHEMA public; Type: ACL; Schema: -; Owner: pg_database_owner
--

GRANT ALL ON SCHEMA public TO ams_user;


-- Completed on 2026-07-30 13:09:09

--
-- PostgreSQL database dump complete
--

\unrestrict 5GheGnbH8lrBJLKIAyETKJHJeaI6JKOhaSYyDIQkWMeUwu2QYfeWDAKy1MjFpUn

