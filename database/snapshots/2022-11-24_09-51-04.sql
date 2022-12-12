--
-- PostgreSQL database dump
--

-- Dumped from database version 13.8 (Debian 13.8-1.pgdg110+1)
-- Dumped by pg_dump version 13.8 (Debian 13.8-0+deb11u1)

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

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: action_events; Type: TABLE; Schema: public; Owner: default
--

CREATE TABLE public.action_events (
    id bigint NOT NULL,
    batch_id character(36) NOT NULL,
    user_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    actionable_type character varying(255) NOT NULL,
    actionable_id bigint NOT NULL,
    target_type character varying(255) NOT NULL,
    target_id bigint NOT NULL,
    model_type character varying(255) NOT NULL,
    model_id bigint,
    fields text NOT NULL,
    status character varying(25) DEFAULT 'running'::character varying NOT NULL,
    exception text NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    original text,
    changes text
);


ALTER TABLE public.action_events OWNER TO "default";

--
-- Name: action_events_id_seq; Type: SEQUENCE; Schema: public; Owner: default
--

CREATE SEQUENCE public.action_events_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.action_events_id_seq OWNER TO "default";

--
-- Name: action_events_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: default
--

ALTER SEQUENCE public.action_events_id_seq OWNED BY public.action_events.id;


--
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: default
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


ALTER TABLE public.failed_jobs OWNER TO "default";

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: default
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.failed_jobs_id_seq OWNER TO "default";

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: default
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: default
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO "default";

--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: default
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.migrations_id_seq OWNER TO "default";

--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: default
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: nova_notifications; Type: TABLE; Schema: public; Owner: default
--

CREATE TABLE public.nova_notifications (
    id uuid NOT NULL,
    type character varying(255) NOT NULL,
    notifiable_type character varying(255) NOT NULL,
    notifiable_id bigint NOT NULL,
    data text NOT NULL,
    read_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.nova_notifications OWNER TO "default";

--
-- Name: password_resets; Type: TABLE; Schema: public; Owner: default
--

CREATE TABLE public.password_resets (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


ALTER TABLE public.password_resets OWNER TO "default";

--
-- Name: personal_access_tokens; Type: TABLE; Schema: public; Owner: default
--

CREATE TABLE public.personal_access_tokens (
    id bigint NOT NULL,
    tokenable_type character varying(255) NOT NULL,
    tokenable_id bigint NOT NULL,
    name character varying(255) NOT NULL,
    token character varying(64) NOT NULL,
    abilities text,
    last_used_at timestamp(0) without time zone,
    expires_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.personal_access_tokens OWNER TO "default";

--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE; Schema: public; Owner: default
--

CREATE SEQUENCE public.personal_access_tokens_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.personal_access_tokens_id_seq OWNER TO "default";

--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: default
--

ALTER SEQUENCE public.personal_access_tokens_id_seq OWNED BY public.personal_access_tokens.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: default
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    is_admin character varying(1) DEFAULT '0'::character varying NOT NULL
);


ALTER TABLE public.users OWNER TO "default";

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: default
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_id_seq OWNER TO "default";

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: default
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: wainwright_bgaming_bonusgames; Type: TABLE; Schema: public; Owner: default
--

CREATE TABLE public.wainwright_bgaming_bonusgames (
    id bigint NOT NULL,
    bonusgame_token character varying(100) NOT NULL,
    player_id character varying(100) NOT NULL,
    game_id character varying(100) NOT NULL,
    game_event json NOT NULL,
    init_event json NOT NULL,
    replayed boolean NOT NULL,
    active boolean NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.wainwright_bgaming_bonusgames OWNER TO "default";

--
-- Name: wainwright_bgaming_bonusgames_id_seq; Type: SEQUENCE; Schema: public; Owner: default
--

CREATE SEQUENCE public.wainwright_bgaming_bonusgames_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.wainwright_bgaming_bonusgames_id_seq OWNER TO "default";

--
-- Name: wainwright_bgaming_bonusgames_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: default
--

ALTER SEQUENCE public.wainwright_bgaming_bonusgames_id_seq OWNED BY public.wainwright_bgaming_bonusgames.id;


--
-- Name: wainwright_casino_profiles; Type: TABLE; Schema: public; Owner: default
--

CREATE TABLE public.wainwright_casino_profiles (
    id bigint NOT NULL,
    player_id character varying(100) NOT NULL,
    user_id character varying(100) NOT NULL,
    currency character varying(100) DEFAULT 'USD'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.wainwright_casino_profiles OWNER TO "default";

--
-- Name: wainwright_casino_profiles_id_seq; Type: SEQUENCE; Schema: public; Owner: default
--

CREATE SEQUENCE public.wainwright_casino_profiles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.wainwright_casino_profiles_id_seq OWNER TO "default";

--
-- Name: wainwright_casino_profiles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: default
--

ALTER SEQUENCE public.wainwright_casino_profiles_id_seq OWNED BY public.wainwright_casino_profiles.id;


--
-- Name: wainwright_crawlerdata; Type: TABLE; Schema: public; Owner: default
--

CREATE TABLE public.wainwright_crawlerdata (
    id bigint NOT NULL,
    url character varying(150) NOT NULL,
    state character varying(50) NOT NULL,
    state_message character varying(300) NOT NULL,
    extra_id character varying(50) DEFAULT '0'::character varying NOT NULL,
    type character varying(100) DEFAULT '[]'::character varying NOT NULL,
    result json NOT NULL,
    expired_bool boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.wainwright_crawlerdata OWNER TO "default";

--
-- Name: wainwright_crawlerdata_id_seq; Type: SEQUENCE; Schema: public; Owner: default
--

CREATE SEQUENCE public.wainwright_crawlerdata_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.wainwright_crawlerdata_id_seq OWNER TO "default";

--
-- Name: wainwright_crawlerdata_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: default
--

ALTER SEQUENCE public.wainwright_crawlerdata_id_seq OWNED BY public.wainwright_crawlerdata.id;


--
-- Name: wainwright_datalogger; Type: TABLE; Schema: public; Owner: default
--

CREATE TABLE public.wainwright_datalogger (
    id bigint NOT NULL,
    uuid character varying(100) NOT NULL,
    type character varying(100) NOT NULL,
    data json NOT NULL,
    extra_data json NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.wainwright_datalogger OWNER TO "default";

--
-- Name: wainwright_datalogger_id_seq; Type: SEQUENCE; Schema: public; Owner: default
--

CREATE SEQUENCE public.wainwright_datalogger_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.wainwright_datalogger_id_seq OWNER TO "default";

--
-- Name: wainwright_datalogger_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: default
--

ALTER SEQUENCE public.wainwright_datalogger_id_seq OWNED BY public.wainwright_datalogger.id;


--
-- Name: wainwright_freespins; Type: TABLE; Schema: public; Owner: default
--

CREATE TABLE public.wainwright_freespins (
    id bigint NOT NULL,
    player_id character varying(200) NOT NULL,
    player_operator_id character varying(200),
    game_id character varying(150),
    total_spins character varying(150),
    spins_left character varying(150),
    operator_key character varying(150),
    total_win character varying(150) DEFAULT '0.00'::character varying NOT NULL,
    currency character varying(150) DEFAULT 'USD'::character varying NOT NULL,
    bet_amount character varying(150),
    operator_id character varying(150),
    expiration_stamp character varying(150),
    active boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.wainwright_freespins OWNER TO "default";

--
-- Name: wainwright_freespins_id_seq; Type: SEQUENCE; Schema: public; Owner: default
--

CREATE SEQUENCE public.wainwright_freespins_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.wainwright_freespins_id_seq OWNER TO "default";

--
-- Name: wainwright_freespins_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: default
--

ALTER SEQUENCE public.wainwright_freespins_id_seq OWNED BY public.wainwright_freespins.id;


--
-- Name: wainwright_gamerespin_template; Type: TABLE; Schema: public; Owner: default
--

CREATE TABLE public.wainwright_gamerespin_template (
    id bigint NOT NULL,
    gid character varying(200) NOT NULL,
    game_data character varying(16000) NOT NULL,
    game_type character varying(200) NOT NULL,
    enabled boolean NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.wainwright_gamerespin_template OWNER TO "default";

--
-- Name: wainwright_gamerespin_template_id_seq; Type: SEQUENCE; Schema: public; Owner: default
--

CREATE SEQUENCE public.wainwright_gamerespin_template_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.wainwright_gamerespin_template_id_seq OWNER TO "default";

--
-- Name: wainwright_gamerespin_template_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: default
--

ALTER SEQUENCE public.wainwright_gamerespin_template_id_seq OWNED BY public.wainwright_gamerespin_template.id;


--
-- Name: wainwright_games_thumbnails; Type: TABLE; Schema: public; Owner: default
--

CREATE TABLE public.wainwright_games_thumbnails (
    id bigint NOT NULL,
    img_gid character varying(100) NOT NULL,
    img_url character varying(300) NOT NULL,
    img_ext character varying(100) NOT NULL,
    "ownedBy" character varying(100) NOT NULL,
    active integer DEFAULT 1 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.wainwright_games_thumbnails OWNER TO "default";

--
-- Name: wainwright_games_thumbnails_id_seq; Type: SEQUENCE; Schema: public; Owner: default
--

CREATE SEQUENCE public.wainwright_games_thumbnails_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.wainwright_games_thumbnails_id_seq OWNER TO "default";

--
-- Name: wainwright_games_thumbnails_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: default
--

ALTER SEQUENCE public.wainwright_games_thumbnails_id_seq OWNED BY public.wainwright_games_thumbnails.id;


--
-- Name: wainwright_gameslist; Type: TABLE; Schema: public; Owner: default
--

CREATE TABLE public.wainwright_gameslist (
    id bigint NOT NULL,
    gid character varying(100) NOT NULL,
    gid_extra character varying(200),
    batch character varying(100) NOT NULL,
    slug character varying(100) NOT NULL,
    name character varying(100) NOT NULL,
    provider character varying(100) NOT NULL,
    type character varying(100) NOT NULL,
    "typeRating" character varying(100) NOT NULL,
    popularity character varying(100) NOT NULL,
    bonusbuy integer DEFAULT 0 NOT NULL,
    jackpot integer DEFAULT 0 NOT NULL,
    demoplay integer DEFAULT 1 NOT NULL,
    demolink character varying(455),
    origin_demolink character varying(455) NOT NULL,
    image character varying(300),
    source character varying(50) NOT NULL,
    source_schema character varying(50) NOT NULL,
    method character varying(50) NOT NULL,
    realmoney json NOT NULL,
    enabled boolean NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.wainwright_gameslist OWNER TO "default";

--
-- Name: wainwright_gameslist_id_seq; Type: SEQUENCE; Schema: public; Owner: default
--

CREATE SEQUENCE public.wainwright_gameslist_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.wainwright_gameslist_id_seq OWNER TO "default";

--
-- Name: wainwright_gameslist_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: default
--

ALTER SEQUENCE public.wainwright_gameslist_id_seq OWNED BY public.wainwright_gameslist.id;


--
-- Name: wainwright_gameslist_raw; Type: TABLE; Schema: public; Owner: default
--

CREATE TABLE public.wainwright_gameslist_raw (
    id bigint NOT NULL,
    gid character varying(100) NOT NULL,
    batch character varying(100) NOT NULL,
    slug character varying(100) NOT NULL,
    name character varying(100) NOT NULL,
    provider character varying(100) NOT NULL,
    type character varying(100) NOT NULL,
    "typeRating" character varying(100) NOT NULL,
    popularity character varying(100) NOT NULL,
    bonusbuy integer DEFAULT 0 NOT NULL,
    jackpot integer DEFAULT 0 NOT NULL,
    demoplay integer DEFAULT 1 NOT NULL,
    demolink character varying(255),
    origin_demolink character varying(455) NOT NULL,
    source character varying(50) NOT NULL,
    state character varying(60) DEFAULT 'NEW'::character varying NOT NULL,
    source_schema character varying(50) NOT NULL,
    realmoney json NOT NULL,
    rawobject json NOT NULL,
    mark_transfer boolean NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.wainwright_gameslist_raw OWNER TO "default";

--
-- Name: wainwright_gameslist_raw_id_seq; Type: SEQUENCE; Schema: public; Owner: default
--

CREATE SEQUENCE public.wainwright_gameslist_raw_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.wainwright_gameslist_raw_id_seq OWNER TO "default";

--
-- Name: wainwright_gameslist_raw_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: default
--

ALTER SEQUENCE public.wainwright_gameslist_raw_id_seq OWNED BY public.wainwright_gameslist_raw.id;


--
-- Name: wainwright_job_gameimporter; Type: TABLE; Schema: public; Owner: default
--

CREATE TABLE public.wainwright_job_gameimporter (
    id bigint NOT NULL,
    link character varying(200) NOT NULL,
    filter_key character varying(100),
    filter_value character varying(150),
    schema character varying(35) DEFAULT 'softswiss'::character varying NOT NULL,
    state character varying(35) DEFAULT 'JOB_QUEUED'::character varying NOT NULL,
    state_message character varying(3500) DEFAULT 'N/A'::character varying NOT NULL,
    proxy boolean DEFAULT true NOT NULL,
    imported_games character varying(25) DEFAULT '0'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.wainwright_job_gameimporter OWNER TO "default";

--
-- Name: wainwright_job_gameimporter_id_seq; Type: SEQUENCE; Schema: public; Owner: default
--

CREATE SEQUENCE public.wainwright_job_gameimporter_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.wainwright_job_gameimporter_id_seq OWNER TO "default";

--
-- Name: wainwright_job_gameimporter_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: default
--

ALTER SEQUENCE public.wainwright_job_gameimporter_id_seq OWNED BY public.wainwright_job_gameimporter.id;


--
-- Name: wainwright_metadata; Type: TABLE; Schema: public; Owner: default
--

CREATE TABLE public.wainwright_metadata (
    id bigint NOT NULL,
    key character varying(100) NOT NULL,
    type character varying(100),
    value character varying(100),
    extended_key character varying(100),
    object_data json NOT NULL,
    active boolean NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.wainwright_metadata OWNER TO "default";

--
-- Name: wainwright_metadata_id_seq; Type: SEQUENCE; Schema: public; Owner: default
--

CREATE SEQUENCE public.wainwright_metadata_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.wainwright_metadata_id_seq OWNER TO "default";

--
-- Name: wainwright_metadata_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: default
--

ALTER SEQUENCE public.wainwright_metadata_id_seq OWNED BY public.wainwright_metadata.id;


--
-- Name: wainwright_operator_access; Type: TABLE; Schema: public; Owner: default
--

CREATE TABLE public.wainwright_operator_access (
    id bigint NOT NULL,
    operator_key character varying(100) NOT NULL,
    operator_secret character varying(100) DEFAULT '9999'::character varying,
    operator_access character varying(100) DEFAULT '127.0.0.1'::character varying,
    callback_url character varying(100) NOT NULL,
    active boolean NOT NULL,
    "ownedBy" bigint NOT NULL,
    last_used_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.wainwright_operator_access OWNER TO "default";

--
-- Name: wainwright_operator_access_id_seq; Type: SEQUENCE; Schema: public; Owner: default
--

CREATE SEQUENCE public.wainwright_operator_access_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.wainwright_operator_access_id_seq OWNER TO "default";

--
-- Name: wainwright_operator_access_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: default
--

ALTER SEQUENCE public.wainwright_operator_access_id_seq OWNED BY public.wainwright_operator_access.id;


--
-- Name: wainwright_operator_disabled_games; Type: TABLE; Schema: public; Owner: default
--

CREATE TABLE public.wainwright_operator_disabled_games (
    id bigint NOT NULL,
    gid character varying(100) NOT NULL,
    extra character varying(100) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.wainwright_operator_disabled_games OWNER TO "default";

--
-- Name: wainwright_operator_disabled_games_id_seq; Type: SEQUENCE; Schema: public; Owner: default
--

CREATE SEQUENCE public.wainwright_operator_disabled_games_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.wainwright_operator_disabled_games_id_seq OWNER TO "default";

--
-- Name: wainwright_operator_disabled_games_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: default
--

ALTER SEQUENCE public.wainwright_operator_disabled_games_id_seq OWNED BY public.wainwright_operator_disabled_games.id;


--
-- Name: wainwright_operator_gameslist; Type: TABLE; Schema: public; Owner: default
--

CREATE TABLE public.wainwright_operator_gameslist (
    id bigint NOT NULL,
    gid character varying(100) NOT NULL,
    gid_extra character varying(200),
    batch character varying(100) NOT NULL,
    slug character varying(100) NOT NULL,
    name character varying(100) NOT NULL,
    provider character varying(100) NOT NULL,
    type character varying(100) NOT NULL,
    "typeRating" character varying(100) NOT NULL,
    popularity character varying(100) NOT NULL,
    bonusbuy integer DEFAULT 0 NOT NULL,
    jackpot integer DEFAULT 0 NOT NULL,
    demoplay integer DEFAULT 1 NOT NULL,
    demolink character varying(455),
    origin_demolink character varying(455) NOT NULL,
    image character varying(300),
    source character varying(50) NOT NULL,
    source_schema character varying(50) NOT NULL,
    method character varying(50) NOT NULL,
    realmoney json NOT NULL,
    enabled boolean NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.wainwright_operator_gameslist OWNER TO "default";

--
-- Name: wainwright_operator_gameslist_id_seq; Type: SEQUENCE; Schema: public; Owner: default
--

CREATE SEQUENCE public.wainwright_operator_gameslist_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.wainwright_operator_gameslist_id_seq OWNER TO "default";

--
-- Name: wainwright_operator_gameslist_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: default
--

ALTER SEQUENCE public.wainwright_operator_gameslist_id_seq OWNED BY public.wainwright_operator_gameslist.id;


--
-- Name: wainwright_operator_transactions; Type: TABLE; Schema: public; Owner: default
--

CREATE TABLE public.wainwright_operator_transactions (
    id bigint NOT NULL,
    gid character varying(100) NOT NULL,
    player_id character varying(100) NOT NULL,
    type character varying(100) NOT NULL,
    change character varying(100) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.wainwright_operator_transactions OWNER TO "default";

--
-- Name: wainwright_operator_transactions_id_seq; Type: SEQUENCE; Schema: public; Owner: default
--

CREATE SEQUENCE public.wainwright_operator_transactions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.wainwright_operator_transactions_id_seq OWNER TO "default";

--
-- Name: wainwright_operator_transactions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: default
--

ALTER SEQUENCE public.wainwright_operator_transactions_id_seq OWNED BY public.wainwright_operator_transactions.id;


--
-- Name: wainwright_parent_sessions; Type: TABLE; Schema: public; Owner: default
--

CREATE TABLE public.wainwright_parent_sessions (
    id bigint NOT NULL,
    game_id character varying(100) NOT NULL,
    game_provider character varying(100) NOT NULL,
    player_id character varying(100),
    player_operator_id character varying(100),
    currency character varying(20) DEFAULT 'USD'::character varying NOT NULL,
    game_id_original character varying(355) NOT NULL,
    token_internal character varying(255) NOT NULL,
    token_original character varying(355) NOT NULL,
    token_original_bridge character varying(355) NOT NULL,
    state character varying(155) NOT NULL,
    operator_id character varying(255) NOT NULL,
    extra_meta json NOT NULL,
    user_agent json NOT NULL,
    expired_bool boolean NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.wainwright_parent_sessions OWNER TO "default";

--
-- Name: wainwright_parent_sessions_id_seq; Type: SEQUENCE; Schema: public; Owner: default
--

CREATE SEQUENCE public.wainwright_parent_sessions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.wainwright_parent_sessions_id_seq OWNER TO "default";

--
-- Name: wainwright_parent_sessions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: default
--

ALTER SEQUENCE public.wainwright_parent_sessions_id_seq OWNED BY public.wainwright_parent_sessions.id;


--
-- Name: wainwright_player_balances; Type: TABLE; Schema: public; Owner: default
--

CREATE TABLE public.wainwright_player_balances (
    id bigint NOT NULL,
    player_id character varying(100) NOT NULL,
    player_name character varying(100),
    currency character varying(100) DEFAULT 'USD'::character varying NOT NULL,
    balance character varying(100) DEFAULT '0'::character varying,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.wainwright_player_balances OWNER TO "default";

--
-- Name: wainwright_player_balances_id_seq; Type: SEQUENCE; Schema: public; Owner: default
--

CREATE SEQUENCE public.wainwright_player_balances_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.wainwright_player_balances_id_seq OWNER TO "default";

--
-- Name: wainwright_player_balances_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: default
--

ALTER SEQUENCE public.wainwright_player_balances_id_seq OWNED BY public.wainwright_player_balances.id;


--
-- Name: action_events id; Type: DEFAULT; Schema: public; Owner: default
--

ALTER TABLE ONLY public.action_events ALTER COLUMN id SET DEFAULT nextval('public.action_events_id_seq'::regclass);


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: default
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: default
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: personal_access_tokens id; Type: DEFAULT; Schema: public; Owner: default
--

ALTER TABLE ONLY public.personal_access_tokens ALTER COLUMN id SET DEFAULT nextval('public.personal_access_tokens_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: default
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Name: wainwright_bgaming_bonusgames id; Type: DEFAULT; Schema: public; Owner: default
--

ALTER TABLE ONLY public.wainwright_bgaming_bonusgames ALTER COLUMN id SET DEFAULT nextval('public.wainwright_bgaming_bonusgames_id_seq'::regclass);


--
-- Name: wainwright_casino_profiles id; Type: DEFAULT; Schema: public; Owner: default
--

ALTER TABLE ONLY public.wainwright_casino_profiles ALTER COLUMN id SET DEFAULT nextval('public.wainwright_casino_profiles_id_seq'::regclass);


--
-- Name: wainwright_crawlerdata id; Type: DEFAULT; Schema: public; Owner: default
--

ALTER TABLE ONLY public.wainwright_crawlerdata ALTER COLUMN id SET DEFAULT nextval('public.wainwright_crawlerdata_id_seq'::regclass);


--
-- Name: wainwright_datalogger id; Type: DEFAULT; Schema: public; Owner: default
--

ALTER TABLE ONLY public.wainwright_datalogger ALTER COLUMN id SET DEFAULT nextval('public.wainwright_datalogger_id_seq'::regclass);


--
-- Name: wainwright_freespins id; Type: DEFAULT; Schema: public; Owner: default
--

ALTER TABLE ONLY public.wainwright_freespins ALTER COLUMN id SET DEFAULT nextval('public.wainwright_freespins_id_seq'::regclass);


--
-- Name: wainwright_gamerespin_template id; Type: DEFAULT; Schema: public; Owner: default
--

ALTER TABLE ONLY public.wainwright_gamerespin_template ALTER COLUMN id SET DEFAULT nextval('public.wainwright_gamerespin_template_id_seq'::regclass);


--
-- Name: wainwright_games_thumbnails id; Type: DEFAULT; Schema: public; Owner: default
--

ALTER TABLE ONLY public.wainwright_games_thumbnails ALTER COLUMN id SET DEFAULT nextval('public.wainwright_games_thumbnails_id_seq'::regclass);


--
-- Name: wainwright_gameslist id; Type: DEFAULT; Schema: public; Owner: default
--

ALTER TABLE ONLY public.wainwright_gameslist ALTER COLUMN id SET DEFAULT nextval('public.wainwright_gameslist_id_seq'::regclass);


--
-- Name: wainwright_gameslist_raw id; Type: DEFAULT; Schema: public; Owner: default
--

ALTER TABLE ONLY public.wainwright_gameslist_raw ALTER COLUMN id SET DEFAULT nextval('public.wainwright_gameslist_raw_id_seq'::regclass);


--
-- Name: wainwright_job_gameimporter id; Type: DEFAULT; Schema: public; Owner: default
--

ALTER TABLE ONLY public.wainwright_job_gameimporter ALTER COLUMN id SET DEFAULT nextval('public.wainwright_job_gameimporter_id_seq'::regclass);


--
-- Name: wainwright_metadata id; Type: DEFAULT; Schema: public; Owner: default
--

ALTER TABLE ONLY public.wainwright_metadata ALTER COLUMN id SET DEFAULT nextval('public.wainwright_metadata_id_seq'::regclass);


--
-- Name: wainwright_operator_access id; Type: DEFAULT; Schema: public; Owner: default
--

ALTER TABLE ONLY public.wainwright_operator_access ALTER COLUMN id SET DEFAULT nextval('public.wainwright_operator_access_id_seq'::regclass);


--
-- Name: wainwright_operator_disabled_games id; Type: DEFAULT; Schema: public; Owner: default
--

ALTER TABLE ONLY public.wainwright_operator_disabled_games ALTER COLUMN id SET DEFAULT nextval('public.wainwright_operator_disabled_games_id_seq'::regclass);


--
-- Name: wainwright_operator_gameslist id; Type: DEFAULT; Schema: public; Owner: default
--

ALTER TABLE ONLY public.wainwright_operator_gameslist ALTER COLUMN id SET DEFAULT nextval('public.wainwright_operator_gameslist_id_seq'::regclass);


--
-- Name: wainwright_operator_transactions id; Type: DEFAULT; Schema: public; Owner: default
--

ALTER TABLE ONLY public.wainwright_operator_transactions ALTER COLUMN id SET DEFAULT nextval('public.wainwright_operator_transactions_id_seq'::regclass);


--
-- Name: wainwright_parent_sessions id; Type: DEFAULT; Schema: public; Owner: default
--

ALTER TABLE ONLY public.wainwright_parent_sessions ALTER COLUMN id SET DEFAULT nextval('public.wainwright_parent_sessions_id_seq'::regclass);


--
-- Name: wainwright_player_balances id; Type: DEFAULT; Schema: public; Owner: default
--

ALTER TABLE ONLY public.wainwright_player_balances ALTER COLUMN id SET DEFAULT nextval('public.wainwright_player_balances_id_seq'::regclass);


--
-- Data for Name: action_events; Type: TABLE DATA; Schema: public; Owner: default
--



--
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: default
--



--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: default
--

INSERT INTO public.migrations VALUES (1, '2014_10_12_000000_create_users_table', 1);
INSERT INTO public.migrations VALUES (2, '2014_10_12_100000_create_password_resets_table', 1);
INSERT INTO public.migrations VALUES (3, '2018_01_01_000000_create_action_events_table', 1);
INSERT INTO public.migrations VALUES (4, '2019_05_10_000000_add_fields_to_action_events_table', 1);
INSERT INTO public.migrations VALUES (5, '2019_08_19_000000_create_failed_jobs_table', 1);
INSERT INTO public.migrations VALUES (6, '2019_12_14_000001_create_personal_access_tokens_table', 1);
INSERT INTO public.migrations VALUES (7, '2021_08_25_193039_create_nova_notifications_table', 1);
INSERT INTO public.migrations VALUES (8, '2022_04_26_000000_add_fields_to_nova_notifications_table', 1);
INSERT INTO public.migrations VALUES (9, '2022_11_24_045454_create_freebets_table', 1);
INSERT INTO public.migrations VALUES (10, '2022_11_24_045455_create_gamerespin_template_table', 1);
INSERT INTO public.migrations VALUES (11, '2022_11_24_045456_create_games_thumbnails', 1);
INSERT INTO public.migrations VALUES (12, '2022_11_24_045457_create_bgaming_bonusgames_table', 1);
INSERT INTO public.migrations VALUES (13, '2022_11_24_045458_modify_users_table', 1);
INSERT INTO public.migrations VALUES (14, '2022_11_24_045459_create_crawlerdata_table', 1);
INSERT INTO public.migrations VALUES (15, '2022_11_24_045500_create_game_importer_job', 1);
INSERT INTO public.migrations VALUES (16, '2022_11_24_045501_create_datalogger_table', 1);
INSERT INTO public.migrations VALUES (17, '2022_11_24_045502_create_gameslist_table', 1);
INSERT INTO public.migrations VALUES (18, '2022_11_24_045503_create_metadata_table', 1);
INSERT INTO public.migrations VALUES (19, '2022_11_24_045504_create_parent_sessions', 1);
INSERT INTO public.migrations VALUES (20, '2022_11_24_045505_create_rawgameslist_table', 1);
INSERT INTO public.migrations VALUES (21, '2022_11_24_045506_create_operatoraccess_table', 1);
INSERT INTO public.migrations VALUES (22, '2022_11_24_053532_create_operator_transactions_table', 1);
INSERT INTO public.migrations VALUES (23, '2022_11_24_053533_create_operator_disabled_games_table', 1);
INSERT INTO public.migrations VALUES (24, '2022_11_24_053534_create_casino_profiles_table', 1);
INSERT INTO public.migrations VALUES (25, '2022_11_24_053535_create_playerbalances_table', 1);
INSERT INTO public.migrations VALUES (26, '2022_11_24_053536_create_operator_gameslist_table', 1);


--
-- Data for Name: nova_notifications; Type: TABLE DATA; Schema: public; Owner: default
--



--
-- Data for Name: password_resets; Type: TABLE DATA; Schema: public; Owner: default
--



--
-- Data for Name: personal_access_tokens; Type: TABLE DATA; Schema: public; Owner: default
--



--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: default
--

INSERT INTO public.users VALUES (1, 'admin', 'admin@casinoman.app', NULL, '$2y$10$g5P0msNLuzjLCFsobqnAquW3cGKwzFgHa711/GeoyWBtdN6pJMVBm', NULL, NULL, NULL, '1');


--
-- Data for Name: wainwright_bgaming_bonusgames; Type: TABLE DATA; Schema: public; Owner: default
--



--
-- Data for Name: wainwright_casino_profiles; Type: TABLE DATA; Schema: public; Owner: default
--



--
-- Data for Name: wainwright_crawlerdata; Type: TABLE DATA; Schema: public; Owner: default
--



--
-- Data for Name: wainwright_datalogger; Type: TABLE DATA; Schema: public; Owner: default
--



--
-- Data for Name: wainwright_freespins; Type: TABLE DATA; Schema: public; Owner: default
--



--
-- Data for Name: wainwright_gamerespin_template; Type: TABLE DATA; Schema: public; Owner: default
--



--
-- Data for Name: wainwright_games_thumbnails; Type: TABLE DATA; Schema: public; Owner: default
--



--
-- Data for Name: wainwright_gameslist; Type: TABLE DATA; Schema: public; Owner: default
--



--
-- Data for Name: wainwright_gameslist_raw; Type: TABLE DATA; Schema: public; Owner: default
--



--
-- Data for Name: wainwright_job_gameimporter; Type: TABLE DATA; Schema: public; Owner: default
--



--
-- Data for Name: wainwright_metadata; Type: TABLE DATA; Schema: public; Owner: default
--



--
-- Data for Name: wainwright_operator_access; Type: TABLE DATA; Schema: public; Owner: default
--

INSERT INTO public.wainwright_operator_access VALUES (1, '73fbf02392260c47ea737c7db7a5f2f3', '49f481a95', '127.0.0.1', 'https://ns363376.ip-91-121-179.eu/allseeingdavid', true, 1, NULL, NULL, NULL);


--
-- Data for Name: wainwright_operator_disabled_games; Type: TABLE DATA; Schema: public; Owner: default
--



--
-- Data for Name: wainwright_operator_gameslist; Type: TABLE DATA; Schema: public; Owner: default
--



--
-- Data for Name: wainwright_operator_transactions; Type: TABLE DATA; Schema: public; Owner: default
--



--
-- Data for Name: wainwright_parent_sessions; Type: TABLE DATA; Schema: public; Owner: default
--



--
-- Data for Name: wainwright_player_balances; Type: TABLE DATA; Schema: public; Owner: default
--



--
-- Name: action_events_id_seq; Type: SEQUENCE SET; Schema: public; Owner: default
--

SELECT pg_catalog.setval('public.action_events_id_seq', 1, false);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: default
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: default
--

SELECT pg_catalog.setval('public.migrations_id_seq', 26, true);


--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE SET; Schema: public; Owner: default
--

SELECT pg_catalog.setval('public.personal_access_tokens_id_seq', 1, false);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: default
--

SELECT pg_catalog.setval('public.users_id_seq', 1, true);


--
-- Name: wainwright_bgaming_bonusgames_id_seq; Type: SEQUENCE SET; Schema: public; Owner: default
--

SELECT pg_catalog.setval('public.wainwright_bgaming_bonusgames_id_seq', 1, false);


--
-- Name: wainwright_casino_profiles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: default
--

SELECT pg_catalog.setval('public.wainwright_casino_profiles_id_seq', 1, false);


--
-- Name: wainwright_crawlerdata_id_seq; Type: SEQUENCE SET; Schema: public; Owner: default
--

SELECT pg_catalog.setval('public.wainwright_crawlerdata_id_seq', 1, false);


--
-- Name: wainwright_datalogger_id_seq; Type: SEQUENCE SET; Schema: public; Owner: default
--

SELECT pg_catalog.setval('public.wainwright_datalogger_id_seq', 1, false);


--
-- Name: wainwright_freespins_id_seq; Type: SEQUENCE SET; Schema: public; Owner: default
--

SELECT pg_catalog.setval('public.wainwright_freespins_id_seq', 1, false);


--
-- Name: wainwright_gamerespin_template_id_seq; Type: SEQUENCE SET; Schema: public; Owner: default
--

SELECT pg_catalog.setval('public.wainwright_gamerespin_template_id_seq', 1, false);


--
-- Name: wainwright_games_thumbnails_id_seq; Type: SEQUENCE SET; Schema: public; Owner: default
--

SELECT pg_catalog.setval('public.wainwright_games_thumbnails_id_seq', 1, false);


--
-- Name: wainwright_gameslist_id_seq; Type: SEQUENCE SET; Schema: public; Owner: default
--

SELECT pg_catalog.setval('public.wainwright_gameslist_id_seq', 1, false);


--
-- Name: wainwright_gameslist_raw_id_seq; Type: SEQUENCE SET; Schema: public; Owner: default
--

SELECT pg_catalog.setval('public.wainwright_gameslist_raw_id_seq', 1, false);


--
-- Name: wainwright_job_gameimporter_id_seq; Type: SEQUENCE SET; Schema: public; Owner: default
--

SELECT pg_catalog.setval('public.wainwright_job_gameimporter_id_seq', 1, false);


--
-- Name: wainwright_metadata_id_seq; Type: SEQUENCE SET; Schema: public; Owner: default
--

SELECT pg_catalog.setval('public.wainwright_metadata_id_seq', 1, false);


--
-- Name: wainwright_operator_access_id_seq; Type: SEQUENCE SET; Schema: public; Owner: default
--

SELECT pg_catalog.setval('public.wainwright_operator_access_id_seq', 1, true);


--
-- Name: wainwright_operator_disabled_games_id_seq; Type: SEQUENCE SET; Schema: public; Owner: default
--

SELECT pg_catalog.setval('public.wainwright_operator_disabled_games_id_seq', 1, false);


--
-- Name: wainwright_operator_gameslist_id_seq; Type: SEQUENCE SET; Schema: public; Owner: default
--

SELECT pg_catalog.setval('public.wainwright_operator_gameslist_id_seq', 1, false);


--
-- Name: wainwright_operator_transactions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: default
--

SELECT pg_catalog.setval('public.wainwright_operator_transactions_id_seq', 1, false);


--
-- Name: wainwright_parent_sessions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: default
--

SELECT pg_catalog.setval('public.wainwright_parent_sessions_id_seq', 1, false);


--
-- Name: wainwright_player_balances_id_seq; Type: SEQUENCE SET; Schema: public; Owner: default
--

SELECT pg_catalog.setval('public.wainwright_player_balances_id_seq', 1, false);


--
-- Name: action_events action_events_pkey; Type: CONSTRAINT; Schema: public; Owner: default
--

ALTER TABLE ONLY public.action_events
    ADD CONSTRAINT action_events_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: default
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: default
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: default
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: nova_notifications nova_notifications_pkey; Type: CONSTRAINT; Schema: public; Owner: default
--

ALTER TABLE ONLY public.nova_notifications
    ADD CONSTRAINT nova_notifications_pkey PRIMARY KEY (id);


--
-- Name: personal_access_tokens personal_access_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: default
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_pkey PRIMARY KEY (id);


--
-- Name: personal_access_tokens personal_access_tokens_token_unique; Type: CONSTRAINT; Schema: public; Owner: default
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_token_unique UNIQUE (token);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: default
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: default
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: wainwright_bgaming_bonusgames wainwright_bgaming_bonusgames_pkey; Type: CONSTRAINT; Schema: public; Owner: default
--

ALTER TABLE ONLY public.wainwright_bgaming_bonusgames
    ADD CONSTRAINT wainwright_bgaming_bonusgames_pkey PRIMARY KEY (id);


--
-- Name: wainwright_casino_profiles wainwright_casino_profiles_pkey; Type: CONSTRAINT; Schema: public; Owner: default
--

ALTER TABLE ONLY public.wainwright_casino_profiles
    ADD CONSTRAINT wainwright_casino_profiles_pkey PRIMARY KEY (id);


--
-- Name: wainwright_crawlerdata wainwright_crawlerdata_pkey; Type: CONSTRAINT; Schema: public; Owner: default
--

ALTER TABLE ONLY public.wainwright_crawlerdata
    ADD CONSTRAINT wainwright_crawlerdata_pkey PRIMARY KEY (id);


--
-- Name: wainwright_datalogger wainwright_datalogger_pkey; Type: CONSTRAINT; Schema: public; Owner: default
--

ALTER TABLE ONLY public.wainwright_datalogger
    ADD CONSTRAINT wainwright_datalogger_pkey PRIMARY KEY (id);


--
-- Name: wainwright_freespins wainwright_freespins_pkey; Type: CONSTRAINT; Schema: public; Owner: default
--

ALTER TABLE ONLY public.wainwright_freespins
    ADD CONSTRAINT wainwright_freespins_pkey PRIMARY KEY (id);


--
-- Name: wainwright_gamerespin_template wainwright_gamerespin_template_pkey; Type: CONSTRAINT; Schema: public; Owner: default
--

ALTER TABLE ONLY public.wainwright_gamerespin_template
    ADD CONSTRAINT wainwright_gamerespin_template_pkey PRIMARY KEY (id);


--
-- Name: wainwright_games_thumbnails wainwright_games_thumbnails_pkey; Type: CONSTRAINT; Schema: public; Owner: default
--

ALTER TABLE ONLY public.wainwright_games_thumbnails
    ADD CONSTRAINT wainwright_games_thumbnails_pkey PRIMARY KEY (id);


--
-- Name: wainwright_gameslist wainwright_gameslist_pkey; Type: CONSTRAINT; Schema: public; Owner: default
--

ALTER TABLE ONLY public.wainwright_gameslist
    ADD CONSTRAINT wainwright_gameslist_pkey PRIMARY KEY (id);


--
-- Name: wainwright_gameslist_raw wainwright_gameslist_raw_pkey; Type: CONSTRAINT; Schema: public; Owner: default
--

ALTER TABLE ONLY public.wainwright_gameslist_raw
    ADD CONSTRAINT wainwright_gameslist_raw_pkey PRIMARY KEY (id);


--
-- Name: wainwright_job_gameimporter wainwright_job_gameimporter_pkey; Type: CONSTRAINT; Schema: public; Owner: default
--

ALTER TABLE ONLY public.wainwright_job_gameimporter
    ADD CONSTRAINT wainwright_job_gameimporter_pkey PRIMARY KEY (id);


--
-- Name: wainwright_metadata wainwright_metadata_pkey; Type: CONSTRAINT; Schema: public; Owner: default
--

ALTER TABLE ONLY public.wainwright_metadata
    ADD CONSTRAINT wainwright_metadata_pkey PRIMARY KEY (id);


--
-- Name: wainwright_operator_access wainwright_operator_access_pkey; Type: CONSTRAINT; Schema: public; Owner: default
--

ALTER TABLE ONLY public.wainwright_operator_access
    ADD CONSTRAINT wainwright_operator_access_pkey PRIMARY KEY (id);


--
-- Name: wainwright_operator_disabled_games wainwright_operator_disabled_games_pkey; Type: CONSTRAINT; Schema: public; Owner: default
--

ALTER TABLE ONLY public.wainwright_operator_disabled_games
    ADD CONSTRAINT wainwright_operator_disabled_games_pkey PRIMARY KEY (id);


--
-- Name: wainwright_operator_gameslist wainwright_operator_gameslist_pkey; Type: CONSTRAINT; Schema: public; Owner: default
--

ALTER TABLE ONLY public.wainwright_operator_gameslist
    ADD CONSTRAINT wainwright_operator_gameslist_pkey PRIMARY KEY (id);


--
-- Name: wainwright_operator_transactions wainwright_operator_transactions_pkey; Type: CONSTRAINT; Schema: public; Owner: default
--

ALTER TABLE ONLY public.wainwright_operator_transactions
    ADD CONSTRAINT wainwright_operator_transactions_pkey PRIMARY KEY (id);


--
-- Name: wainwright_parent_sessions wainwright_parent_sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: default
--

ALTER TABLE ONLY public.wainwright_parent_sessions
    ADD CONSTRAINT wainwright_parent_sessions_pkey PRIMARY KEY (id);


--
-- Name: wainwright_player_balances wainwright_player_balances_pkey; Type: CONSTRAINT; Schema: public; Owner: default
--

ALTER TABLE ONLY public.wainwright_player_balances
    ADD CONSTRAINT wainwright_player_balances_pkey PRIMARY KEY (id);


--
-- Name: action_events_actionable_type_actionable_id_index; Type: INDEX; Schema: public; Owner: default
--

CREATE INDEX action_events_actionable_type_actionable_id_index ON public.action_events USING btree (actionable_type, actionable_id);


--
-- Name: action_events_batch_id_model_type_model_id_index; Type: INDEX; Schema: public; Owner: default
--

CREATE INDEX action_events_batch_id_model_type_model_id_index ON public.action_events USING btree (batch_id, model_type, model_id);


--
-- Name: action_events_target_type_target_id_index; Type: INDEX; Schema: public; Owner: default
--

CREATE INDEX action_events_target_type_target_id_index ON public.action_events USING btree (target_type, target_id);


--
-- Name: action_events_user_id_index; Type: INDEX; Schema: public; Owner: default
--

CREATE INDEX action_events_user_id_index ON public.action_events USING btree (user_id);


--
-- Name: nova_notifications_notifiable_type_notifiable_id_index; Type: INDEX; Schema: public; Owner: default
--

CREATE INDEX nova_notifications_notifiable_type_notifiable_id_index ON public.nova_notifications USING btree (notifiable_type, notifiable_id);


--
-- Name: password_resets_email_index; Type: INDEX; Schema: public; Owner: default
--

CREATE INDEX password_resets_email_index ON public.password_resets USING btree (email);


--
-- Name: personal_access_tokens_tokenable_type_tokenable_id_index; Type: INDEX; Schema: public; Owner: default
--

CREATE INDEX personal_access_tokens_tokenable_type_tokenable_id_index ON public.personal_access_tokens USING btree (tokenable_type, tokenable_id);


--
-- Name: wainwright_bgaming_bonusgames_id_index; Type: INDEX; Schema: public; Owner: default
--

CREATE INDEX wainwright_bgaming_bonusgames_id_index ON public.wainwright_bgaming_bonusgames USING btree (id);


--
-- Name: wainwright_casino_profiles_id_index; Type: INDEX; Schema: public; Owner: default
--

CREATE INDEX wainwright_casino_profiles_id_index ON public.wainwright_casino_profiles USING btree (id);


--
-- Name: wainwright_crawlerdata_id_index; Type: INDEX; Schema: public; Owner: default
--

CREATE INDEX wainwright_crawlerdata_id_index ON public.wainwright_crawlerdata USING btree (id);


--
-- Name: wainwright_datalogger_id_index; Type: INDEX; Schema: public; Owner: default
--

CREATE INDEX wainwright_datalogger_id_index ON public.wainwright_datalogger USING btree (id);


--
-- Name: wainwright_freespins_id_index; Type: INDEX; Schema: public; Owner: default
--

CREATE INDEX wainwright_freespins_id_index ON public.wainwright_freespins USING btree (id);


--
-- Name: wainwright_gamerespin_template_id_index; Type: INDEX; Schema: public; Owner: default
--

CREATE INDEX wainwright_gamerespin_template_id_index ON public.wainwright_gamerespin_template USING btree (id);


--
-- Name: wainwright_games_thumbnails_id_index; Type: INDEX; Schema: public; Owner: default
--

CREATE INDEX wainwright_games_thumbnails_id_index ON public.wainwright_games_thumbnails USING btree (id);


--
-- Name: wainwright_gameslist_id_index; Type: INDEX; Schema: public; Owner: default
--

CREATE INDEX wainwright_gameslist_id_index ON public.wainwright_gameslist USING btree (id);


--
-- Name: wainwright_gameslist_raw_id_index; Type: INDEX; Schema: public; Owner: default
--

CREATE INDEX wainwright_gameslist_raw_id_index ON public.wainwright_gameslist_raw USING btree (id);


--
-- Name: wainwright_job_gameimporter_id_index; Type: INDEX; Schema: public; Owner: default
--

CREATE INDEX wainwright_job_gameimporter_id_index ON public.wainwright_job_gameimporter USING btree (id);


--
-- Name: wainwright_metadata_id_index; Type: INDEX; Schema: public; Owner: default
--

CREATE INDEX wainwright_metadata_id_index ON public.wainwright_metadata USING btree (id);


--
-- Name: wainwright_operator_access_id_index; Type: INDEX; Schema: public; Owner: default
--

CREATE INDEX wainwright_operator_access_id_index ON public.wainwright_operator_access USING btree (id);


--
-- Name: wainwright_operator_disabled_games_id_index; Type: INDEX; Schema: public; Owner: default
--

CREATE INDEX wainwright_operator_disabled_games_id_index ON public.wainwright_operator_disabled_games USING btree (id);


--
-- Name: wainwright_operator_gameslist_id_index; Type: INDEX; Schema: public; Owner: default
--

CREATE INDEX wainwright_operator_gameslist_id_index ON public.wainwright_operator_gameslist USING btree (id);


--
-- Name: wainwright_operator_transactions_id_index; Type: INDEX; Schema: public; Owner: default
--

CREATE INDEX wainwright_operator_transactions_id_index ON public.wainwright_operator_transactions USING btree (id);


--
-- Name: wainwright_parent_sessions_id_index; Type: INDEX; Schema: public; Owner: default
--

CREATE INDEX wainwright_parent_sessions_id_index ON public.wainwright_parent_sessions USING btree (id);


--
-- Name: wainwright_player_balances_id_index; Type: INDEX; Schema: public; Owner: default
--

CREATE INDEX wainwright_player_balances_id_index ON public.wainwright_player_balances USING btree (id);


--
-- Name: wainwright_operator_access wainwright_operator_access_ownedby_foreign; Type: FK CONSTRAINT; Schema: public; Owner: default
--

ALTER TABLE ONLY public.wainwright_operator_access
    ADD CONSTRAINT wainwright_operator_access_ownedby_foreign FOREIGN KEY ("ownedBy") REFERENCES public.users(id);


--
-- PostgreSQL database dump complete
--

