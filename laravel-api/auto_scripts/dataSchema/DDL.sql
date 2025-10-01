--
-- PostgreSQL database dump
--

-- Dumped from database version 15.14
-- Dumped by pg_dump version 17.0

-- Started on 2025-10-01 17:39:09

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

--
-- TOC entry 305 (class 1255 OID 16986)
-- Name: insert_into_api_role_from_api(); Type: FUNCTION; Schema: public; Owner: ml_pg_user
--

CREATE FUNCTION public.insert_into_api_role_from_api() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
            BEGIN
              -- Insert a new record into api_role_mst table
              INSERT INTO api_role_mst (api_mst_id, role_mst_id, created_at, updated_at)
              SELECT NEW.id, id, now(), now() FROM role_mst WHERE name = 'root';
              RETURN NEW;
            END;
          $$;


ALTER FUNCTION public.insert_into_api_role_from_api() OWNER TO ml_pg_user;

--
-- TOC entry 306 (class 1255 OID 16988)
-- Name: insert_into_department_management(); Type: FUNCTION; Schema: public; Owner: ml_pg_user
--

CREATE FUNCTION public.insert_into_department_management() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
            BEGIN
              -- Insert a new record into department_management_mst table
              INSERT INTO department_management_mst (department_mst_id, policy_department_mst_id, created_at, updated_at)
              SELECT id, NEW.id, now(), now() FROM department_mst WHERE name = 'root';
              RETURN NEW;
            END;
          $$;


ALTER FUNCTION public.insert_into_department_management() OWNER TO ml_pg_user;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 239 (class 1259 OID 16555)
-- Name: admin_department_mst; Type: TABLE; Schema: public; Owner: ml_pg_user
--

CREATE TABLE public.admin_department_mst (
    admin_mst_id integer NOT NULL,
    department_mst_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.admin_department_mst OWNER TO ml_pg_user;

--
-- TOC entry 3982 (class 0 OID 0)
-- Dependencies: 239
-- Name: COLUMN admin_department_mst.admin_mst_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.admin_department_mst.admin_mst_id IS 'Admin ID';


--
-- TOC entry 3983 (class 0 OID 0)
-- Dependencies: 239
-- Name: COLUMN admin_department_mst.department_mst_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.admin_department_mst.department_mst_id IS 'Department ID';


--
-- TOC entry 228 (class 1259 OID 16467)
-- Name: admin_mst; Type: TABLE; Schema: public; Owner: ml_pg_user
--

CREATE TABLE public.admin_mst (
    id integer NOT NULL,
    email character varying(30) NOT NULL,
    user_name character varying(50) NOT NULL,
    password character varying(100) NOT NULL,
    first_name character varying(20) NOT NULL,
    last_name character varying(20) NOT NULL,
    address character varying(100),
    phone_number character varying(20),
    birth timestamp(0) without time zone,
    gender smallint DEFAULT '0'::smallint NOT NULL,
    status smallint DEFAULT '0'::smallint NOT NULL,
    is_active boolean DEFAULT false NOT NULL,
    avatar character varying(30),
    email_verified_at timestamp(0) without time zone,
    is_delete boolean DEFAULT false NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.admin_mst OWNER TO ml_pg_user;

--
-- TOC entry 3984 (class 0 OID 0)
-- Dependencies: 228
-- Name: COLUMN admin_mst.id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.admin_mst.id IS 'Admin ID';


--
-- TOC entry 3985 (class 0 OID 0)
-- Dependencies: 228
-- Name: COLUMN admin_mst.email; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.admin_mst.email IS 'Admin email';


--
-- TOC entry 3986 (class 0 OID 0)
-- Dependencies: 228
-- Name: COLUMN admin_mst.user_name; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.admin_mst.user_name IS 'Admin user name';


--
-- TOC entry 3987 (class 0 OID 0)
-- Dependencies: 228
-- Name: COLUMN admin_mst.password; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.admin_mst.password IS 'Admin password';


--
-- TOC entry 3988 (class 0 OID 0)
-- Dependencies: 228
-- Name: COLUMN admin_mst.first_name; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.admin_mst.first_name IS 'Admin first name';


--
-- TOC entry 3989 (class 0 OID 0)
-- Dependencies: 228
-- Name: COLUMN admin_mst.last_name; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.admin_mst.last_name IS 'Admin last name';


--
-- TOC entry 3990 (class 0 OID 0)
-- Dependencies: 228
-- Name: COLUMN admin_mst.address; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.admin_mst.address IS 'Admin address';


--
-- TOC entry 3991 (class 0 OID 0)
-- Dependencies: 228
-- Name: COLUMN admin_mst.phone_number; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.admin_mst.phone_number IS 'Admin phone number';


--
-- TOC entry 3992 (class 0 OID 0)
-- Dependencies: 228
-- Name: COLUMN admin_mst.birth; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.admin_mst.birth IS 'Admin birth';


--
-- TOC entry 3993 (class 0 OID 0)
-- Dependencies: 228
-- Name: COLUMN admin_mst.gender; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.admin_mst.gender IS 'Admin gender';


--
-- TOC entry 3994 (class 0 OID 0)
-- Dependencies: 228
-- Name: COLUMN admin_mst.status; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.admin_mst.status IS 'Admin status';


--
-- TOC entry 3995 (class 0 OID 0)
-- Dependencies: 228
-- Name: COLUMN admin_mst.is_active; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.admin_mst.is_active IS 'Admin active';


--
-- TOC entry 3996 (class 0 OID 0)
-- Dependencies: 228
-- Name: COLUMN admin_mst.avatar; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.admin_mst.avatar IS 'Admin avatar name';


--
-- TOC entry 3997 (class 0 OID 0)
-- Dependencies: 228
-- Name: COLUMN admin_mst.email_verified_at; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.admin_mst.email_verified_at IS 'Verified email time';


--
-- TOC entry 3998 (class 0 OID 0)
-- Dependencies: 228
-- Name: COLUMN admin_mst.is_delete; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.admin_mst.is_delete IS 'is deleted';


--
-- TOC entry 270 (class 1259 OID 16770)
-- Name: admin_mst_hist; Type: TABLE; Schema: public; Owner: ml_pg_user
--

CREATE TABLE public.admin_mst_hist (
    id integer NOT NULL,
    admin_mst_id integer NOT NULL,
    email character varying(30),
    user_name character varying(50),
    password character varying(100),
    first_name character varying(20),
    last_name character varying(20),
    address character varying(100),
    phone_number character varying(20),
    birth timestamp(0) without time zone,
    gender smallint,
    status smallint,
    is_active boolean,
    avatar character varying(30),
    email_verified_at timestamp(0) without time zone,
    remember_token character varying(100),
    action smallint NOT NULL,
    author_id integer NOT NULL,
    created_at timestamp(0) without time zone NOT NULL
);


ALTER TABLE public.admin_mst_hist OWNER TO ml_pg_user;

--
-- TOC entry 3999 (class 0 OID 0)
-- Dependencies: 270
-- Name: COLUMN admin_mst_hist.id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.admin_mst_hist.id IS 'admin history id';


--
-- TOC entry 4000 (class 0 OID 0)
-- Dependencies: 270
-- Name: COLUMN admin_mst_hist.admin_mst_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.admin_mst_hist.admin_mst_id IS 'Admin id';


--
-- TOC entry 4001 (class 0 OID 0)
-- Dependencies: 270
-- Name: COLUMN admin_mst_hist.email; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.admin_mst_hist.email IS 'email';


--
-- TOC entry 4002 (class 0 OID 0)
-- Dependencies: 270
-- Name: COLUMN admin_mst_hist.user_name; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.admin_mst_hist.user_name IS 'user name';


--
-- TOC entry 4003 (class 0 OID 0)
-- Dependencies: 270
-- Name: COLUMN admin_mst_hist.password; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.admin_mst_hist.password IS 'password';


--
-- TOC entry 4004 (class 0 OID 0)
-- Dependencies: 270
-- Name: COLUMN admin_mst_hist.first_name; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.admin_mst_hist.first_name IS 'first name';


--
-- TOC entry 4005 (class 0 OID 0)
-- Dependencies: 270
-- Name: COLUMN admin_mst_hist.last_name; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.admin_mst_hist.last_name IS 'last name';


--
-- TOC entry 4006 (class 0 OID 0)
-- Dependencies: 270
-- Name: COLUMN admin_mst_hist.address; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.admin_mst_hist.address IS 'address';


--
-- TOC entry 4007 (class 0 OID 0)
-- Dependencies: 270
-- Name: COLUMN admin_mst_hist.phone_number; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.admin_mst_hist.phone_number IS 'phone number';


--
-- TOC entry 4008 (class 0 OID 0)
-- Dependencies: 270
-- Name: COLUMN admin_mst_hist.birth; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.admin_mst_hist.birth IS 'birth';


--
-- TOC entry 4009 (class 0 OID 0)
-- Dependencies: 270
-- Name: COLUMN admin_mst_hist.gender; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.admin_mst_hist.gender IS 'gender';


--
-- TOC entry 4010 (class 0 OID 0)
-- Dependencies: 270
-- Name: COLUMN admin_mst_hist.status; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.admin_mst_hist.status IS 'status';


--
-- TOC entry 4011 (class 0 OID 0)
-- Dependencies: 270
-- Name: COLUMN admin_mst_hist.is_active; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.admin_mst_hist.is_active IS 'active';


--
-- TOC entry 4012 (class 0 OID 0)
-- Dependencies: 270
-- Name: COLUMN admin_mst_hist.avatar; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.admin_mst_hist.avatar IS 'avatar name';


--
-- TOC entry 4013 (class 0 OID 0)
-- Dependencies: 270
-- Name: COLUMN admin_mst_hist.email_verified_at; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.admin_mst_hist.email_verified_at IS 'Verified email time';


--
-- TOC entry 4014 (class 0 OID 0)
-- Dependencies: 270
-- Name: COLUMN admin_mst_hist.remember_token; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.admin_mst_hist.remember_token IS 'remember token';


--
-- TOC entry 4015 (class 0 OID 0)
-- Dependencies: 270
-- Name: COLUMN admin_mst_hist.action; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.admin_mst_hist.action IS 'action';


--
-- TOC entry 4016 (class 0 OID 0)
-- Dependencies: 270
-- Name: COLUMN admin_mst_hist.author_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.admin_mst_hist.author_id IS 'Author id';


--
-- TOC entry 4017 (class 0 OID 0)
-- Dependencies: 270
-- Name: COLUMN admin_mst_hist.created_at; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.admin_mst_hist.created_at IS 'Created time';


--
-- TOC entry 269 (class 1259 OID 16769)
-- Name: admin_mst_hist_id_seq; Type: SEQUENCE; Schema: public; Owner: ml_pg_user
--

CREATE SEQUENCE public.admin_mst_hist_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.admin_mst_hist_id_seq OWNER TO ml_pg_user;

--
-- TOC entry 4018 (class 0 OID 0)
-- Dependencies: 269
-- Name: admin_mst_hist_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ml_pg_user
--

ALTER SEQUENCE public.admin_mst_hist_id_seq OWNED BY public.admin_mst_hist.id;


--
-- TOC entry 227 (class 1259 OID 16466)
-- Name: admin_mst_id_seq; Type: SEQUENCE; Schema: public; Owner: ml_pg_user
--

CREATE SEQUENCE public.admin_mst_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.admin_mst_id_seq OWNER TO ml_pg_user;

--
-- TOC entry 4019 (class 0 OID 0)
-- Dependencies: 227
-- Name: admin_mst_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ml_pg_user
--

ALTER SEQUENCE public.admin_mst_id_seq OWNED BY public.admin_mst.id;


--
-- TOC entry 231 (class 1259 OID 16492)
-- Name: admin_role_mst; Type: TABLE; Schema: public; Owner: ml_pg_user
--

CREATE TABLE public.admin_role_mst (
    admin_mst_id integer NOT NULL,
    role_mst_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.admin_role_mst OWNER TO ml_pg_user;

--
-- TOC entry 4020 (class 0 OID 0)
-- Dependencies: 231
-- Name: COLUMN admin_role_mst.admin_mst_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.admin_role_mst.admin_mst_id IS 'Admin ID';


--
-- TOC entry 4021 (class 0 OID 0)
-- Dependencies: 231
-- Name: COLUMN admin_role_mst.role_mst_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.admin_role_mst.role_mst_id IS 'Role ID';


--
-- TOC entry 235 (class 1259 OID 16517)
-- Name: api_mst; Type: TABLE; Schema: public; Owner: ml_pg_user
--

CREATE TABLE public.api_mst (
    id integer NOT NULL,
    type smallint DEFAULT '0'::smallint NOT NULL,
    name character varying(50) NOT NULL,
    path character varying(100) NOT NULL,
    is_active boolean DEFAULT false NOT NULL,
    feature_mst_id integer NOT NULL,
    is_delete boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.api_mst OWNER TO ml_pg_user;

--
-- TOC entry 4022 (class 0 OID 0)
-- Dependencies: 235
-- Name: COLUMN api_mst.id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.api_mst.id IS 'Api ID';


--
-- TOC entry 4023 (class 0 OID 0)
-- Dependencies: 235
-- Name: COLUMN api_mst.type; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.api_mst.type IS 'Api type';


--
-- TOC entry 4024 (class 0 OID 0)
-- Dependencies: 235
-- Name: COLUMN api_mst.name; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.api_mst.name IS 'Api name';


--
-- TOC entry 4025 (class 0 OID 0)
-- Dependencies: 235
-- Name: COLUMN api_mst.path; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.api_mst.path IS 'Api path';


--
-- TOC entry 4026 (class 0 OID 0)
-- Dependencies: 235
-- Name: COLUMN api_mst.is_active; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.api_mst.is_active IS 'Api status';


--
-- TOC entry 4027 (class 0 OID 0)
-- Dependencies: 235
-- Name: COLUMN api_mst.feature_mst_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.api_mst.feature_mst_id IS 'Feature ID';


--
-- TOC entry 4028 (class 0 OID 0)
-- Dependencies: 235
-- Name: COLUMN api_mst.is_delete; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.api_mst.is_delete IS 'is deleted';


--
-- TOC entry 236 (class 1259 OID 16531)
-- Name: api_role_mst; Type: TABLE; Schema: public; Owner: ml_pg_user
--

CREATE TABLE public.api_role_mst (
    api_mst_id integer NOT NULL,
    role_mst_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.api_role_mst OWNER TO ml_pg_user;

--
-- TOC entry 4029 (class 0 OID 0)
-- Dependencies: 236
-- Name: COLUMN api_role_mst.api_mst_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.api_role_mst.api_mst_id IS 'API ID';


--
-- TOC entry 4030 (class 0 OID 0)
-- Dependencies: 236
-- Name: COLUMN api_role_mst.role_mst_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.api_role_mst.role_mst_id IS 'Role ID';


--
-- TOC entry 233 (class 1259 OID 16508)
-- Name: feature_mst; Type: TABLE; Schema: public; Owner: ml_pg_user
--

CREATE TABLE public.feature_mst (
    id integer NOT NULL,
    name character varying(50) NOT NULL,
    group_name character varying(50) NOT NULL,
    description character varying(100) NOT NULL,
    status smallint DEFAULT '0'::smallint NOT NULL,
    is_delete boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.feature_mst OWNER TO ml_pg_user;

--
-- TOC entry 4031 (class 0 OID 0)
-- Dependencies: 233
-- Name: COLUMN feature_mst.id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.feature_mst.id IS 'Feature ID';


--
-- TOC entry 4032 (class 0 OID 0)
-- Dependencies: 233
-- Name: COLUMN feature_mst.name; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.feature_mst.name IS 'Feature name';


--
-- TOC entry 4033 (class 0 OID 0)
-- Dependencies: 233
-- Name: COLUMN feature_mst.group_name; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.feature_mst.group_name IS 'Feature group name';


--
-- TOC entry 4034 (class 0 OID 0)
-- Dependencies: 233
-- Name: COLUMN feature_mst.description; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.feature_mst.description IS 'Feature description';


--
-- TOC entry 4035 (class 0 OID 0)
-- Dependencies: 233
-- Name: COLUMN feature_mst.status; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.feature_mst.status IS 'Feature status';


--
-- TOC entry 4036 (class 0 OID 0)
-- Dependencies: 233
-- Name: COLUMN feature_mst.is_delete; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.feature_mst.is_delete IS 'is deleted';


--
-- TOC entry 230 (class 1259 OID 16482)
-- Name: role_mst; Type: TABLE; Schema: public; Owner: ml_pg_user
--

CREATE TABLE public.role_mst (
    id integer NOT NULL,
    name character varying(30) NOT NULL,
    permission character varying(50) NOT NULL,
    is_active boolean DEFAULT false NOT NULL,
    is_delete boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.role_mst OWNER TO ml_pg_user;

--
-- TOC entry 4037 (class 0 OID 0)
-- Dependencies: 230
-- Name: COLUMN role_mst.id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.role_mst.id IS 'Role ID';


--
-- TOC entry 4038 (class 0 OID 0)
-- Dependencies: 230
-- Name: COLUMN role_mst.name; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.role_mst.name IS 'Role name';


--
-- TOC entry 4039 (class 0 OID 0)
-- Dependencies: 230
-- Name: COLUMN role_mst.permission; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.role_mst.permission IS 'Role description';


--
-- TOC entry 4040 (class 0 OID 0)
-- Dependencies: 230
-- Name: COLUMN role_mst.is_active; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.role_mst.is_active IS 'Role active';


--
-- TOC entry 4041 (class 0 OID 0)
-- Dependencies: 230
-- Name: COLUMN role_mst.is_delete; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.role_mst.is_delete IS 'is deleted';


--
-- TOC entry 303 (class 1259 OID 16990)
-- Name: admin_permission_view; Type: VIEW; Schema: public; Owner: ml_pg_user
--

CREATE VIEW public.admin_permission_view AS
 SELECT am.id AS admin_mst_id,
    rm.id AS role_mst_id,
    rm.name AS role_name,
        CASE
            WHEN (am2.type = 0) THEN 'GET'::text
            WHEN (am2.type = 1) THEN 'POST'::text
            WHEN (am2.type = 2) THEN 'PUT'::text
            WHEN (am2.type = 3) THEN 'PATCH'::text
            WHEN (am2.type = 4) THEN 'DELETE'::text
            ELSE NULL::text
        END AS type,
    am2.name AS api_name,
    am2.path,
    fm.name AS feature_name,
    fm.group_name AS feature_group
   FROM (((((public.admin_mst am
     JOIN public.admin_role_mst arm ON ((arm.admin_mst_id = am.id)))
     JOIN public.role_mst rm ON ((rm.id = arm.role_mst_id)))
     JOIN public.api_role_mst arm2 ON ((arm2.role_mst_id = rm.id)))
     JOIN public.api_mst am2 ON ((am2.id = arm2.api_mst_id)))
     JOIN public.feature_mst fm ON ((fm.id = am2.feature_mst_id)))
  WHERE ((am.status = 1) AND (am.is_active = true) AND (rm.is_active = true) AND (am2.is_active = true) AND (fm.status = 1));


ALTER VIEW public.admin_permission_view OWNER TO ml_pg_user;

--
-- TOC entry 242 (class 1259 OID 16578)
-- Name: department_management_mst; Type: TABLE; Schema: public; Owner: ml_pg_user
--

CREATE TABLE public.department_management_mst (
    department_mst_id integer NOT NULL,
    policy_department_mst_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.department_management_mst OWNER TO ml_pg_user;

--
-- TOC entry 4042 (class 0 OID 0)
-- Dependencies: 242
-- Name: COLUMN department_management_mst.department_mst_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.department_management_mst.department_mst_id IS 'Department ID';


--
-- TOC entry 4043 (class 0 OID 0)
-- Dependencies: 242
-- Name: COLUMN department_management_mst.policy_department_mst_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.department_management_mst.policy_department_mst_id IS 'Policy Department ID';


--
-- TOC entry 238 (class 1259 OID 16547)
-- Name: department_mst; Type: TABLE; Schema: public; Owner: ml_pg_user
--

CREATE TABLE public.department_mst (
    id integer NOT NULL,
    code character varying(50) NOT NULL,
    name character varying(50) NOT NULL,
    status smallint DEFAULT '0'::smallint NOT NULL,
    is_delete boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.department_mst OWNER TO ml_pg_user;

--
-- TOC entry 4044 (class 0 OID 0)
-- Dependencies: 238
-- Name: COLUMN department_mst.code; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.department_mst.code IS 'Department code';


--
-- TOC entry 4045 (class 0 OID 0)
-- Dependencies: 238
-- Name: COLUMN department_mst.name; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.department_mst.name IS 'Department name';


--
-- TOC entry 4046 (class 0 OID 0)
-- Dependencies: 238
-- Name: COLUMN department_mst.status; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.department_mst.status IS 'Department status';


--
-- TOC entry 4047 (class 0 OID 0)
-- Dependencies: 238
-- Name: COLUMN department_mst.is_delete; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.department_mst.is_delete IS 'is deleted';


--
-- TOC entry 241 (class 1259 OID 16571)
-- Name: policy_department_mst; Type: TABLE; Schema: public; Owner: ml_pg_user
--

CREATE TABLE public.policy_department_mst (
    id integer NOT NULL,
    table_name character varying(20) NOT NULL,
    row_id integer NOT NULL,
    is_delete boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.policy_department_mst OWNER TO ml_pg_user;

--
-- TOC entry 4048 (class 0 OID 0)
-- Dependencies: 241
-- Name: COLUMN policy_department_mst.id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.policy_department_mst.id IS 'Policy department ID';


--
-- TOC entry 4049 (class 0 OID 0)
-- Dependencies: 241
-- Name: COLUMN policy_department_mst.table_name; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.policy_department_mst.table_name IS 'Table name';


--
-- TOC entry 4050 (class 0 OID 0)
-- Dependencies: 241
-- Name: COLUMN policy_department_mst.row_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.policy_department_mst.row_id IS 'Row id';


--
-- TOC entry 4051 (class 0 OID 0)
-- Dependencies: 241
-- Name: COLUMN policy_department_mst.is_delete; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.policy_department_mst.is_delete IS 'is deleted';


--
-- TOC entry 304 (class 1259 OID 16995)
-- Name: admin_policy_view; Type: VIEW; Schema: public; Owner: ml_pg_user
--

CREATE VIEW public.admin_policy_view AS
 SELECT am.id AS admin_mst_id,
    dm.code AS department_code,
    dm.name AS department_name,
    pdm.table_name,
    pdm.row_id
   FROM ((((public.admin_mst am
     JOIN public.admin_department_mst adm ON ((adm.admin_mst_id = am.id)))
     JOIN public.department_mst dm ON ((dm.id = adm.department_mst_id)))
     JOIN public.department_management_mst dmm ON ((dmm.department_mst_id = dm.id)))
     JOIN public.policy_department_mst pdm ON ((pdm.id = dmm.policy_department_mst_id)))
  WHERE ((am.status = 1) AND (dm.status = 1));


ALTER VIEW public.admin_policy_view OWNER TO ml_pg_user;

--
-- TOC entry 280 (class 1259 OID 16830)
-- Name: api_mst_hist; Type: TABLE; Schema: public; Owner: ml_pg_user
--

CREATE TABLE public.api_mst_hist (
    id integer NOT NULL,
    api_mst_id integer NOT NULL,
    type smallint,
    name character varying(50),
    path character varying(100),
    is_active smallint,
    feature_mst_id integer NOT NULL,
    action smallint NOT NULL,
    author_id integer NOT NULL,
    created_at timestamp(0) without time zone NOT NULL
);


ALTER TABLE public.api_mst_hist OWNER TO ml_pg_user;

--
-- TOC entry 4052 (class 0 OID 0)
-- Dependencies: 280
-- Name: COLUMN api_mst_hist.api_mst_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.api_mst_hist.api_mst_id IS 'ApiMst id';


--
-- TOC entry 4053 (class 0 OID 0)
-- Dependencies: 280
-- Name: COLUMN api_mst_hist.type; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.api_mst_hist.type IS 'type';


--
-- TOC entry 4054 (class 0 OID 0)
-- Dependencies: 280
-- Name: COLUMN api_mst_hist.name; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.api_mst_hist.name IS 'name';


--
-- TOC entry 4055 (class 0 OID 0)
-- Dependencies: 280
-- Name: COLUMN api_mst_hist.path; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.api_mst_hist.path IS 'path';


--
-- TOC entry 4056 (class 0 OID 0)
-- Dependencies: 280
-- Name: COLUMN api_mst_hist.is_active; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.api_mst_hist.is_active IS 'status';


--
-- TOC entry 4057 (class 0 OID 0)
-- Dependencies: 280
-- Name: COLUMN api_mst_hist.feature_mst_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.api_mst_hist.feature_mst_id IS 'FeatureMst id';


--
-- TOC entry 4058 (class 0 OID 0)
-- Dependencies: 280
-- Name: COLUMN api_mst_hist.action; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.api_mst_hist.action IS 'action';


--
-- TOC entry 4059 (class 0 OID 0)
-- Dependencies: 280
-- Name: COLUMN api_mst_hist.author_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.api_mst_hist.author_id IS 'Author id';


--
-- TOC entry 4060 (class 0 OID 0)
-- Dependencies: 280
-- Name: COLUMN api_mst_hist.created_at; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.api_mst_hist.created_at IS 'Created time';


--
-- TOC entry 279 (class 1259 OID 16829)
-- Name: api_mst_hist_id_seq; Type: SEQUENCE; Schema: public; Owner: ml_pg_user
--

CREATE SEQUENCE public.api_mst_hist_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.api_mst_hist_id_seq OWNER TO ml_pg_user;

--
-- TOC entry 4061 (class 0 OID 0)
-- Dependencies: 279
-- Name: api_mst_hist_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ml_pg_user
--

ALTER SEQUENCE public.api_mst_hist_id_seq OWNED BY public.api_mst_hist.id;


--
-- TOC entry 234 (class 1259 OID 16516)
-- Name: api_mst_id_seq; Type: SEQUENCE; Schema: public; Owner: ml_pg_user
--

CREATE SEQUENCE public.api_mst_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.api_mst_id_seq OWNER TO ml_pg_user;

--
-- TOC entry 4062 (class 0 OID 0)
-- Dependencies: 234
-- Name: api_mst_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ml_pg_user
--

ALTER SEQUENCE public.api_mst_id_seq OWNED BY public.api_mst.id;


--
-- TOC entry 262 (class 1259 OID 16732)
-- Name: banner_mgmt; Type: TABLE; Schema: public; Owner: ml_pg_user
--

CREATE TABLE public.banner_mgmt (
    id integer NOT NULL,
    title character varying(50) NOT NULL,
    slug character varying(50) NOT NULL,
    description character varying(255) NOT NULL,
    link character varying(100) NOT NULL,
    image character varying(100) NOT NULL,
    "position" character varying(50) NOT NULL,
    status smallint DEFAULT '0'::smallint NOT NULL,
    is_delete boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.banner_mgmt OWNER TO ml_pg_user;

--
-- TOC entry 4063 (class 0 OID 0)
-- Dependencies: 262
-- Name: COLUMN banner_mgmt.title; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.banner_mgmt.title IS 'Banner title';


--
-- TOC entry 4064 (class 0 OID 0)
-- Dependencies: 262
-- Name: COLUMN banner_mgmt.slug; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.banner_mgmt.slug IS 'Banner slug';


--
-- TOC entry 4065 (class 0 OID 0)
-- Dependencies: 262
-- Name: COLUMN banner_mgmt.description; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.banner_mgmt.description IS 'Banner description';


--
-- TOC entry 4066 (class 0 OID 0)
-- Dependencies: 262
-- Name: COLUMN banner_mgmt.link; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.banner_mgmt.link IS 'Banner path image';


--
-- TOC entry 4067 (class 0 OID 0)
-- Dependencies: 262
-- Name: COLUMN banner_mgmt.image; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.banner_mgmt.image IS 'Banner image name';


--
-- TOC entry 4068 (class 0 OID 0)
-- Dependencies: 262
-- Name: COLUMN banner_mgmt."position"; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.banner_mgmt."position" IS 'Banner display position';


--
-- TOC entry 4069 (class 0 OID 0)
-- Dependencies: 262
-- Name: COLUMN banner_mgmt.status; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.banner_mgmt.status IS 'Banner status';


--
-- TOC entry 4070 (class 0 OID 0)
-- Dependencies: 262
-- Name: COLUMN banner_mgmt.is_delete; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.banner_mgmt.is_delete IS 'is deleted';


--
-- TOC entry 296 (class 1259 OID 16935)
-- Name: banner_mgmt_hist; Type: TABLE; Schema: public; Owner: ml_pg_user
--

CREATE TABLE public.banner_mgmt_hist (
    id integer NOT NULL,
    banner_mgmt_id integer NOT NULL,
    title character varying(50),
    slug character varying(50),
    description character varying(255),
    link character varying(100),
    image character varying(100),
    "position" character varying(50),
    status smallint,
    action smallint NOT NULL,
    author_id integer NOT NULL,
    created_at timestamp(0) without time zone NOT NULL
);


ALTER TABLE public.banner_mgmt_hist OWNER TO ml_pg_user;

--
-- TOC entry 4071 (class 0 OID 0)
-- Dependencies: 296
-- Name: COLUMN banner_mgmt_hist.id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.banner_mgmt_hist.id IS 'Banner history id';


--
-- TOC entry 4072 (class 0 OID 0)
-- Dependencies: 296
-- Name: COLUMN banner_mgmt_hist.banner_mgmt_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.banner_mgmt_hist.banner_mgmt_id IS 'Banner management id';


--
-- TOC entry 4073 (class 0 OID 0)
-- Dependencies: 296
-- Name: COLUMN banner_mgmt_hist.title; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.banner_mgmt_hist.title IS 'title';


--
-- TOC entry 4074 (class 0 OID 0)
-- Dependencies: 296
-- Name: COLUMN banner_mgmt_hist.slug; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.banner_mgmt_hist.slug IS 'slug';


--
-- TOC entry 4075 (class 0 OID 0)
-- Dependencies: 296
-- Name: COLUMN banner_mgmt_hist.description; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.banner_mgmt_hist.description IS 'description';


--
-- TOC entry 4076 (class 0 OID 0)
-- Dependencies: 296
-- Name: COLUMN banner_mgmt_hist.link; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.banner_mgmt_hist.link IS 'link';


--
-- TOC entry 4077 (class 0 OID 0)
-- Dependencies: 296
-- Name: COLUMN banner_mgmt_hist.image; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.banner_mgmt_hist.image IS 'image';


--
-- TOC entry 4078 (class 0 OID 0)
-- Dependencies: 296
-- Name: COLUMN banner_mgmt_hist."position"; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.banner_mgmt_hist."position" IS 'position';


--
-- TOC entry 4079 (class 0 OID 0)
-- Dependencies: 296
-- Name: COLUMN banner_mgmt_hist.status; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.banner_mgmt_hist.status IS 'status';


--
-- TOC entry 4080 (class 0 OID 0)
-- Dependencies: 296
-- Name: COLUMN banner_mgmt_hist.action; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.banner_mgmt_hist.action IS 'action';


--
-- TOC entry 4081 (class 0 OID 0)
-- Dependencies: 296
-- Name: COLUMN banner_mgmt_hist.author_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.banner_mgmt_hist.author_id IS 'Author id';


--
-- TOC entry 4082 (class 0 OID 0)
-- Dependencies: 296
-- Name: COLUMN banner_mgmt_hist.created_at; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.banner_mgmt_hist.created_at IS 'Created time';


--
-- TOC entry 295 (class 1259 OID 16934)
-- Name: banner_mgmt_hist_id_seq; Type: SEQUENCE; Schema: public; Owner: ml_pg_user
--

CREATE SEQUENCE public.banner_mgmt_hist_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.banner_mgmt_hist_id_seq OWNER TO ml_pg_user;

--
-- TOC entry 4083 (class 0 OID 0)
-- Dependencies: 295
-- Name: banner_mgmt_hist_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ml_pg_user
--

ALTER SEQUENCE public.banner_mgmt_hist_id_seq OWNED BY public.banner_mgmt_hist.id;


--
-- TOC entry 261 (class 1259 OID 16731)
-- Name: banner_mgmt_id_seq; Type: SEQUENCE; Schema: public; Owner: ml_pg_user
--

CREATE SEQUENCE public.banner_mgmt_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.banner_mgmt_id_seq OWNER TO ml_pg_user;

--
-- TOC entry 4084 (class 0 OID 0)
-- Dependencies: 261
-- Name: banner_mgmt_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ml_pg_user
--

ALTER SEQUENCE public.banner_mgmt_id_seq OWNED BY public.banner_mgmt.id;


--
-- TOC entry 220 (class 1259 OID 16423)
-- Name: cache; Type: TABLE; Schema: public; Owner: ml_pg_user
--

CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache OWNER TO ml_pg_user;

--
-- TOC entry 221 (class 1259 OID 16430)
-- Name: cache_locks; Type: TABLE; Schema: public; Owner: ml_pg_user
--

CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache_locks OWNER TO ml_pg_user;

--
-- TOC entry 253 (class 1259 OID 16650)
-- Name: category_mgmt; Type: TABLE; Schema: public; Owner: ml_pg_user
--

CREATE TABLE public.category_mgmt (
    id integer NOT NULL,
    parent_id integer DEFAULT 0 NOT NULL,
    name character varying(50) NOT NULL,
    slug character varying(50) NOT NULL,
    description character varying(150),
    status smallint DEFAULT '0'::smallint NOT NULL,
    is_display boolean DEFAULT false NOT NULL,
    rank_order smallint DEFAULT '0'::smallint NOT NULL,
    is_delete boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.category_mgmt OWNER TO ml_pg_user;

--
-- TOC entry 4085 (class 0 OID 0)
-- Dependencies: 253
-- Name: COLUMN category_mgmt.parent_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.category_mgmt.parent_id IS 'Parent category';


--
-- TOC entry 4086 (class 0 OID 0)
-- Dependencies: 253
-- Name: COLUMN category_mgmt.name; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.category_mgmt.name IS 'Category name';


--
-- TOC entry 4087 (class 0 OID 0)
-- Dependencies: 253
-- Name: COLUMN category_mgmt.slug; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.category_mgmt.slug IS 'Category slug';


--
-- TOC entry 4088 (class 0 OID 0)
-- Dependencies: 253
-- Name: COLUMN category_mgmt.description; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.category_mgmt.description IS 'Category description';


--
-- TOC entry 4089 (class 0 OID 0)
-- Dependencies: 253
-- Name: COLUMN category_mgmt.status; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.category_mgmt.status IS 'Category status';


--
-- TOC entry 4090 (class 0 OID 0)
-- Dependencies: 253
-- Name: COLUMN category_mgmt.is_display; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.category_mgmt.is_display IS 'Display category';


--
-- TOC entry 4091 (class 0 OID 0)
-- Dependencies: 253
-- Name: COLUMN category_mgmt.rank_order; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.category_mgmt.rank_order IS 'Category order';


--
-- TOC entry 4092 (class 0 OID 0)
-- Dependencies: 253
-- Name: COLUMN category_mgmt.is_delete; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.category_mgmt.is_delete IS 'is deleted';


--
-- TOC entry 288 (class 1259 OID 16885)
-- Name: category_mgmt_hist; Type: TABLE; Schema: public; Owner: ml_pg_user
--

CREATE TABLE public.category_mgmt_hist (
    id integer NOT NULL,
    category_mgmt_id integer NOT NULL,
    parent_id integer,
    name character varying(50),
    slug character varying(50),
    description character varying(150),
    status smallint,
    is_display boolean,
    rank_order smallint,
    action smallint NOT NULL,
    author_id integer NOT NULL,
    created_at timestamp(0) without time zone NOT NULL
);


ALTER TABLE public.category_mgmt_hist OWNER TO ml_pg_user;

--
-- TOC entry 4093 (class 0 OID 0)
-- Dependencies: 288
-- Name: COLUMN category_mgmt_hist.id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.category_mgmt_hist.id IS 'Category id';


--
-- TOC entry 4094 (class 0 OID 0)
-- Dependencies: 288
-- Name: COLUMN category_mgmt_hist.category_mgmt_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.category_mgmt_hist.category_mgmt_id IS 'Category management id';


--
-- TOC entry 4095 (class 0 OID 0)
-- Dependencies: 288
-- Name: COLUMN category_mgmt_hist.parent_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.category_mgmt_hist.parent_id IS 'Parent category';


--
-- TOC entry 4096 (class 0 OID 0)
-- Dependencies: 288
-- Name: COLUMN category_mgmt_hist.name; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.category_mgmt_hist.name IS 'name';


--
-- TOC entry 4097 (class 0 OID 0)
-- Dependencies: 288
-- Name: COLUMN category_mgmt_hist.slug; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.category_mgmt_hist.slug IS 'slug';


--
-- TOC entry 4098 (class 0 OID 0)
-- Dependencies: 288
-- Name: COLUMN category_mgmt_hist.description; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.category_mgmt_hist.description IS 'description';


--
-- TOC entry 4099 (class 0 OID 0)
-- Dependencies: 288
-- Name: COLUMN category_mgmt_hist.status; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.category_mgmt_hist.status IS 'status';


--
-- TOC entry 4100 (class 0 OID 0)
-- Dependencies: 288
-- Name: COLUMN category_mgmt_hist.is_display; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.category_mgmt_hist.is_display IS 'Display category';


--
-- TOC entry 4101 (class 0 OID 0)
-- Dependencies: 288
-- Name: COLUMN category_mgmt_hist.rank_order; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.category_mgmt_hist.rank_order IS 'order';


--
-- TOC entry 4102 (class 0 OID 0)
-- Dependencies: 288
-- Name: COLUMN category_mgmt_hist.action; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.category_mgmt_hist.action IS 'action';


--
-- TOC entry 4103 (class 0 OID 0)
-- Dependencies: 288
-- Name: COLUMN category_mgmt_hist.author_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.category_mgmt_hist.author_id IS 'Author id';


--
-- TOC entry 4104 (class 0 OID 0)
-- Dependencies: 288
-- Name: COLUMN category_mgmt_hist.created_at; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.category_mgmt_hist.created_at IS 'Created time';


--
-- TOC entry 287 (class 1259 OID 16884)
-- Name: category_mgmt_hist_id_seq; Type: SEQUENCE; Schema: public; Owner: ml_pg_user
--

CREATE SEQUENCE public.category_mgmt_hist_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.category_mgmt_hist_id_seq OWNER TO ml_pg_user;

--
-- TOC entry 4105 (class 0 OID 0)
-- Dependencies: 287
-- Name: category_mgmt_hist_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ml_pg_user
--

ALTER SEQUENCE public.category_mgmt_hist_id_seq OWNED BY public.category_mgmt_hist.id;


--
-- TOC entry 252 (class 1259 OID 16649)
-- Name: category_mgmt_id_seq; Type: SEQUENCE; Schema: public; Owner: ml_pg_user
--

CREATE SEQUENCE public.category_mgmt_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.category_mgmt_id_seq OWNER TO ml_pg_user;

--
-- TOC entry 4106 (class 0 OID 0)
-- Dependencies: 252
-- Name: category_mgmt_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ml_pg_user
--

ALTER SEQUENCE public.category_mgmt_id_seq OWNED BY public.category_mgmt.id;


--
-- TOC entry 256 (class 1259 OID 16683)
-- Name: category_skill_mgmt; Type: TABLE; Schema: public; Owner: ml_pg_user
--

CREATE TABLE public.category_skill_mgmt (
    category_mgmt_id integer NOT NULL,
    skill_mgmt_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.category_skill_mgmt OWNER TO ml_pg_user;

--
-- TOC entry 4107 (class 0 OID 0)
-- Dependencies: 256
-- Name: COLUMN category_skill_mgmt.category_mgmt_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.category_skill_mgmt.category_mgmt_id IS 'Category ID';


--
-- TOC entry 4108 (class 0 OID 0)
-- Dependencies: 256
-- Name: COLUMN category_skill_mgmt.skill_mgmt_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.category_skill_mgmt.skill_mgmt_id IS 'Skill ID';


--
-- TOC entry 272 (class 1259 OID 16782)
-- Name: department_mst_hist; Type: TABLE; Schema: public; Owner: ml_pg_user
--

CREATE TABLE public.department_mst_hist (
    id integer NOT NULL,
    department_mst_id integer NOT NULL,
    code character varying(50),
    name character varying(50),
    status smallint,
    action smallint NOT NULL,
    author_id integer NOT NULL,
    created_at timestamp(0) without time zone NOT NULL
);


ALTER TABLE public.department_mst_hist OWNER TO ml_pg_user;

--
-- TOC entry 4109 (class 0 OID 0)
-- Dependencies: 272
-- Name: COLUMN department_mst_hist.id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.department_mst_hist.id IS 'Department history id';


--
-- TOC entry 4110 (class 0 OID 0)
-- Dependencies: 272
-- Name: COLUMN department_mst_hist.department_mst_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.department_mst_hist.department_mst_id IS 'Department id';


--
-- TOC entry 4111 (class 0 OID 0)
-- Dependencies: 272
-- Name: COLUMN department_mst_hist.code; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.department_mst_hist.code IS 'code';


--
-- TOC entry 4112 (class 0 OID 0)
-- Dependencies: 272
-- Name: COLUMN department_mst_hist.name; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.department_mst_hist.name IS 'name';


--
-- TOC entry 4113 (class 0 OID 0)
-- Dependencies: 272
-- Name: COLUMN department_mst_hist.status; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.department_mst_hist.status IS 'status';


--
-- TOC entry 4114 (class 0 OID 0)
-- Dependencies: 272
-- Name: COLUMN department_mst_hist.action; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.department_mst_hist.action IS 'action';


--
-- TOC entry 4115 (class 0 OID 0)
-- Dependencies: 272
-- Name: COLUMN department_mst_hist.author_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.department_mst_hist.author_id IS 'Author id';


--
-- TOC entry 4116 (class 0 OID 0)
-- Dependencies: 272
-- Name: COLUMN department_mst_hist.created_at; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.department_mst_hist.created_at IS 'Created time';


--
-- TOC entry 271 (class 1259 OID 16781)
-- Name: department_mst_hist_id_seq; Type: SEQUENCE; Schema: public; Owner: ml_pg_user
--

CREATE SEQUENCE public.department_mst_hist_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.department_mst_hist_id_seq OWNER TO ml_pg_user;

--
-- TOC entry 4117 (class 0 OID 0)
-- Dependencies: 271
-- Name: department_mst_hist_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ml_pg_user
--

ALTER SEQUENCE public.department_mst_hist_id_seq OWNED BY public.department_mst_hist.id;


--
-- TOC entry 237 (class 1259 OID 16546)
-- Name: department_mst_id_seq; Type: SEQUENCE; Schema: public; Owner: ml_pg_user
--

CREATE SEQUENCE public.department_mst_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.department_mst_id_seq OWNER TO ml_pg_user;

--
-- TOC entry 4118 (class 0 OID 0)
-- Dependencies: 237
-- Name: department_mst_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ml_pg_user
--

ALTER SEQUENCE public.department_mst_id_seq OWNED BY public.department_mst.id;


--
-- TOC entry 226 (class 1259 OID 16455)
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: ml_pg_user
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


ALTER TABLE public.failed_jobs OWNER TO ml_pg_user;

--
-- TOC entry 225 (class 1259 OID 16454)
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: ml_pg_user
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.failed_jobs_id_seq OWNER TO ml_pg_user;

--
-- TOC entry 4119 (class 0 OID 0)
-- Dependencies: 225
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ml_pg_user
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- TOC entry 278 (class 1259 OID 16818)
-- Name: feature_mst_hist; Type: TABLE; Schema: public; Owner: ml_pg_user
--

CREATE TABLE public.feature_mst_hist (
    id integer NOT NULL,
    feature_mst_id integer NOT NULL,
    name character varying(50),
    group_name character varying(50),
    description character varying(100),
    status smallint,
    action smallint NOT NULL,
    author_id integer NOT NULL,
    created_at timestamp(0) without time zone NOT NULL
);


ALTER TABLE public.feature_mst_hist OWNER TO ml_pg_user;

--
-- TOC entry 4120 (class 0 OID 0)
-- Dependencies: 278
-- Name: COLUMN feature_mst_hist.feature_mst_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.feature_mst_hist.feature_mst_id IS 'FeatureMst id';


--
-- TOC entry 4121 (class 0 OID 0)
-- Dependencies: 278
-- Name: COLUMN feature_mst_hist.name; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.feature_mst_hist.name IS 'name';


--
-- TOC entry 4122 (class 0 OID 0)
-- Dependencies: 278
-- Name: COLUMN feature_mst_hist.group_name; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.feature_mst_hist.group_name IS 'group name';


--
-- TOC entry 4123 (class 0 OID 0)
-- Dependencies: 278
-- Name: COLUMN feature_mst_hist.description; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.feature_mst_hist.description IS 'description';


--
-- TOC entry 4124 (class 0 OID 0)
-- Dependencies: 278
-- Name: COLUMN feature_mst_hist.status; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.feature_mst_hist.status IS 'status';


--
-- TOC entry 4125 (class 0 OID 0)
-- Dependencies: 278
-- Name: COLUMN feature_mst_hist.action; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.feature_mst_hist.action IS 'action';


--
-- TOC entry 4126 (class 0 OID 0)
-- Dependencies: 278
-- Name: COLUMN feature_mst_hist.author_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.feature_mst_hist.author_id IS 'Author id';


--
-- TOC entry 4127 (class 0 OID 0)
-- Dependencies: 278
-- Name: COLUMN feature_mst_hist.created_at; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.feature_mst_hist.created_at IS 'Created time';


--
-- TOC entry 277 (class 1259 OID 16817)
-- Name: feature_mst_hist_id_seq; Type: SEQUENCE; Schema: public; Owner: ml_pg_user
--

CREATE SEQUENCE public.feature_mst_hist_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.feature_mst_hist_id_seq OWNER TO ml_pg_user;

--
-- TOC entry 4128 (class 0 OID 0)
-- Dependencies: 277
-- Name: feature_mst_hist_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ml_pg_user
--

ALTER SEQUENCE public.feature_mst_hist_id_seq OWNED BY public.feature_mst_hist.id;


--
-- TOC entry 232 (class 1259 OID 16507)
-- Name: feature_mst_id_seq; Type: SEQUENCE; Schema: public; Owner: ml_pg_user
--

CREATE SEQUENCE public.feature_mst_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.feature_mst_id_seq OWNER TO ml_pg_user;

--
-- TOC entry 4129 (class 0 OID 0)
-- Dependencies: 232
-- Name: feature_mst_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ml_pg_user
--

ALTER SEQUENCE public.feature_mst_id_seq OWNED BY public.feature_mst.id;


--
-- TOC entry 224 (class 1259 OID 16447)
-- Name: job_batches; Type: TABLE; Schema: public; Owner: ml_pg_user
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


ALTER TABLE public.job_batches OWNER TO ml_pg_user;

--
-- TOC entry 223 (class 1259 OID 16438)
-- Name: jobs; Type: TABLE; Schema: public; Owner: ml_pg_user
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


ALTER TABLE public.jobs OWNER TO ml_pg_user;

--
-- TOC entry 222 (class 1259 OID 16437)
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: ml_pg_user
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.jobs_id_seq OWNER TO ml_pg_user;

--
-- TOC entry 4130 (class 0 OID 0)
-- Dependencies: 222
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ml_pg_user
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- TOC entry 248 (class 1259 OID 16609)
-- Name: language_mst; Type: TABLE; Schema: public; Owner: ml_pg_user
--

CREATE TABLE public.language_mst (
    id integer NOT NULL,
    abbreviation character varying(10) NOT NULL,
    name character varying(30) NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    is_delete boolean DEFAULT false NOT NULL,
    created_at character varying(255),
    updated_at character varying(255)
);


ALTER TABLE public.language_mst OWNER TO ml_pg_user;

--
-- TOC entry 4131 (class 0 OID 0)
-- Dependencies: 248
-- Name: COLUMN language_mst.id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.language_mst.id IS 'Language ID';


--
-- TOC entry 4132 (class 0 OID 0)
-- Dependencies: 248
-- Name: COLUMN language_mst.abbreviation; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.language_mst.abbreviation IS 'Language abbreviation code';


--
-- TOC entry 4133 (class 0 OID 0)
-- Dependencies: 248
-- Name: COLUMN language_mst.name; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.language_mst.name IS 'Language name';


--
-- TOC entry 4134 (class 0 OID 0)
-- Dependencies: 248
-- Name: COLUMN language_mst.is_active; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.language_mst.is_active IS 'Flag to indicate if language is active';


--
-- TOC entry 4135 (class 0 OID 0)
-- Dependencies: 248
-- Name: COLUMN language_mst.is_delete; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.language_mst.is_delete IS 'is deleted';


--
-- TOC entry 284 (class 1259 OID 16859)
-- Name: language_mst_hist; Type: TABLE; Schema: public; Owner: ml_pg_user
--

CREATE TABLE public.language_mst_hist (
    id integer NOT NULL,
    language_mst_id integer NOT NULL,
    abbreviation character varying(10),
    name character varying(30),
    is_active boolean,
    action smallint NOT NULL,
    author_id integer NOT NULL,
    created_at timestamp(0) without time zone NOT NULL
);


ALTER TABLE public.language_mst_hist OWNER TO ml_pg_user;

--
-- TOC entry 4136 (class 0 OID 0)
-- Dependencies: 284
-- Name: COLUMN language_mst_hist.language_mst_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.language_mst_hist.language_mst_id IS 'Language id';


--
-- TOC entry 4137 (class 0 OID 0)
-- Dependencies: 284
-- Name: COLUMN language_mst_hist.abbreviation; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.language_mst_hist.abbreviation IS 'abbreviation';


--
-- TOC entry 4138 (class 0 OID 0)
-- Dependencies: 284
-- Name: COLUMN language_mst_hist.name; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.language_mst_hist.name IS 'name';


--
-- TOC entry 4139 (class 0 OID 0)
-- Dependencies: 284
-- Name: COLUMN language_mst_hist.is_active; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.language_mst_hist.is_active IS 'active';


--
-- TOC entry 4140 (class 0 OID 0)
-- Dependencies: 284
-- Name: COLUMN language_mst_hist.action; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.language_mst_hist.action IS 'action';


--
-- TOC entry 4141 (class 0 OID 0)
-- Dependencies: 284
-- Name: COLUMN language_mst_hist.author_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.language_mst_hist.author_id IS 'Author id';


--
-- TOC entry 4142 (class 0 OID 0)
-- Dependencies: 284
-- Name: COLUMN language_mst_hist.created_at; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.language_mst_hist.created_at IS 'Created time';


--
-- TOC entry 283 (class 1259 OID 16858)
-- Name: language_mst_hist_id_seq; Type: SEQUENCE; Schema: public; Owner: ml_pg_user
--

CREATE SEQUENCE public.language_mst_hist_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.language_mst_hist_id_seq OWNER TO ml_pg_user;

--
-- TOC entry 4143 (class 0 OID 0)
-- Dependencies: 283
-- Name: language_mst_hist_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ml_pg_user
--

ALTER SEQUENCE public.language_mst_hist_id_seq OWNED BY public.language_mst_hist.id;


--
-- TOC entry 247 (class 1259 OID 16608)
-- Name: language_mst_id_seq; Type: SEQUENCE; Schema: public; Owner: ml_pg_user
--

CREATE SEQUENCE public.language_mst_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.language_mst_id_seq OWNER TO ml_pg_user;

--
-- TOC entry 4144 (class 0 OID 0)
-- Dependencies: 247
-- Name: language_mst_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ml_pg_user
--

ALTER SEQUENCE public.language_mst_id_seq OWNED BY public.language_mst.id;


--
-- TOC entry 215 (class 1259 OID 16390)
-- Name: migrations; Type: TABLE; Schema: public; Owner: ml_pg_user
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO ml_pg_user;

--
-- TOC entry 214 (class 1259 OID 16389)
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: ml_pg_user
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.migrations_id_seq OWNER TO ml_pg_user;

--
-- TOC entry 4145 (class 0 OID 0)
-- Dependencies: 214
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ml_pg_user
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- TOC entry 244 (class 1259 OID 16594)
-- Name: original_translator_mst; Type: TABLE; Schema: public; Owner: ml_pg_user
--

CREATE TABLE public.original_translator_mst (
    id integer NOT NULL,
    "table" character varying(64) NOT NULL,
    "column" character varying(64) NOT NULL,
    field_id integer NOT NULL,
    is_delete boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.original_translator_mst OWNER TO ml_pg_user;

--
-- TOC entry 4146 (class 0 OID 0)
-- Dependencies: 244
-- Name: COLUMN original_translator_mst.id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.original_translator_mst.id IS 'Original translator ID';


--
-- TOC entry 4147 (class 0 OID 0)
-- Dependencies: 244
-- Name: COLUMN original_translator_mst."table"; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.original_translator_mst."table" IS 'Table name';


--
-- TOC entry 4148 (class 0 OID 0)
-- Dependencies: 244
-- Name: COLUMN original_translator_mst."column"; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.original_translator_mst."column" IS 'Column name';


--
-- TOC entry 4149 (class 0 OID 0)
-- Dependencies: 244
-- Name: COLUMN original_translator_mst.field_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.original_translator_mst.field_id IS 'Field id';


--
-- TOC entry 4150 (class 0 OID 0)
-- Dependencies: 244
-- Name: COLUMN original_translator_mst.is_delete; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.original_translator_mst.is_delete IS 'is deleted';


--
-- TOC entry 282 (class 1259 OID 16847)
-- Name: original_translator_mst_hist; Type: TABLE; Schema: public; Owner: ml_pg_user
--

CREATE TABLE public.original_translator_mst_hist (
    id integer NOT NULL,
    original_translator_mst_id integer NOT NULL,
    "table" character varying(64),
    "column" character varying(64),
    field_id integer,
    action smallint NOT NULL,
    author_id integer NOT NULL,
    created_at timestamp(0) without time zone NOT NULL
);


ALTER TABLE public.original_translator_mst_hist OWNER TO ml_pg_user;

--
-- TOC entry 4151 (class 0 OID 0)
-- Dependencies: 282
-- Name: COLUMN original_translator_mst_hist.original_translator_mst_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.original_translator_mst_hist.original_translator_mst_id IS 'Original translator id';


--
-- TOC entry 4152 (class 0 OID 0)
-- Dependencies: 282
-- Name: COLUMN original_translator_mst_hist."table"; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.original_translator_mst_hist."table" IS 'Table name';


--
-- TOC entry 4153 (class 0 OID 0)
-- Dependencies: 282
-- Name: COLUMN original_translator_mst_hist."column"; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.original_translator_mst_hist."column" IS 'Column name';


--
-- TOC entry 4154 (class 0 OID 0)
-- Dependencies: 282
-- Name: COLUMN original_translator_mst_hist.field_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.original_translator_mst_hist.field_id IS 'Field id';


--
-- TOC entry 4155 (class 0 OID 0)
-- Dependencies: 282
-- Name: COLUMN original_translator_mst_hist.action; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.original_translator_mst_hist.action IS 'action';


--
-- TOC entry 4156 (class 0 OID 0)
-- Dependencies: 282
-- Name: COLUMN original_translator_mst_hist.author_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.original_translator_mst_hist.author_id IS 'Author id';


--
-- TOC entry 4157 (class 0 OID 0)
-- Dependencies: 282
-- Name: COLUMN original_translator_mst_hist.created_at; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.original_translator_mst_hist.created_at IS 'Created time';


--
-- TOC entry 281 (class 1259 OID 16846)
-- Name: original_translator_mst_hist_id_seq; Type: SEQUENCE; Schema: public; Owner: ml_pg_user
--

CREATE SEQUENCE public.original_translator_mst_hist_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.original_translator_mst_hist_id_seq OWNER TO ml_pg_user;

--
-- TOC entry 4158 (class 0 OID 0)
-- Dependencies: 281
-- Name: original_translator_mst_hist_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ml_pg_user
--

ALTER SEQUENCE public.original_translator_mst_hist_id_seq OWNED BY public.original_translator_mst_hist.id;


--
-- TOC entry 243 (class 1259 OID 16593)
-- Name: original_translator_mst_id_seq; Type: SEQUENCE; Schema: public; Owner: ml_pg_user
--

CREATE SEQUENCE public.original_translator_mst_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.original_translator_mst_id_seq OWNER TO ml_pg_user;

--
-- TOC entry 4159 (class 0 OID 0)
-- Dependencies: 243
-- Name: original_translator_mst_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ml_pg_user
--

ALTER SEQUENCE public.original_translator_mst_id_seq OWNED BY public.original_translator_mst.id;


--
-- TOC entry 218 (class 1259 OID 16407)
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: ml_pg_user
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


ALTER TABLE public.password_reset_tokens OWNER TO ml_pg_user;

--
-- TOC entry 274 (class 1259 OID 16794)
-- Name: policy_department_mst_hist; Type: TABLE; Schema: public; Owner: ml_pg_user
--

CREATE TABLE public.policy_department_mst_hist (
    id integer NOT NULL,
    policy_department_mst_id integer NOT NULL,
    table_name integer,
    row_id integer,
    action smallint NOT NULL,
    author_id integer NOT NULL,
    created_at timestamp(0) without time zone NOT NULL
);


ALTER TABLE public.policy_department_mst_hist OWNER TO ml_pg_user;

--
-- TOC entry 4160 (class 0 OID 0)
-- Dependencies: 274
-- Name: COLUMN policy_department_mst_hist.id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.policy_department_mst_hist.id IS 'Policy department history id';


--
-- TOC entry 4161 (class 0 OID 0)
-- Dependencies: 274
-- Name: COLUMN policy_department_mst_hist.policy_department_mst_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.policy_department_mst_hist.policy_department_mst_id IS 'Policy department id';


--
-- TOC entry 4162 (class 0 OID 0)
-- Dependencies: 274
-- Name: COLUMN policy_department_mst_hist.table_name; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.policy_department_mst_hist.table_name IS 'table name';


--
-- TOC entry 4163 (class 0 OID 0)
-- Dependencies: 274
-- Name: COLUMN policy_department_mst_hist.row_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.policy_department_mst_hist.row_id IS 'Row id';


--
-- TOC entry 4164 (class 0 OID 0)
-- Dependencies: 274
-- Name: COLUMN policy_department_mst_hist.action; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.policy_department_mst_hist.action IS 'action';


--
-- TOC entry 4165 (class 0 OID 0)
-- Dependencies: 274
-- Name: COLUMN policy_department_mst_hist.author_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.policy_department_mst_hist.author_id IS 'Author id';


--
-- TOC entry 4166 (class 0 OID 0)
-- Dependencies: 274
-- Name: COLUMN policy_department_mst_hist.created_at; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.policy_department_mst_hist.created_at IS 'Created time';


--
-- TOC entry 273 (class 1259 OID 16793)
-- Name: policy_department_mst_hist_id_seq; Type: SEQUENCE; Schema: public; Owner: ml_pg_user
--

CREATE SEQUENCE public.policy_department_mst_hist_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.policy_department_mst_hist_id_seq OWNER TO ml_pg_user;

--
-- TOC entry 4167 (class 0 OID 0)
-- Dependencies: 273
-- Name: policy_department_mst_hist_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ml_pg_user
--

ALTER SEQUENCE public.policy_department_mst_hist_id_seq OWNED BY public.policy_department_mst_hist.id;


--
-- TOC entry 240 (class 1259 OID 16570)
-- Name: policy_department_mst_id_seq; Type: SEQUENCE; Schema: public; Owner: ml_pg_user
--

CREATE SEQUENCE public.policy_department_mst_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.policy_department_mst_id_seq OWNER TO ml_pg_user;

--
-- TOC entry 4168 (class 0 OID 0)
-- Dependencies: 240
-- Name: policy_department_mst_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ml_pg_user
--

ALTER SEQUENCE public.policy_department_mst_id_seq OWNED BY public.policy_department_mst.id;


--
-- TOC entry 276 (class 1259 OID 16806)
-- Name: role_mst_hist; Type: TABLE; Schema: public; Owner: ml_pg_user
--

CREATE TABLE public.role_mst_hist (
    id integer NOT NULL,
    role_mst_id integer NOT NULL,
    name character varying(30),
    permission character varying(50),
    is_active boolean,
    action smallint NOT NULL,
    author_id integer NOT NULL,
    created_at timestamp(0) without time zone NOT NULL
);


ALTER TABLE public.role_mst_hist OWNER TO ml_pg_user;

--
-- TOC entry 4169 (class 0 OID 0)
-- Dependencies: 276
-- Name: COLUMN role_mst_hist.role_mst_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.role_mst_hist.role_mst_id IS 'role_id';


--
-- TOC entry 4170 (class 0 OID 0)
-- Dependencies: 276
-- Name: COLUMN role_mst_hist.name; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.role_mst_hist.name IS 'name';


--
-- TOC entry 4171 (class 0 OID 0)
-- Dependencies: 276
-- Name: COLUMN role_mst_hist.permission; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.role_mst_hist.permission IS 'description';


--
-- TOC entry 4172 (class 0 OID 0)
-- Dependencies: 276
-- Name: COLUMN role_mst_hist.is_active; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.role_mst_hist.is_active IS 'active';


--
-- TOC entry 4173 (class 0 OID 0)
-- Dependencies: 276
-- Name: COLUMN role_mst_hist.action; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.role_mst_hist.action IS 'action';


--
-- TOC entry 4174 (class 0 OID 0)
-- Dependencies: 276
-- Name: COLUMN role_mst_hist.author_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.role_mst_hist.author_id IS 'Author id';


--
-- TOC entry 4175 (class 0 OID 0)
-- Dependencies: 276
-- Name: COLUMN role_mst_hist.created_at; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.role_mst_hist.created_at IS 'Created time';


--
-- TOC entry 275 (class 1259 OID 16805)
-- Name: role_mst_hist_id_seq; Type: SEQUENCE; Schema: public; Owner: ml_pg_user
--

CREATE SEQUENCE public.role_mst_hist_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.role_mst_hist_id_seq OWNER TO ml_pg_user;

--
-- TOC entry 4176 (class 0 OID 0)
-- Dependencies: 275
-- Name: role_mst_hist_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ml_pg_user
--

ALTER SEQUENCE public.role_mst_hist_id_seq OWNED BY public.role_mst_hist.id;


--
-- TOC entry 229 (class 1259 OID 16481)
-- Name: role_mst_id_seq; Type: SEQUENCE; Schema: public; Owner: ml_pg_user
--

CREATE SEQUENCE public.role_mst_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.role_mst_id_seq OWNER TO ml_pg_user;

--
-- TOC entry 4177 (class 0 OID 0)
-- Dependencies: 229
-- Name: role_mst_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ml_pg_user
--

ALTER SEQUENCE public.role_mst_id_seq OWNED BY public.role_mst.id;


--
-- TOC entry 219 (class 1259 OID 16414)
-- Name: sessions; Type: TABLE; Schema: public; Owner: ml_pg_user
--

CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id bigint,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);


ALTER TABLE public.sessions OWNER TO ml_pg_user;

--
-- TOC entry 264 (class 1259 OID 16743)
-- Name: setting_link_mgmt; Type: TABLE; Schema: public; Owner: ml_pg_user
--

CREATE TABLE public.setting_link_mgmt (
    id integer NOT NULL,
    key character varying(30) NOT NULL,
    value character varying(100) NOT NULL,
    is_delete boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.setting_link_mgmt OWNER TO ml_pg_user;

--
-- TOC entry 4178 (class 0 OID 0)
-- Dependencies: 264
-- Name: COLUMN setting_link_mgmt.key; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.setting_link_mgmt.key IS 'Setting link key';


--
-- TOC entry 4179 (class 0 OID 0)
-- Dependencies: 264
-- Name: COLUMN setting_link_mgmt.value; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.setting_link_mgmt.value IS 'Setting link value';


--
-- TOC entry 4180 (class 0 OID 0)
-- Dependencies: 264
-- Name: COLUMN setting_link_mgmt.is_delete; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.setting_link_mgmt.is_delete IS 'is deleted';


--
-- TOC entry 300 (class 1259 OID 16963)
-- Name: setting_link_mgmt_hist; Type: TABLE; Schema: public; Owner: ml_pg_user
--

CREATE TABLE public.setting_link_mgmt_hist (
    id integer NOT NULL,
    setting_link_id integer NOT NULL,
    key character varying(30),
    value character varying(100),
    action smallint NOT NULL,
    author_id integer NOT NULL,
    created_at timestamp(0) without time zone NOT NULL
);


ALTER TABLE public.setting_link_mgmt_hist OWNER TO ml_pg_user;

--
-- TOC entry 4181 (class 0 OID 0)
-- Dependencies: 300
-- Name: COLUMN setting_link_mgmt_hist.id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.setting_link_mgmt_hist.id IS 'Setting link history id';


--
-- TOC entry 4182 (class 0 OID 0)
-- Dependencies: 300
-- Name: COLUMN setting_link_mgmt_hist.setting_link_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.setting_link_mgmt_hist.setting_link_id IS 'Setting link id';


--
-- TOC entry 4183 (class 0 OID 0)
-- Dependencies: 300
-- Name: COLUMN setting_link_mgmt_hist.key; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.setting_link_mgmt_hist.key IS 'key';


--
-- TOC entry 4184 (class 0 OID 0)
-- Dependencies: 300
-- Name: COLUMN setting_link_mgmt_hist.value; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.setting_link_mgmt_hist.value IS 'value';


--
-- TOC entry 4185 (class 0 OID 0)
-- Dependencies: 300
-- Name: COLUMN setting_link_mgmt_hist.action; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.setting_link_mgmt_hist.action IS 'action';


--
-- TOC entry 4186 (class 0 OID 0)
-- Dependencies: 300
-- Name: COLUMN setting_link_mgmt_hist.author_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.setting_link_mgmt_hist.author_id IS 'Author id';


--
-- TOC entry 4187 (class 0 OID 0)
-- Dependencies: 300
-- Name: COLUMN setting_link_mgmt_hist.created_at; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.setting_link_mgmt_hist.created_at IS 'Created time';


--
-- TOC entry 299 (class 1259 OID 16962)
-- Name: setting_link_mgmt_hist_id_seq; Type: SEQUENCE; Schema: public; Owner: ml_pg_user
--

CREATE SEQUENCE public.setting_link_mgmt_hist_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.setting_link_mgmt_hist_id_seq OWNER TO ml_pg_user;

--
-- TOC entry 4188 (class 0 OID 0)
-- Dependencies: 299
-- Name: setting_link_mgmt_hist_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ml_pg_user
--

ALTER SEQUENCE public.setting_link_mgmt_hist_id_seq OWNED BY public.setting_link_mgmt_hist.id;


--
-- TOC entry 263 (class 1259 OID 16742)
-- Name: setting_link_mgmt_id_seq; Type: SEQUENCE; Schema: public; Owner: ml_pg_user
--

CREATE SEQUENCE public.setting_link_mgmt_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.setting_link_mgmt_id_seq OWNER TO ml_pg_user;

--
-- TOC entry 4189 (class 0 OID 0)
-- Dependencies: 263
-- Name: setting_link_mgmt_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ml_pg_user
--

ALTER SEQUENCE public.setting_link_mgmt_id_seq OWNED BY public.setting_link_mgmt.id;


--
-- TOC entry 258 (class 1259 OID 16699)
-- Name: skill_description_mgmt; Type: TABLE; Schema: public; Owner: ml_pg_user
--

CREATE TABLE public.skill_description_mgmt (
    id integer NOT NULL,
    parent_id integer DEFAULT 0 NOT NULL,
    title character varying(100) NOT NULL,
    summary character varying(255) NOT NULL,
    article text NOT NULL,
    status smallint DEFAULT '0'::smallint NOT NULL,
    is_display boolean DEFAULT false NOT NULL,
    rank_order smallint DEFAULT '0'::smallint NOT NULL,
    skill_mgmt_id integer NOT NULL,
    is_delete boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.skill_description_mgmt OWNER TO ml_pg_user;

--
-- TOC entry 4190 (class 0 OID 0)
-- Dependencies: 258
-- Name: COLUMN skill_description_mgmt.parent_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.skill_description_mgmt.parent_id IS 'Parent skill';


--
-- TOC entry 4191 (class 0 OID 0)
-- Dependencies: 258
-- Name: COLUMN skill_description_mgmt.title; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.skill_description_mgmt.title IS 'Title skill';


--
-- TOC entry 4192 (class 0 OID 0)
-- Dependencies: 258
-- Name: COLUMN skill_description_mgmt.summary; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.skill_description_mgmt.summary IS 'Summary skill';


--
-- TOC entry 4193 (class 0 OID 0)
-- Dependencies: 258
-- Name: COLUMN skill_description_mgmt.article; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.skill_description_mgmt.article IS 'Article skill';


--
-- TOC entry 4194 (class 0 OID 0)
-- Dependencies: 258
-- Name: COLUMN skill_description_mgmt.status; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.skill_description_mgmt.status IS 'Skill status';


--
-- TOC entry 4195 (class 0 OID 0)
-- Dependencies: 258
-- Name: COLUMN skill_description_mgmt.is_display; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.skill_description_mgmt.is_display IS 'Display skill';


--
-- TOC entry 4196 (class 0 OID 0)
-- Dependencies: 258
-- Name: COLUMN skill_description_mgmt.rank_order; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.skill_description_mgmt.rank_order IS 'Rank order';


--
-- TOC entry 4197 (class 0 OID 0)
-- Dependencies: 258
-- Name: COLUMN skill_description_mgmt.skill_mgmt_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.skill_description_mgmt.skill_mgmt_id IS 'Skill ID';


--
-- TOC entry 4198 (class 0 OID 0)
-- Dependencies: 258
-- Name: COLUMN skill_description_mgmt.is_delete; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.skill_description_mgmt.is_delete IS 'is deleted';


--
-- TOC entry 292 (class 1259 OID 16909)
-- Name: skill_description_mgmt_hist; Type: TABLE; Schema: public; Owner: ml_pg_user
--

CREATE TABLE public.skill_description_mgmt_hist (
    id integer NOT NULL,
    skill_description_mgmt_id integer NOT NULL,
    parent_id integer,
    title character varying(100),
    summary character varying(255),
    article text,
    status smallint,
    is_display boolean,
    rank_order smallint,
    skill_id integer,
    action smallint NOT NULL,
    author_id integer NOT NULL,
    created_at timestamp(0) without time zone NOT NULL
);


ALTER TABLE public.skill_description_mgmt_hist OWNER TO ml_pg_user;

--
-- TOC entry 4199 (class 0 OID 0)
-- Dependencies: 292
-- Name: COLUMN skill_description_mgmt_hist.id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.skill_description_mgmt_hist.id IS 'Skill description id';


--
-- TOC entry 4200 (class 0 OID 0)
-- Dependencies: 292
-- Name: COLUMN skill_description_mgmt_hist.skill_description_mgmt_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.skill_description_mgmt_hist.skill_description_mgmt_id IS 'Skill description management id';


--
-- TOC entry 4201 (class 0 OID 0)
-- Dependencies: 292
-- Name: COLUMN skill_description_mgmt_hist.parent_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.skill_description_mgmt_hist.parent_id IS 'Parent skill description';


--
-- TOC entry 4202 (class 0 OID 0)
-- Dependencies: 292
-- Name: COLUMN skill_description_mgmt_hist.title; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.skill_description_mgmt_hist.title IS 'Title';


--
-- TOC entry 4203 (class 0 OID 0)
-- Dependencies: 292
-- Name: COLUMN skill_description_mgmt_hist.summary; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.skill_description_mgmt_hist.summary IS 'Summary';


--
-- TOC entry 4204 (class 0 OID 0)
-- Dependencies: 292
-- Name: COLUMN skill_description_mgmt_hist.article; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.skill_description_mgmt_hist.article IS 'Article';


--
-- TOC entry 4205 (class 0 OID 0)
-- Dependencies: 292
-- Name: COLUMN skill_description_mgmt_hist.status; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.skill_description_mgmt_hist.status IS 'management status';


--
-- TOC entry 4206 (class 0 OID 0)
-- Dependencies: 292
-- Name: COLUMN skill_description_mgmt_hist.is_display; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.skill_description_mgmt_hist.is_display IS 'Display';


--
-- TOC entry 4207 (class 0 OID 0)
-- Dependencies: 292
-- Name: COLUMN skill_description_mgmt_hist.rank_order; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.skill_description_mgmt_hist.rank_order IS 'management order';


--
-- TOC entry 4208 (class 0 OID 0)
-- Dependencies: 292
-- Name: COLUMN skill_description_mgmt_hist.skill_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.skill_description_mgmt_hist.skill_id IS 'Skill id';


--
-- TOC entry 4209 (class 0 OID 0)
-- Dependencies: 292
-- Name: COLUMN skill_description_mgmt_hist.action; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.skill_description_mgmt_hist.action IS 'management action';


--
-- TOC entry 4210 (class 0 OID 0)
-- Dependencies: 292
-- Name: COLUMN skill_description_mgmt_hist.author_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.skill_description_mgmt_hist.author_id IS 'Author id';


--
-- TOC entry 4211 (class 0 OID 0)
-- Dependencies: 292
-- Name: COLUMN skill_description_mgmt_hist.created_at; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.skill_description_mgmt_hist.created_at IS 'Created time';


--
-- TOC entry 291 (class 1259 OID 16908)
-- Name: skill_description_mgmt_hist_id_seq; Type: SEQUENCE; Schema: public; Owner: ml_pg_user
--

CREATE SEQUENCE public.skill_description_mgmt_hist_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.skill_description_mgmt_hist_id_seq OWNER TO ml_pg_user;

--
-- TOC entry 4212 (class 0 OID 0)
-- Dependencies: 291
-- Name: skill_description_mgmt_hist_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ml_pg_user
--

ALTER SEQUENCE public.skill_description_mgmt_hist_id_seq OWNED BY public.skill_description_mgmt_hist.id;


--
-- TOC entry 257 (class 1259 OID 16698)
-- Name: skill_description_mgmt_id_seq; Type: SEQUENCE; Schema: public; Owner: ml_pg_user
--

CREATE SEQUENCE public.skill_description_mgmt_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.skill_description_mgmt_id_seq OWNER TO ml_pg_user;

--
-- TOC entry 4213 (class 0 OID 0)
-- Dependencies: 257
-- Name: skill_description_mgmt_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ml_pg_user
--

ALTER SEQUENCE public.skill_description_mgmt_id_seq OWNED BY public.skill_description_mgmt.id;


--
-- TOC entry 255 (class 1259 OID 16667)
-- Name: skill_mgmt; Type: TABLE; Schema: public; Owner: ml_pg_user
--

CREATE TABLE public.skill_mgmt (
    id integer NOT NULL,
    parent_id integer DEFAULT 0 NOT NULL,
    name character varying(50) NOT NULL,
    slug character varying(50) NOT NULL,
    status smallint DEFAULT '0'::smallint NOT NULL,
    is_display boolean DEFAULT false NOT NULL,
    rank_order smallint DEFAULT '0'::smallint NOT NULL,
    is_delete boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.skill_mgmt OWNER TO ml_pg_user;

--
-- TOC entry 4214 (class 0 OID 0)
-- Dependencies: 255
-- Name: COLUMN skill_mgmt.parent_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.skill_mgmt.parent_id IS 'Parent skill';


--
-- TOC entry 4215 (class 0 OID 0)
-- Dependencies: 255
-- Name: COLUMN skill_mgmt.name; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.skill_mgmt.name IS 'Skill name';


--
-- TOC entry 4216 (class 0 OID 0)
-- Dependencies: 255
-- Name: COLUMN skill_mgmt.slug; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.skill_mgmt.slug IS 'Skill slug';


--
-- TOC entry 4217 (class 0 OID 0)
-- Dependencies: 255
-- Name: COLUMN skill_mgmt.status; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.skill_mgmt.status IS 'Skill status';


--
-- TOC entry 4218 (class 0 OID 0)
-- Dependencies: 255
-- Name: COLUMN skill_mgmt.is_display; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.skill_mgmt.is_display IS 'Display skill';


--
-- TOC entry 4219 (class 0 OID 0)
-- Dependencies: 255
-- Name: COLUMN skill_mgmt.rank_order; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.skill_mgmt.rank_order IS 'Skill order';


--
-- TOC entry 4220 (class 0 OID 0)
-- Dependencies: 255
-- Name: COLUMN skill_mgmt.is_delete; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.skill_mgmt.is_delete IS 'is deleted';


--
-- TOC entry 290 (class 1259 OID 16897)
-- Name: skill_mgmt_hist; Type: TABLE; Schema: public; Owner: ml_pg_user
--

CREATE TABLE public.skill_mgmt_hist (
    id integer NOT NULL,
    skill_mgmt_id integer NOT NULL,
    parent_id integer,
    name character varying(50),
    slug character varying(50),
    status smallint,
    is_display boolean,
    rank_order smallint,
    action smallint NOT NULL,
    author_id integer NOT NULL,
    created_at timestamp(0) without time zone NOT NULL
);


ALTER TABLE public.skill_mgmt_hist OWNER TO ml_pg_user;

--
-- TOC entry 4221 (class 0 OID 0)
-- Dependencies: 290
-- Name: COLUMN skill_mgmt_hist.id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.skill_mgmt_hist.id IS 'Skill id';


--
-- TOC entry 4222 (class 0 OID 0)
-- Dependencies: 290
-- Name: COLUMN skill_mgmt_hist.skill_mgmt_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.skill_mgmt_hist.skill_mgmt_id IS 'Skill management id';


--
-- TOC entry 4223 (class 0 OID 0)
-- Dependencies: 290
-- Name: COLUMN skill_mgmt_hist.parent_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.skill_mgmt_hist.parent_id IS 'Parent skill';


--
-- TOC entry 4224 (class 0 OID 0)
-- Dependencies: 290
-- Name: COLUMN skill_mgmt_hist.name; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.skill_mgmt_hist.name IS 'name';


--
-- TOC entry 4225 (class 0 OID 0)
-- Dependencies: 290
-- Name: COLUMN skill_mgmt_hist.slug; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.skill_mgmt_hist.slug IS 'slug';


--
-- TOC entry 4226 (class 0 OID 0)
-- Dependencies: 290
-- Name: COLUMN skill_mgmt_hist.status; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.skill_mgmt_hist.status IS 'status';


--
-- TOC entry 4227 (class 0 OID 0)
-- Dependencies: 290
-- Name: COLUMN skill_mgmt_hist.is_display; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.skill_mgmt_hist.is_display IS 'Display skill';


--
-- TOC entry 4228 (class 0 OID 0)
-- Dependencies: 290
-- Name: COLUMN skill_mgmt_hist.rank_order; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.skill_mgmt_hist.rank_order IS 'order';


--
-- TOC entry 4229 (class 0 OID 0)
-- Dependencies: 290
-- Name: COLUMN skill_mgmt_hist.action; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.skill_mgmt_hist.action IS 'action';


--
-- TOC entry 4230 (class 0 OID 0)
-- Dependencies: 290
-- Name: COLUMN skill_mgmt_hist.author_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.skill_mgmt_hist.author_id IS 'Author id';


--
-- TOC entry 4231 (class 0 OID 0)
-- Dependencies: 290
-- Name: COLUMN skill_mgmt_hist.created_at; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.skill_mgmt_hist.created_at IS 'Created time';


--
-- TOC entry 289 (class 1259 OID 16896)
-- Name: skill_mgmt_hist_id_seq; Type: SEQUENCE; Schema: public; Owner: ml_pg_user
--

CREATE SEQUENCE public.skill_mgmt_hist_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.skill_mgmt_hist_id_seq OWNER TO ml_pg_user;

--
-- TOC entry 4232 (class 0 OID 0)
-- Dependencies: 289
-- Name: skill_mgmt_hist_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ml_pg_user
--

ALTER SEQUENCE public.skill_mgmt_hist_id_seq OWNED BY public.skill_mgmt_hist.id;


--
-- TOC entry 254 (class 1259 OID 16666)
-- Name: skill_mgmt_id_seq; Type: SEQUENCE; Schema: public; Owner: ml_pg_user
--

CREATE SEQUENCE public.skill_mgmt_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.skill_mgmt_id_seq OWNER TO ml_pg_user;

--
-- TOC entry 4233 (class 0 OID 0)
-- Dependencies: 254
-- Name: skill_mgmt_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ml_pg_user
--

ALTER SEQUENCE public.skill_mgmt_id_seq OWNED BY public.skill_mgmt.id;


--
-- TOC entry 260 (class 1259 OID 16723)
-- Name: slider_mgmt; Type: TABLE; Schema: public; Owner: ml_pg_user
--

CREATE TABLE public.slider_mgmt (
    id integer NOT NULL,
    title character varying(50) NOT NULL,
    slug character varying(50) NOT NULL,
    link character varying(100) NOT NULL,
    image character varying(100) NOT NULL,
    status smallint DEFAULT '0'::smallint NOT NULL,
    is_delete boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.slider_mgmt OWNER TO ml_pg_user;

--
-- TOC entry 4234 (class 0 OID 0)
-- Dependencies: 260
-- Name: COLUMN slider_mgmt.title; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.slider_mgmt.title IS 'Slider title';


--
-- TOC entry 4235 (class 0 OID 0)
-- Dependencies: 260
-- Name: COLUMN slider_mgmt.slug; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.slider_mgmt.slug IS 'Slider slug';


--
-- TOC entry 4236 (class 0 OID 0)
-- Dependencies: 260
-- Name: COLUMN slider_mgmt.link; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.slider_mgmt.link IS 'Slider path image';


--
-- TOC entry 4237 (class 0 OID 0)
-- Dependencies: 260
-- Name: COLUMN slider_mgmt.image; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.slider_mgmt.image IS 'Slider image name';


--
-- TOC entry 4238 (class 0 OID 0)
-- Dependencies: 260
-- Name: COLUMN slider_mgmt.status; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.slider_mgmt.status IS 'Slider image';


--
-- TOC entry 4239 (class 0 OID 0)
-- Dependencies: 260
-- Name: COLUMN slider_mgmt.is_delete; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.slider_mgmt.is_delete IS 'is deleted';


--
-- TOC entry 294 (class 1259 OID 16923)
-- Name: slider_mgmt_hist; Type: TABLE; Schema: public; Owner: ml_pg_user
--

CREATE TABLE public.slider_mgmt_hist (
    id integer NOT NULL,
    slider_mgmt_id integer NOT NULL,
    title character varying(50),
    slug character varying(50),
    link character varying(100),
    image character varying(100),
    status smallint,
    action smallint NOT NULL,
    author_id integer NOT NULL,
    created_at timestamp(0) without time zone NOT NULL
);


ALTER TABLE public.slider_mgmt_hist OWNER TO ml_pg_user;

--
-- TOC entry 4240 (class 0 OID 0)
-- Dependencies: 294
-- Name: COLUMN slider_mgmt_hist.id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.slider_mgmt_hist.id IS 'Slider history id';


--
-- TOC entry 4241 (class 0 OID 0)
-- Dependencies: 294
-- Name: COLUMN slider_mgmt_hist.slider_mgmt_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.slider_mgmt_hist.slider_mgmt_id IS 'id';


--
-- TOC entry 4242 (class 0 OID 0)
-- Dependencies: 294
-- Name: COLUMN slider_mgmt_hist.title; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.slider_mgmt_hist.title IS 'title';


--
-- TOC entry 4243 (class 0 OID 0)
-- Dependencies: 294
-- Name: COLUMN slider_mgmt_hist.slug; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.slider_mgmt_hist.slug IS 'slug';


--
-- TOC entry 4244 (class 0 OID 0)
-- Dependencies: 294
-- Name: COLUMN slider_mgmt_hist.link; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.slider_mgmt_hist.link IS 'link';


--
-- TOC entry 4245 (class 0 OID 0)
-- Dependencies: 294
-- Name: COLUMN slider_mgmt_hist.image; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.slider_mgmt_hist.image IS 'image';


--
-- TOC entry 4246 (class 0 OID 0)
-- Dependencies: 294
-- Name: COLUMN slider_mgmt_hist.status; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.slider_mgmt_hist.status IS 'status';


--
-- TOC entry 4247 (class 0 OID 0)
-- Dependencies: 294
-- Name: COLUMN slider_mgmt_hist.action; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.slider_mgmt_hist.action IS 'action';


--
-- TOC entry 4248 (class 0 OID 0)
-- Dependencies: 294
-- Name: COLUMN slider_mgmt_hist.author_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.slider_mgmt_hist.author_id IS 'Author id';


--
-- TOC entry 4249 (class 0 OID 0)
-- Dependencies: 294
-- Name: COLUMN slider_mgmt_hist.created_at; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.slider_mgmt_hist.created_at IS 'Created time';


--
-- TOC entry 293 (class 1259 OID 16922)
-- Name: slider_mgmt_hist_id_seq; Type: SEQUENCE; Schema: public; Owner: ml_pg_user
--

CREATE SEQUENCE public.slider_mgmt_hist_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.slider_mgmt_hist_id_seq OWNER TO ml_pg_user;

--
-- TOC entry 4250 (class 0 OID 0)
-- Dependencies: 293
-- Name: slider_mgmt_hist_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ml_pg_user
--

ALTER SEQUENCE public.slider_mgmt_hist_id_seq OWNED BY public.slider_mgmt_hist.id;


--
-- TOC entry 259 (class 1259 OID 16722)
-- Name: slider_mgmt_id_seq; Type: SEQUENCE; Schema: public; Owner: ml_pg_user
--

CREATE SEQUENCE public.slider_mgmt_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.slider_mgmt_id_seq OWNER TO ml_pg_user;

--
-- TOC entry 4251 (class 0 OID 0)
-- Dependencies: 259
-- Name: slider_mgmt_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ml_pg_user
--

ALTER SEQUENCE public.slider_mgmt_id_seq OWNED BY public.slider_mgmt.id;


--
-- TOC entry 268 (class 1259 OID 16762)
-- Name: social_mgmt; Type: TABLE; Schema: public; Owner: ml_pg_user
--

CREATE TABLE public.social_mgmt (
    id bigint NOT NULL,
    name character varying(50) NOT NULL,
    slug character varying(50) NOT NULL,
    link character varying(255) NOT NULL,
    image character varying(100) NOT NULL,
    status integer NOT NULL,
    is_display boolean NOT NULL,
    rank_order integer NOT NULL,
    is_delete boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.social_mgmt OWNER TO ml_pg_user;

--
-- TOC entry 4252 (class 0 OID 0)
-- Dependencies: 268
-- Name: COLUMN social_mgmt.name; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.social_mgmt.name IS 'Social name';


--
-- TOC entry 4253 (class 0 OID 0)
-- Dependencies: 268
-- Name: COLUMN social_mgmt.slug; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.social_mgmt.slug IS 'Social slug';


--
-- TOC entry 4254 (class 0 OID 0)
-- Dependencies: 268
-- Name: COLUMN social_mgmt.link; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.social_mgmt.link IS 'Social link';


--
-- TOC entry 4255 (class 0 OID 0)
-- Dependencies: 268
-- Name: COLUMN social_mgmt.image; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.social_mgmt.image IS 'Social image';


--
-- TOC entry 4256 (class 0 OID 0)
-- Dependencies: 268
-- Name: COLUMN social_mgmt.status; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.social_mgmt.status IS 'Social status';


--
-- TOC entry 4257 (class 0 OID 0)
-- Dependencies: 268
-- Name: COLUMN social_mgmt.is_display; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.social_mgmt.is_display IS 'Social is display';


--
-- TOC entry 4258 (class 0 OID 0)
-- Dependencies: 268
-- Name: COLUMN social_mgmt.rank_order; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.social_mgmt.rank_order IS 'Social rank order';


--
-- TOC entry 4259 (class 0 OID 0)
-- Dependencies: 268
-- Name: COLUMN social_mgmt.is_delete; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.social_mgmt.is_delete IS 'is deleted';


--
-- TOC entry 298 (class 1259 OID 16949)
-- Name: social_mgmt_hist; Type: TABLE; Schema: public; Owner: ml_pg_user
--

CREATE TABLE public.social_mgmt_hist (
    id integer NOT NULL,
    social_mgmt_id integer NOT NULL,
    name character varying(50),
    slug character varying(50),
    link character varying(255),
    image character varying(100),
    status integer,
    is_display boolean DEFAULT false,
    rank_order integer DEFAULT 0,
    action integer NOT NULL,
    author_id integer NOT NULL,
    created_at timestamp(0) without time zone NOT NULL
);


ALTER TABLE public.social_mgmt_hist OWNER TO ml_pg_user;

--
-- TOC entry 4260 (class 0 OID 0)
-- Dependencies: 298
-- Name: COLUMN social_mgmt_hist.id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.social_mgmt_hist.id IS 'Social history id';


--
-- TOC entry 4261 (class 0 OID 0)
-- Dependencies: 298
-- Name: COLUMN social_mgmt_hist.social_mgmt_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.social_mgmt_hist.social_mgmt_id IS 'Social management id';


--
-- TOC entry 4262 (class 0 OID 0)
-- Dependencies: 298
-- Name: COLUMN social_mgmt_hist.name; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.social_mgmt_hist.name IS 'name';


--
-- TOC entry 4263 (class 0 OID 0)
-- Dependencies: 298
-- Name: COLUMN social_mgmt_hist.slug; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.social_mgmt_hist.slug IS 'slug';


--
-- TOC entry 4264 (class 0 OID 0)
-- Dependencies: 298
-- Name: COLUMN social_mgmt_hist.link; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.social_mgmt_hist.link IS 'link';


--
-- TOC entry 4265 (class 0 OID 0)
-- Dependencies: 298
-- Name: COLUMN social_mgmt_hist.image; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.social_mgmt_hist.image IS 'image name';


--
-- TOC entry 4266 (class 0 OID 0)
-- Dependencies: 298
-- Name: COLUMN social_mgmt_hist.status; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.social_mgmt_hist.status IS 'status';


--
-- TOC entry 4267 (class 0 OID 0)
-- Dependencies: 298
-- Name: COLUMN social_mgmt_hist.is_display; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.social_mgmt_hist.is_display IS 'display status';


--
-- TOC entry 4268 (class 0 OID 0)
-- Dependencies: 298
-- Name: COLUMN social_mgmt_hist.rank_order; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.social_mgmt_hist.rank_order IS 'rank order';


--
-- TOC entry 4269 (class 0 OID 0)
-- Dependencies: 298
-- Name: COLUMN social_mgmt_hist.action; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.social_mgmt_hist.action IS 'action';


--
-- TOC entry 4270 (class 0 OID 0)
-- Dependencies: 298
-- Name: COLUMN social_mgmt_hist.author_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.social_mgmt_hist.author_id IS 'Author id';


--
-- TOC entry 4271 (class 0 OID 0)
-- Dependencies: 298
-- Name: COLUMN social_mgmt_hist.created_at; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.social_mgmt_hist.created_at IS 'Created time';


--
-- TOC entry 297 (class 1259 OID 16948)
-- Name: social_mgmt_hist_id_seq; Type: SEQUENCE; Schema: public; Owner: ml_pg_user
--

CREATE SEQUENCE public.social_mgmt_hist_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.social_mgmt_hist_id_seq OWNER TO ml_pg_user;

--
-- TOC entry 4272 (class 0 OID 0)
-- Dependencies: 297
-- Name: social_mgmt_hist_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ml_pg_user
--

ALTER SEQUENCE public.social_mgmt_hist_id_seq OWNED BY public.social_mgmt_hist.id;


--
-- TOC entry 267 (class 1259 OID 16761)
-- Name: social_mgmt_id_seq; Type: SEQUENCE; Schema: public; Owner: ml_pg_user
--

CREATE SEQUENCE public.social_mgmt_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.social_mgmt_id_seq OWNER TO ml_pg_user;

--
-- TOC entry 4273 (class 0 OID 0)
-- Dependencies: 267
-- Name: social_mgmt_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ml_pg_user
--

ALTER SEQUENCE public.social_mgmt_id_seq OWNED BY public.social_mgmt.id;


--
-- TOC entry 249 (class 1259 OID 16619)
-- Name: translation_language_mst; Type: TABLE; Schema: public; Owner: ml_pg_user
--

CREATE TABLE public.translation_language_mst (
    translation_mst_id integer NOT NULL,
    language_mst_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.translation_language_mst OWNER TO ml_pg_user;

--
-- TOC entry 4274 (class 0 OID 0)
-- Dependencies: 249
-- Name: COLUMN translation_language_mst.translation_mst_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.translation_language_mst.translation_mst_id IS 'Translation ID';


--
-- TOC entry 4275 (class 0 OID 0)
-- Dependencies: 249
-- Name: COLUMN translation_language_mst.language_mst_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.translation_language_mst.language_mst_id IS 'Language ID';


--
-- TOC entry 246 (class 1259 OID 16602)
-- Name: translation_mst; Type: TABLE; Schema: public; Owner: ml_pg_user
--

CREATE TABLE public.translation_mst (
    id integer NOT NULL,
    language_id integer NOT NULL,
    original_id integer NOT NULL,
    value character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.translation_mst OWNER TO ml_pg_user;

--
-- TOC entry 4276 (class 0 OID 0)
-- Dependencies: 246
-- Name: COLUMN translation_mst.id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.translation_mst.id IS 'Translation ID';


--
-- TOC entry 4277 (class 0 OID 0)
-- Dependencies: 246
-- Name: COLUMN translation_mst.language_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.translation_mst.language_id IS 'Language id';


--
-- TOC entry 4278 (class 0 OID 0)
-- Dependencies: 246
-- Name: COLUMN translation_mst.original_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.translation_mst.original_id IS 'Original translator id';


--
-- TOC entry 4279 (class 0 OID 0)
-- Dependencies: 246
-- Name: COLUMN translation_mst.value; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.translation_mst.value IS 'value';


--
-- TOC entry 4280 (class 0 OID 0)
-- Dependencies: 246
-- Name: COLUMN translation_mst.created_at; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.translation_mst.created_at IS 'Created time';


--
-- TOC entry 4281 (class 0 OID 0)
-- Dependencies: 246
-- Name: COLUMN translation_mst.updated_at; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.translation_mst.updated_at IS 'Updated time';


--
-- TOC entry 286 (class 1259 OID 16871)
-- Name: translation_mst_hist; Type: TABLE; Schema: public; Owner: ml_pg_user
--

CREATE TABLE public.translation_mst_hist (
    id integer NOT NULL,
    translation_mst_id integer NOT NULL,
    language_id integer,
    original_id integer,
    value character varying(255),
    action integer NOT NULL,
    author_id integer NOT NULL,
    created_at character varying(255) NOT NULL
);


ALTER TABLE public.translation_mst_hist OWNER TO ml_pg_user;

--
-- TOC entry 4282 (class 0 OID 0)
-- Dependencies: 286
-- Name: COLUMN translation_mst_hist.translation_mst_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.translation_mst_hist.translation_mst_id IS 'Translation id';


--
-- TOC entry 4283 (class 0 OID 0)
-- Dependencies: 286
-- Name: COLUMN translation_mst_hist.language_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.translation_mst_hist.language_id IS 'Language id';


--
-- TOC entry 4284 (class 0 OID 0)
-- Dependencies: 286
-- Name: COLUMN translation_mst_hist.original_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.translation_mst_hist.original_id IS 'Original id';


--
-- TOC entry 4285 (class 0 OID 0)
-- Dependencies: 286
-- Name: COLUMN translation_mst_hist.value; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.translation_mst_hist.value IS 'value';


--
-- TOC entry 4286 (class 0 OID 0)
-- Dependencies: 286
-- Name: COLUMN translation_mst_hist.action; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.translation_mst_hist.action IS 'Action';


--
-- TOC entry 4287 (class 0 OID 0)
-- Dependencies: 286
-- Name: COLUMN translation_mst_hist.author_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.translation_mst_hist.author_id IS 'Author id';


--
-- TOC entry 285 (class 1259 OID 16870)
-- Name: translation_mst_hist_id_seq; Type: SEQUENCE; Schema: public; Owner: ml_pg_user
--

CREATE SEQUENCE public.translation_mst_hist_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.translation_mst_hist_id_seq OWNER TO ml_pg_user;

--
-- TOC entry 4288 (class 0 OID 0)
-- Dependencies: 285
-- Name: translation_mst_hist_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ml_pg_user
--

ALTER SEQUENCE public.translation_mst_hist_id_seq OWNED BY public.translation_mst_hist.id;


--
-- TOC entry 245 (class 1259 OID 16601)
-- Name: translation_mst_id_seq; Type: SEQUENCE; Schema: public; Owner: ml_pg_user
--

CREATE SEQUENCE public.translation_mst_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.translation_mst_id_seq OWNER TO ml_pg_user;

--
-- TOC entry 4289 (class 0 OID 0)
-- Dependencies: 245
-- Name: translation_mst_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ml_pg_user
--

ALTER SEQUENCE public.translation_mst_id_seq OWNED BY public.translation_mst.id;


--
-- TOC entry 266 (class 1259 OID 16751)
-- Name: user_mgmt; Type: TABLE; Schema: public; Owner: ml_pg_user
--

CREATE TABLE public.user_mgmt (
    id integer NOT NULL,
    email character varying(30) NOT NULL,
    user_name character varying(50) NOT NULL,
    password character varying(100) NOT NULL,
    first_name character varying(20) NOT NULL,
    last_name character varying(20) NOT NULL,
    address character varying(100),
    phone_number character varying(20),
    birth character varying(255),
    gender integer,
    status integer,
    is_active boolean DEFAULT false NOT NULL,
    avatar character varying(30),
    email_verified_at character varying(255),
    remember_token character varying(100),
    is_delete boolean DEFAULT false NOT NULL,
    created_at character varying(255),
    updated_at character varying(255)
);


ALTER TABLE public.user_mgmt OWNER TO ml_pg_user;

--
-- TOC entry 4290 (class 0 OID 0)
-- Dependencies: 266
-- Name: COLUMN user_mgmt.id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.user_mgmt.id IS 'User ID';


--
-- TOC entry 4291 (class 0 OID 0)
-- Dependencies: 266
-- Name: COLUMN user_mgmt.email; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.user_mgmt.email IS 'User email';


--
-- TOC entry 4292 (class 0 OID 0)
-- Dependencies: 266
-- Name: COLUMN user_mgmt.user_name; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.user_mgmt.user_name IS 'User name';


--
-- TOC entry 4293 (class 0 OID 0)
-- Dependencies: 266
-- Name: COLUMN user_mgmt.password; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.user_mgmt.password IS 'User password';


--
-- TOC entry 4294 (class 0 OID 0)
-- Dependencies: 266
-- Name: COLUMN user_mgmt.first_name; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.user_mgmt.first_name IS 'User first name';


--
-- TOC entry 4295 (class 0 OID 0)
-- Dependencies: 266
-- Name: COLUMN user_mgmt.last_name; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.user_mgmt.last_name IS 'User last name';


--
-- TOC entry 4296 (class 0 OID 0)
-- Dependencies: 266
-- Name: COLUMN user_mgmt.address; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.user_mgmt.address IS 'User address';


--
-- TOC entry 4297 (class 0 OID 0)
-- Dependencies: 266
-- Name: COLUMN user_mgmt.phone_number; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.user_mgmt.phone_number IS 'User phone number';


--
-- TOC entry 4298 (class 0 OID 0)
-- Dependencies: 266
-- Name: COLUMN user_mgmt.birth; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.user_mgmt.birth IS 'User birthday';


--
-- TOC entry 4299 (class 0 OID 0)
-- Dependencies: 266
-- Name: COLUMN user_mgmt.gender; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.user_mgmt.gender IS 'User gender';


--
-- TOC entry 4300 (class 0 OID 0)
-- Dependencies: 266
-- Name: COLUMN user_mgmt.status; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.user_mgmt.status IS 'User status';


--
-- TOC entry 4301 (class 0 OID 0)
-- Dependencies: 266
-- Name: COLUMN user_mgmt.is_active; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.user_mgmt.is_active IS 'User active status';


--
-- TOC entry 4302 (class 0 OID 0)
-- Dependencies: 266
-- Name: COLUMN user_mgmt.avatar; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.user_mgmt.avatar IS 'User avatar';


--
-- TOC entry 4303 (class 0 OID 0)
-- Dependencies: 266
-- Name: COLUMN user_mgmt.email_verified_at; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.user_mgmt.email_verified_at IS 'User email verified at';


--
-- TOC entry 4304 (class 0 OID 0)
-- Dependencies: 266
-- Name: COLUMN user_mgmt.remember_token; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.user_mgmt.remember_token IS 'Remember token';


--
-- TOC entry 4305 (class 0 OID 0)
-- Dependencies: 266
-- Name: COLUMN user_mgmt.is_delete; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.user_mgmt.is_delete IS 'is deleted';


--
-- TOC entry 4306 (class 0 OID 0)
-- Dependencies: 266
-- Name: COLUMN user_mgmt.created_at; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.user_mgmt.created_at IS 'User created at';


--
-- TOC entry 4307 (class 0 OID 0)
-- Dependencies: 266
-- Name: COLUMN user_mgmt.updated_at; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.user_mgmt.updated_at IS 'User updated status';


--
-- TOC entry 302 (class 1259 OID 16975)
-- Name: user_mgmt_hist; Type: TABLE; Schema: public; Owner: ml_pg_user
--

CREATE TABLE public.user_mgmt_hist (
    id integer NOT NULL,
    user_mgmt_id integer NOT NULL,
    email character varying(30),
    user_name character varying(50),
    password character varying(100),
    first_name character varying(20),
    last_name character varying(20),
    address character varying(50),
    phone_number character varying(20),
    birth timestamp(0) without time zone,
    gender smallint,
    status smallint,
    is_active boolean,
    avatar character varying(30),
    email_verified_at timestamp(0) without time zone,
    remember_token character varying(100),
    action smallint NOT NULL,
    author_id integer NOT NULL,
    created_at timestamp(0) without time zone NOT NULL
);


ALTER TABLE public.user_mgmt_hist OWNER TO ml_pg_user;

--
-- TOC entry 4308 (class 0 OID 0)
-- Dependencies: 302
-- Name: COLUMN user_mgmt_hist.id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.user_mgmt_hist.id IS 'User mgmt hist id';


--
-- TOC entry 4309 (class 0 OID 0)
-- Dependencies: 302
-- Name: COLUMN user_mgmt_hist.user_mgmt_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.user_mgmt_hist.user_mgmt_id IS 'User management id';


--
-- TOC entry 4310 (class 0 OID 0)
-- Dependencies: 302
-- Name: COLUMN user_mgmt_hist.email; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.user_mgmt_hist.email IS 'email';


--
-- TOC entry 4311 (class 0 OID 0)
-- Dependencies: 302
-- Name: COLUMN user_mgmt_hist.user_name; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.user_mgmt_hist.user_name IS 'name';


--
-- TOC entry 4312 (class 0 OID 0)
-- Dependencies: 302
-- Name: COLUMN user_mgmt_hist.password; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.user_mgmt_hist.password IS 'password';


--
-- TOC entry 4313 (class 0 OID 0)
-- Dependencies: 302
-- Name: COLUMN user_mgmt_hist.first_name; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.user_mgmt_hist.first_name IS 'First name';


--
-- TOC entry 4314 (class 0 OID 0)
-- Dependencies: 302
-- Name: COLUMN user_mgmt_hist.last_name; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.user_mgmt_hist.last_name IS 'Last name';


--
-- TOC entry 4315 (class 0 OID 0)
-- Dependencies: 302
-- Name: COLUMN user_mgmt_hist.address; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.user_mgmt_hist.address IS 'address';


--
-- TOC entry 4316 (class 0 OID 0)
-- Dependencies: 302
-- Name: COLUMN user_mgmt_hist.phone_number; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.user_mgmt_hist.phone_number IS 'phone number';


--
-- TOC entry 4317 (class 0 OID 0)
-- Dependencies: 302
-- Name: COLUMN user_mgmt_hist.birth; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.user_mgmt_hist.birth IS 'birth';


--
-- TOC entry 4318 (class 0 OID 0)
-- Dependencies: 302
-- Name: COLUMN user_mgmt_hist.gender; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.user_mgmt_hist.gender IS 'gender';


--
-- TOC entry 4319 (class 0 OID 0)
-- Dependencies: 302
-- Name: COLUMN user_mgmt_hist.status; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.user_mgmt_hist.status IS 'status';


--
-- TOC entry 4320 (class 0 OID 0)
-- Dependencies: 302
-- Name: COLUMN user_mgmt_hist.is_active; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.user_mgmt_hist.is_active IS 'active';


--
-- TOC entry 4321 (class 0 OID 0)
-- Dependencies: 302
-- Name: COLUMN user_mgmt_hist.avatar; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.user_mgmt_hist.avatar IS 'avatar name';


--
-- TOC entry 4322 (class 0 OID 0)
-- Dependencies: 302
-- Name: COLUMN user_mgmt_hist.email_verified_at; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.user_mgmt_hist.email_verified_at IS 'Verified email time';


--
-- TOC entry 4323 (class 0 OID 0)
-- Dependencies: 302
-- Name: COLUMN user_mgmt_hist.remember_token; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.user_mgmt_hist.remember_token IS 'Remember token';


--
-- TOC entry 4324 (class 0 OID 0)
-- Dependencies: 302
-- Name: COLUMN user_mgmt_hist.action; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.user_mgmt_hist.action IS 'action';


--
-- TOC entry 4325 (class 0 OID 0)
-- Dependencies: 302
-- Name: COLUMN user_mgmt_hist.author_id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.user_mgmt_hist.author_id IS 'Author id';


--
-- TOC entry 4326 (class 0 OID 0)
-- Dependencies: 302
-- Name: COLUMN user_mgmt_hist.created_at; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.user_mgmt_hist.created_at IS 'Created time';


--
-- TOC entry 301 (class 1259 OID 16974)
-- Name: user_mgmt_hist_id_seq; Type: SEQUENCE; Schema: public; Owner: ml_pg_user
--

CREATE SEQUENCE public.user_mgmt_hist_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.user_mgmt_hist_id_seq OWNER TO ml_pg_user;

--
-- TOC entry 4327 (class 0 OID 0)
-- Dependencies: 301
-- Name: user_mgmt_hist_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ml_pg_user
--

ALTER SEQUENCE public.user_mgmt_hist_id_seq OWNED BY public.user_mgmt_hist.id;


--
-- TOC entry 265 (class 1259 OID 16750)
-- Name: user_mgmt_id_seq; Type: SEQUENCE; Schema: public; Owner: ml_pg_user
--

CREATE SEQUENCE public.user_mgmt_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.user_mgmt_id_seq OWNER TO ml_pg_user;

--
-- TOC entry 4328 (class 0 OID 0)
-- Dependencies: 265
-- Name: user_mgmt_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ml_pg_user
--

ALTER SEQUENCE public.user_mgmt_id_seq OWNED BY public.user_mgmt.id;


--
-- TOC entry 251 (class 1259 OID 16635)
-- Name: user_mst; Type: TABLE; Schema: public; Owner: ml_pg_user
--

CREATE TABLE public.user_mst (
    id integer NOT NULL,
    email character varying(30) NOT NULL,
    user_name character varying(50) NOT NULL,
    password character varying(100) NOT NULL,
    first_name character varying(20) NOT NULL,
    last_name character varying(20) NOT NULL,
    address character varying(50),
    phone_number character varying(20),
    birth timestamp(0) without time zone,
    gender smallint DEFAULT '0'::smallint NOT NULL,
    status smallint DEFAULT '0'::smallint NOT NULL,
    is_active boolean DEFAULT false NOT NULL,
    avatar character varying(30),
    email_verified_at timestamp(0) without time zone,
    is_delete boolean DEFAULT false NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.user_mst OWNER TO ml_pg_user;

--
-- TOC entry 4329 (class 0 OID 0)
-- Dependencies: 251
-- Name: COLUMN user_mst.id; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.user_mst.id IS 'User ID';


--
-- TOC entry 4330 (class 0 OID 0)
-- Dependencies: 251
-- Name: COLUMN user_mst.email; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.user_mst.email IS 'User email';


--
-- TOC entry 4331 (class 0 OID 0)
-- Dependencies: 251
-- Name: COLUMN user_mst.user_name; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.user_mst.user_name IS 'User name';


--
-- TOC entry 4332 (class 0 OID 0)
-- Dependencies: 251
-- Name: COLUMN user_mst.password; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.user_mst.password IS 'User password';


--
-- TOC entry 4333 (class 0 OID 0)
-- Dependencies: 251
-- Name: COLUMN user_mst.first_name; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.user_mst.first_name IS 'First name';


--
-- TOC entry 4334 (class 0 OID 0)
-- Dependencies: 251
-- Name: COLUMN user_mst.last_name; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.user_mst.last_name IS 'Last name';


--
-- TOC entry 4335 (class 0 OID 0)
-- Dependencies: 251
-- Name: COLUMN user_mst.address; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.user_mst.address IS 'User address';


--
-- TOC entry 4336 (class 0 OID 0)
-- Dependencies: 251
-- Name: COLUMN user_mst.phone_number; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.user_mst.phone_number IS 'User phone number';


--
-- TOC entry 4337 (class 0 OID 0)
-- Dependencies: 251
-- Name: COLUMN user_mst.birth; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.user_mst.birth IS 'User birth';


--
-- TOC entry 4338 (class 0 OID 0)
-- Dependencies: 251
-- Name: COLUMN user_mst.gender; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.user_mst.gender IS 'User gender';


--
-- TOC entry 4339 (class 0 OID 0)
-- Dependencies: 251
-- Name: COLUMN user_mst.status; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.user_mst.status IS 'User status';


--
-- TOC entry 4340 (class 0 OID 0)
-- Dependencies: 251
-- Name: COLUMN user_mst.is_active; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.user_mst.is_active IS 'User active';


--
-- TOC entry 4341 (class 0 OID 0)
-- Dependencies: 251
-- Name: COLUMN user_mst.avatar; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.user_mst.avatar IS 'User avatar name';


--
-- TOC entry 4342 (class 0 OID 0)
-- Dependencies: 251
-- Name: COLUMN user_mst.email_verified_at; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.user_mst.email_verified_at IS 'Verified email time';


--
-- TOC entry 4343 (class 0 OID 0)
-- Dependencies: 251
-- Name: COLUMN user_mst.is_delete; Type: COMMENT; Schema: public; Owner: ml_pg_user
--

COMMENT ON COLUMN public.user_mst.is_delete IS 'is deleted';


--
-- TOC entry 250 (class 1259 OID 16634)
-- Name: user_mst_id_seq; Type: SEQUENCE; Schema: public; Owner: ml_pg_user
--

CREATE SEQUENCE public.user_mst_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.user_mst_id_seq OWNER TO ml_pg_user;

--
-- TOC entry 4344 (class 0 OID 0)
-- Dependencies: 250
-- Name: user_mst_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ml_pg_user
--

ALTER SEQUENCE public.user_mst_id_seq OWNED BY public.user_mst.id;


--
-- TOC entry 217 (class 1259 OID 16397)
-- Name: users; Type: TABLE; Schema: public; Owner: ml_pg_user
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


ALTER TABLE public.users OWNER TO ml_pg_user;

--
-- TOC entry 216 (class 1259 OID 16396)
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: ml_pg_user
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.users_id_seq OWNER TO ml_pg_user;

--
-- TOC entry 4345 (class 0 OID 0)
-- Dependencies: 216
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: ml_pg_user
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- TOC entry 3509 (class 2604 OID 16470)
-- Name: admin_mst id; Type: DEFAULT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.admin_mst ALTER COLUMN id SET DEFAULT nextval('public.admin_mst_id_seq'::regclass);


--
-- TOC entry 3571 (class 2604 OID 16773)
-- Name: admin_mst_hist id; Type: DEFAULT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.admin_mst_hist ALTER COLUMN id SET DEFAULT nextval('public.admin_mst_hist_id_seq'::regclass);


--
-- TOC entry 3520 (class 2604 OID 16520)
-- Name: api_mst id; Type: DEFAULT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.api_mst ALTER COLUMN id SET DEFAULT nextval('public.api_mst_id_seq'::regclass);


--
-- TOC entry 3576 (class 2604 OID 16833)
-- Name: api_mst_hist id; Type: DEFAULT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.api_mst_hist ALTER COLUMN id SET DEFAULT nextval('public.api_mst_hist_id_seq'::regclass);


--
-- TOC entry 3561 (class 2604 OID 16735)
-- Name: banner_mgmt id; Type: DEFAULT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.banner_mgmt ALTER COLUMN id SET DEFAULT nextval('public.banner_mgmt_id_seq'::regclass);


--
-- TOC entry 3584 (class 2604 OID 16938)
-- Name: banner_mgmt_hist id; Type: DEFAULT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.banner_mgmt_hist ALTER COLUMN id SET DEFAULT nextval('public.banner_mgmt_hist_id_seq'::regclass);


--
-- TOC entry 3540 (class 2604 OID 16653)
-- Name: category_mgmt id; Type: DEFAULT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.category_mgmt ALTER COLUMN id SET DEFAULT nextval('public.category_mgmt_id_seq'::regclass);


--
-- TOC entry 3580 (class 2604 OID 16888)
-- Name: category_mgmt_hist id; Type: DEFAULT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.category_mgmt_hist ALTER COLUMN id SET DEFAULT nextval('public.category_mgmt_hist_id_seq'::regclass);


--
-- TOC entry 3524 (class 2604 OID 16550)
-- Name: department_mst id; Type: DEFAULT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.department_mst ALTER COLUMN id SET DEFAULT nextval('public.department_mst_id_seq'::regclass);


--
-- TOC entry 3572 (class 2604 OID 16785)
-- Name: department_mst_hist id; Type: DEFAULT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.department_mst_hist ALTER COLUMN id SET DEFAULT nextval('public.department_mst_hist_id_seq'::regclass);


--
-- TOC entry 3507 (class 2604 OID 16458)
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- TOC entry 3517 (class 2604 OID 16511)
-- Name: feature_mst id; Type: DEFAULT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.feature_mst ALTER COLUMN id SET DEFAULT nextval('public.feature_mst_id_seq'::regclass);


--
-- TOC entry 3575 (class 2604 OID 16821)
-- Name: feature_mst_hist id; Type: DEFAULT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.feature_mst_hist ALTER COLUMN id SET DEFAULT nextval('public.feature_mst_hist_id_seq'::regclass);


--
-- TOC entry 3506 (class 2604 OID 16441)
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- TOC entry 3532 (class 2604 OID 16612)
-- Name: language_mst id; Type: DEFAULT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.language_mst ALTER COLUMN id SET DEFAULT nextval('public.language_mst_id_seq'::regclass);


--
-- TOC entry 3578 (class 2604 OID 16862)
-- Name: language_mst_hist id; Type: DEFAULT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.language_mst_hist ALTER COLUMN id SET DEFAULT nextval('public.language_mst_hist_id_seq'::regclass);


--
-- TOC entry 3504 (class 2604 OID 16393)
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- TOC entry 3529 (class 2604 OID 16597)
-- Name: original_translator_mst id; Type: DEFAULT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.original_translator_mst ALTER COLUMN id SET DEFAULT nextval('public.original_translator_mst_id_seq'::regclass);


--
-- TOC entry 3577 (class 2604 OID 16850)
-- Name: original_translator_mst_hist id; Type: DEFAULT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.original_translator_mst_hist ALTER COLUMN id SET DEFAULT nextval('public.original_translator_mst_hist_id_seq'::regclass);


--
-- TOC entry 3527 (class 2604 OID 16574)
-- Name: policy_department_mst id; Type: DEFAULT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.policy_department_mst ALTER COLUMN id SET DEFAULT nextval('public.policy_department_mst_id_seq'::regclass);


--
-- TOC entry 3573 (class 2604 OID 16797)
-- Name: policy_department_mst_hist id; Type: DEFAULT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.policy_department_mst_hist ALTER COLUMN id SET DEFAULT nextval('public.policy_department_mst_hist_id_seq'::regclass);


--
-- TOC entry 3514 (class 2604 OID 16485)
-- Name: role_mst id; Type: DEFAULT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.role_mst ALTER COLUMN id SET DEFAULT nextval('public.role_mst_id_seq'::regclass);


--
-- TOC entry 3574 (class 2604 OID 16809)
-- Name: role_mst_hist id; Type: DEFAULT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.role_mst_hist ALTER COLUMN id SET DEFAULT nextval('public.role_mst_hist_id_seq'::regclass);


--
-- TOC entry 3564 (class 2604 OID 16746)
-- Name: setting_link_mgmt id; Type: DEFAULT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.setting_link_mgmt ALTER COLUMN id SET DEFAULT nextval('public.setting_link_mgmt_id_seq'::regclass);


--
-- TOC entry 3588 (class 2604 OID 16966)
-- Name: setting_link_mgmt_hist id; Type: DEFAULT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.setting_link_mgmt_hist ALTER COLUMN id SET DEFAULT nextval('public.setting_link_mgmt_hist_id_seq'::regclass);


--
-- TOC entry 3552 (class 2604 OID 16702)
-- Name: skill_description_mgmt id; Type: DEFAULT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.skill_description_mgmt ALTER COLUMN id SET DEFAULT nextval('public.skill_description_mgmt_id_seq'::regclass);


--
-- TOC entry 3582 (class 2604 OID 16912)
-- Name: skill_description_mgmt_hist id; Type: DEFAULT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.skill_description_mgmt_hist ALTER COLUMN id SET DEFAULT nextval('public.skill_description_mgmt_hist_id_seq'::regclass);


--
-- TOC entry 3546 (class 2604 OID 16670)
-- Name: skill_mgmt id; Type: DEFAULT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.skill_mgmt ALTER COLUMN id SET DEFAULT nextval('public.skill_mgmt_id_seq'::regclass);


--
-- TOC entry 3581 (class 2604 OID 16900)
-- Name: skill_mgmt_hist id; Type: DEFAULT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.skill_mgmt_hist ALTER COLUMN id SET DEFAULT nextval('public.skill_mgmt_hist_id_seq'::regclass);


--
-- TOC entry 3558 (class 2604 OID 16726)
-- Name: slider_mgmt id; Type: DEFAULT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.slider_mgmt ALTER COLUMN id SET DEFAULT nextval('public.slider_mgmt_id_seq'::regclass);


--
-- TOC entry 3583 (class 2604 OID 16926)
-- Name: slider_mgmt_hist id; Type: DEFAULT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.slider_mgmt_hist ALTER COLUMN id SET DEFAULT nextval('public.slider_mgmt_hist_id_seq'::regclass);


--
-- TOC entry 3569 (class 2604 OID 16765)
-- Name: social_mgmt id; Type: DEFAULT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.social_mgmt ALTER COLUMN id SET DEFAULT nextval('public.social_mgmt_id_seq'::regclass);


--
-- TOC entry 3585 (class 2604 OID 16952)
-- Name: social_mgmt_hist id; Type: DEFAULT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.social_mgmt_hist ALTER COLUMN id SET DEFAULT nextval('public.social_mgmt_hist_id_seq'::regclass);


--
-- TOC entry 3531 (class 2604 OID 16605)
-- Name: translation_mst id; Type: DEFAULT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.translation_mst ALTER COLUMN id SET DEFAULT nextval('public.translation_mst_id_seq'::regclass);


--
-- TOC entry 3579 (class 2604 OID 16874)
-- Name: translation_mst_hist id; Type: DEFAULT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.translation_mst_hist ALTER COLUMN id SET DEFAULT nextval('public.translation_mst_hist_id_seq'::regclass);


--
-- TOC entry 3566 (class 2604 OID 16754)
-- Name: user_mgmt id; Type: DEFAULT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.user_mgmt ALTER COLUMN id SET DEFAULT nextval('public.user_mgmt_id_seq'::regclass);


--
-- TOC entry 3589 (class 2604 OID 16978)
-- Name: user_mgmt_hist id; Type: DEFAULT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.user_mgmt_hist ALTER COLUMN id SET DEFAULT nextval('public.user_mgmt_hist_id_seq'::regclass);


--
-- TOC entry 3535 (class 2604 OID 16638)
-- Name: user_mst id; Type: DEFAULT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.user_mst ALTER COLUMN id SET DEFAULT nextval('public.user_mst_id_seq'::regclass);


--
-- TOC entry 3505 (class 2604 OID 16400)
-- Name: users id; Type: DEFAULT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- TOC entry 3913 (class 0 OID 16555)
-- Dependencies: 239
-- Data for Name: admin_department_mst; Type: TABLE DATA; Schema: public; Owner: ml_pg_user
--

COPY public.admin_department_mst (admin_mst_id, department_mst_id, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 3902 (class 0 OID 16467)
-- Dependencies: 228
-- Data for Name: admin_mst; Type: TABLE DATA; Schema: public; Owner: ml_pg_user
--

COPY public.admin_mst (id, email, user_name, password, first_name, last_name, address, phone_number, birth, gender, status, is_active, avatar, email_verified_at, is_delete, remember_token, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 3944 (class 0 OID 16770)
-- Dependencies: 270
-- Data for Name: admin_mst_hist; Type: TABLE DATA; Schema: public; Owner: ml_pg_user
--

COPY public.admin_mst_hist (id, admin_mst_id, email, user_name, password, first_name, last_name, address, phone_number, birth, gender, status, is_active, avatar, email_verified_at, remember_token, action, author_id, created_at) FROM stdin;
\.


--
-- TOC entry 3905 (class 0 OID 16492)
-- Dependencies: 231
-- Data for Name: admin_role_mst; Type: TABLE DATA; Schema: public; Owner: ml_pg_user
--

COPY public.admin_role_mst (admin_mst_id, role_mst_id, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 3909 (class 0 OID 16517)
-- Dependencies: 235
-- Data for Name: api_mst; Type: TABLE DATA; Schema: public; Owner: ml_pg_user
--

COPY public.api_mst (id, type, name, path, is_active, feature_mst_id, is_delete, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 3954 (class 0 OID 16830)
-- Dependencies: 280
-- Data for Name: api_mst_hist; Type: TABLE DATA; Schema: public; Owner: ml_pg_user
--

COPY public.api_mst_hist (id, api_mst_id, type, name, path, is_active, feature_mst_id, action, author_id, created_at) FROM stdin;
\.


--
-- TOC entry 3910 (class 0 OID 16531)
-- Dependencies: 236
-- Data for Name: api_role_mst; Type: TABLE DATA; Schema: public; Owner: ml_pg_user
--

COPY public.api_role_mst (api_mst_id, role_mst_id, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 3936 (class 0 OID 16732)
-- Dependencies: 262
-- Data for Name: banner_mgmt; Type: TABLE DATA; Schema: public; Owner: ml_pg_user
--

COPY public.banner_mgmt (id, title, slug, description, link, image, "position", status, is_delete, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 3970 (class 0 OID 16935)
-- Dependencies: 296
-- Data for Name: banner_mgmt_hist; Type: TABLE DATA; Schema: public; Owner: ml_pg_user
--

COPY public.banner_mgmt_hist (id, banner_mgmt_id, title, slug, description, link, image, "position", status, action, author_id, created_at) FROM stdin;
\.


--
-- TOC entry 3894 (class 0 OID 16423)
-- Dependencies: 220
-- Data for Name: cache; Type: TABLE DATA; Schema: public; Owner: ml_pg_user
--

COPY public.cache (key, value, expiration) FROM stdin;
\.


--
-- TOC entry 3895 (class 0 OID 16430)
-- Dependencies: 221
-- Data for Name: cache_locks; Type: TABLE DATA; Schema: public; Owner: ml_pg_user
--

COPY public.cache_locks (key, owner, expiration) FROM stdin;
\.


--
-- TOC entry 3927 (class 0 OID 16650)
-- Dependencies: 253
-- Data for Name: category_mgmt; Type: TABLE DATA; Schema: public; Owner: ml_pg_user
--

COPY public.category_mgmt (id, parent_id, name, slug, description, status, is_display, rank_order, is_delete, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 3962 (class 0 OID 16885)
-- Dependencies: 288
-- Data for Name: category_mgmt_hist; Type: TABLE DATA; Schema: public; Owner: ml_pg_user
--

COPY public.category_mgmt_hist (id, category_mgmt_id, parent_id, name, slug, description, status, is_display, rank_order, action, author_id, created_at) FROM stdin;
\.


--
-- TOC entry 3930 (class 0 OID 16683)
-- Dependencies: 256
-- Data for Name: category_skill_mgmt; Type: TABLE DATA; Schema: public; Owner: ml_pg_user
--

COPY public.category_skill_mgmt (category_mgmt_id, skill_mgmt_id, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 3916 (class 0 OID 16578)
-- Dependencies: 242
-- Data for Name: department_management_mst; Type: TABLE DATA; Schema: public; Owner: ml_pg_user
--

COPY public.department_management_mst (department_mst_id, policy_department_mst_id, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 3912 (class 0 OID 16547)
-- Dependencies: 238
-- Data for Name: department_mst; Type: TABLE DATA; Schema: public; Owner: ml_pg_user
--

COPY public.department_mst (id, code, name, status, is_delete, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 3946 (class 0 OID 16782)
-- Dependencies: 272
-- Data for Name: department_mst_hist; Type: TABLE DATA; Schema: public; Owner: ml_pg_user
--

COPY public.department_mst_hist (id, department_mst_id, code, name, status, action, author_id, created_at) FROM stdin;
\.


--
-- TOC entry 3900 (class 0 OID 16455)
-- Dependencies: 226
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: ml_pg_user
--

COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
\.


--
-- TOC entry 3907 (class 0 OID 16508)
-- Dependencies: 233
-- Data for Name: feature_mst; Type: TABLE DATA; Schema: public; Owner: ml_pg_user
--

COPY public.feature_mst (id, name, group_name, description, status, is_delete, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 3952 (class 0 OID 16818)
-- Dependencies: 278
-- Data for Name: feature_mst_hist; Type: TABLE DATA; Schema: public; Owner: ml_pg_user
--

COPY public.feature_mst_hist (id, feature_mst_id, name, group_name, description, status, action, author_id, created_at) FROM stdin;
\.


--
-- TOC entry 3898 (class 0 OID 16447)
-- Dependencies: 224
-- Data for Name: job_batches; Type: TABLE DATA; Schema: public; Owner: ml_pg_user
--

COPY public.job_batches (id, name, total_jobs, pending_jobs, failed_jobs, failed_job_ids, options, cancelled_at, created_at, finished_at) FROM stdin;
\.


--
-- TOC entry 3897 (class 0 OID 16438)
-- Dependencies: 223
-- Data for Name: jobs; Type: TABLE DATA; Schema: public; Owner: ml_pg_user
--

COPY public.jobs (id, queue, payload, attempts, reserved_at, available_at, created_at) FROM stdin;
\.


--
-- TOC entry 3922 (class 0 OID 16609)
-- Dependencies: 248
-- Data for Name: language_mst; Type: TABLE DATA; Schema: public; Owner: ml_pg_user
--

COPY public.language_mst (id, abbreviation, name, is_active, is_delete, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 3958 (class 0 OID 16859)
-- Dependencies: 284
-- Data for Name: language_mst_hist; Type: TABLE DATA; Schema: public; Owner: ml_pg_user
--

COPY public.language_mst_hist (id, language_mst_id, abbreviation, name, is_active, action, author_id, created_at) FROM stdin;
\.


--
-- TOC entry 3889 (class 0 OID 16390)
-- Dependencies: 215
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: ml_pg_user
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	0001_01_01_000000_create_users_table	1
2	0001_01_01_000001_create_cache_table	1
3	0001_01_01_000002_create_jobs_table	1
4	2024_06_09_023543_create_admin_mst_table	2
5	2024_06_09_023557_create_role_mst_table	2
6	2024_06_09_023611_create_admin_role_mst_table	2
7	2024_06_09_023639_create_feature_mst_table	2
8	2024_06_09_023820_create_api_mst_table	2
9	2024_06_09_023843_create_api_role_mst_table	2
10	2024_06_09_023947_create_department_mst_table	2
11	2024_06_09_024000_create_admin_department_mst_table	2
12	2024_06_09_024031_create_policy_department_mst_table	2
13	2024_06_09_024053_create_department_management_mst_table	2
14	2024_06_09_024131_create_original_translator_mst_table	2
15	2024_06_09_024146_create_translation_mst_table	2
16	2024_06_09_024215_create_language_mst_table	2
17	2024_06_09_024216_create_translation_language_mst_table	2
18	2024_06_09_024226_create_user_mst_table	2
19	2024_06_09_023253_create_category_mgmt_table	3
20	2024_06_09_023335_create_skill_mgmt_table	3
21	2024_06_09_023354_create_category_skill_mgmt_table	3
22	2024_06_09_023425_create_skill_description_mgmt_table	3
23	2024_06_09_023447_create_slider_mgmt_table	3
24	2024_06_09_023455_create_banner_mgmt_table	3
25	2024_06_09_023521_create_setting_link_mgmt_table	3
26	2024_09_24_000000_create_user_mgmt_table	3
27	2025_09_23_000001_create_social_mgmt_table	3
28	2024_06_09_024447_create_admin_mst_hist_table	4
29	2024_06_09_024510_create_department_mst_hist_table	4
30	2024_06_09_024530_create_policy_department_mst_hist_table	4
31	2024_06_09_024539_create_role_mst_hist_table	4
32	2024_06_09_024557_create_feature_mst_hist_table	4
33	2024_06_09_024608_create_api_mst_hist_table	4
34	2024_06_09_024636_create_original_translator_mst_hist_table	4
35	2024_06_09_024823_create_language_mst_hist_table	4
36	2024_09_24_000000_create_translation_mst_hist_table	4
37	2024_06_09_024258_create_category_mgmt_hist_table	5
38	2024_06_09_024306_create_skill_mgmt_hist_table	5
39	2024_06_09_024320_create_skill_description_mgmt_hist_table	5
40	2024_06_09_024343_create_slider_mgmt_hist_table	5
41	2024_06_09_024352_create_banner_mgmt_hist_table	5
42	2024_06_09_024408_create_social_mgmt_hist_table	5
43	2024_06_09_024422_create_setting_link_mgmt_hist_table	5
44	2024_06_09_024830_create_user_mgmt_hist_table	5
45	2024_08_05_041318_create_after_api_insert	6
46	2024_08_05_041452_create_after_policy_department_insert	6
47	2024_08_02_100908_create_admin_permission_view	7
48	2024_08_02_102814_create_admin_policy_view	7
\.


--
-- TOC entry 3918 (class 0 OID 16594)
-- Dependencies: 244
-- Data for Name: original_translator_mst; Type: TABLE DATA; Schema: public; Owner: ml_pg_user
--

COPY public.original_translator_mst (id, "table", "column", field_id, is_delete, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 3956 (class 0 OID 16847)
-- Dependencies: 282
-- Data for Name: original_translator_mst_hist; Type: TABLE DATA; Schema: public; Owner: ml_pg_user
--

COPY public.original_translator_mst_hist (id, original_translator_mst_id, "table", "column", field_id, action, author_id, created_at) FROM stdin;
\.


--
-- TOC entry 3892 (class 0 OID 16407)
-- Dependencies: 218
-- Data for Name: password_reset_tokens; Type: TABLE DATA; Schema: public; Owner: ml_pg_user
--

COPY public.password_reset_tokens (email, token, created_at) FROM stdin;
\.


--
-- TOC entry 3915 (class 0 OID 16571)
-- Dependencies: 241
-- Data for Name: policy_department_mst; Type: TABLE DATA; Schema: public; Owner: ml_pg_user
--

COPY public.policy_department_mst (id, table_name, row_id, is_delete, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 3948 (class 0 OID 16794)
-- Dependencies: 274
-- Data for Name: policy_department_mst_hist; Type: TABLE DATA; Schema: public; Owner: ml_pg_user
--

COPY public.policy_department_mst_hist (id, policy_department_mst_id, table_name, row_id, action, author_id, created_at) FROM stdin;
\.


--
-- TOC entry 3904 (class 0 OID 16482)
-- Dependencies: 230
-- Data for Name: role_mst; Type: TABLE DATA; Schema: public; Owner: ml_pg_user
--

COPY public.role_mst (id, name, permission, is_active, is_delete, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 3950 (class 0 OID 16806)
-- Dependencies: 276
-- Data for Name: role_mst_hist; Type: TABLE DATA; Schema: public; Owner: ml_pg_user
--

COPY public.role_mst_hist (id, role_mst_id, name, permission, is_active, action, author_id, created_at) FROM stdin;
\.


--
-- TOC entry 3893 (class 0 OID 16414)
-- Dependencies: 219
-- Data for Name: sessions; Type: TABLE DATA; Schema: public; Owner: ml_pg_user
--

COPY public.sessions (id, user_id, ip_address, user_agent, payload, last_activity) FROM stdin;
\.


--
-- TOC entry 3938 (class 0 OID 16743)
-- Dependencies: 264
-- Data for Name: setting_link_mgmt; Type: TABLE DATA; Schema: public; Owner: ml_pg_user
--

COPY public.setting_link_mgmt (id, key, value, is_delete, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 3974 (class 0 OID 16963)
-- Dependencies: 300
-- Data for Name: setting_link_mgmt_hist; Type: TABLE DATA; Schema: public; Owner: ml_pg_user
--

COPY public.setting_link_mgmt_hist (id, setting_link_id, key, value, action, author_id, created_at) FROM stdin;
\.


--
-- TOC entry 3932 (class 0 OID 16699)
-- Dependencies: 258
-- Data for Name: skill_description_mgmt; Type: TABLE DATA; Schema: public; Owner: ml_pg_user
--

COPY public.skill_description_mgmt (id, parent_id, title, summary, article, status, is_display, rank_order, skill_mgmt_id, is_delete, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 3966 (class 0 OID 16909)
-- Dependencies: 292
-- Data for Name: skill_description_mgmt_hist; Type: TABLE DATA; Schema: public; Owner: ml_pg_user
--

COPY public.skill_description_mgmt_hist (id, skill_description_mgmt_id, parent_id, title, summary, article, status, is_display, rank_order, skill_id, action, author_id, created_at) FROM stdin;
\.


--
-- TOC entry 3929 (class 0 OID 16667)
-- Dependencies: 255
-- Data for Name: skill_mgmt; Type: TABLE DATA; Schema: public; Owner: ml_pg_user
--

COPY public.skill_mgmt (id, parent_id, name, slug, status, is_display, rank_order, is_delete, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 3964 (class 0 OID 16897)
-- Dependencies: 290
-- Data for Name: skill_mgmt_hist; Type: TABLE DATA; Schema: public; Owner: ml_pg_user
--

COPY public.skill_mgmt_hist (id, skill_mgmt_id, parent_id, name, slug, status, is_display, rank_order, action, author_id, created_at) FROM stdin;
\.


--
-- TOC entry 3934 (class 0 OID 16723)
-- Dependencies: 260
-- Data for Name: slider_mgmt; Type: TABLE DATA; Schema: public; Owner: ml_pg_user
--

COPY public.slider_mgmt (id, title, slug, link, image, status, is_delete, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 3968 (class 0 OID 16923)
-- Dependencies: 294
-- Data for Name: slider_mgmt_hist; Type: TABLE DATA; Schema: public; Owner: ml_pg_user
--

COPY public.slider_mgmt_hist (id, slider_mgmt_id, title, slug, link, image, status, action, author_id, created_at) FROM stdin;
\.


--
-- TOC entry 3942 (class 0 OID 16762)
-- Dependencies: 268
-- Data for Name: social_mgmt; Type: TABLE DATA; Schema: public; Owner: ml_pg_user
--

COPY public.social_mgmt (id, name, slug, link, image, status, is_display, rank_order, is_delete, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 3972 (class 0 OID 16949)
-- Dependencies: 298
-- Data for Name: social_mgmt_hist; Type: TABLE DATA; Schema: public; Owner: ml_pg_user
--

COPY public.social_mgmt_hist (id, social_mgmt_id, name, slug, link, image, status, is_display, rank_order, action, author_id, created_at) FROM stdin;
\.


--
-- TOC entry 3923 (class 0 OID 16619)
-- Dependencies: 249
-- Data for Name: translation_language_mst; Type: TABLE DATA; Schema: public; Owner: ml_pg_user
--

COPY public.translation_language_mst (translation_mst_id, language_mst_id, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 3920 (class 0 OID 16602)
-- Dependencies: 246
-- Data for Name: translation_mst; Type: TABLE DATA; Schema: public; Owner: ml_pg_user
--

COPY public.translation_mst (id, language_id, original_id, value, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 3960 (class 0 OID 16871)
-- Dependencies: 286
-- Data for Name: translation_mst_hist; Type: TABLE DATA; Schema: public; Owner: ml_pg_user
--

COPY public.translation_mst_hist (id, translation_mst_id, language_id, original_id, value, action, author_id, created_at) FROM stdin;
\.


--
-- TOC entry 3940 (class 0 OID 16751)
-- Dependencies: 266
-- Data for Name: user_mgmt; Type: TABLE DATA; Schema: public; Owner: ml_pg_user
--

COPY public.user_mgmt (id, email, user_name, password, first_name, last_name, address, phone_number, birth, gender, status, is_active, avatar, email_verified_at, remember_token, is_delete, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 3976 (class 0 OID 16975)
-- Dependencies: 302
-- Data for Name: user_mgmt_hist; Type: TABLE DATA; Schema: public; Owner: ml_pg_user
--

COPY public.user_mgmt_hist (id, user_mgmt_id, email, user_name, password, first_name, last_name, address, phone_number, birth, gender, status, is_active, avatar, email_verified_at, remember_token, action, author_id, created_at) FROM stdin;
\.


--
-- TOC entry 3925 (class 0 OID 16635)
-- Dependencies: 251
-- Data for Name: user_mst; Type: TABLE DATA; Schema: public; Owner: ml_pg_user
--

COPY public.user_mst (id, email, user_name, password, first_name, last_name, address, phone_number, birth, gender, status, is_active, avatar, email_verified_at, is_delete, remember_token, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 3891 (class 0 OID 16397)
-- Dependencies: 217
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: ml_pg_user
--

COPY public.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at) FROM stdin;
\.


--
-- TOC entry 4346 (class 0 OID 0)
-- Dependencies: 269
-- Name: admin_mst_hist_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ml_pg_user
--

SELECT pg_catalog.setval('public.admin_mst_hist_id_seq', 1, false);


--
-- TOC entry 4347 (class 0 OID 0)
-- Dependencies: 227
-- Name: admin_mst_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ml_pg_user
--

SELECT pg_catalog.setval('public.admin_mst_id_seq', 1, false);


--
-- TOC entry 4348 (class 0 OID 0)
-- Dependencies: 279
-- Name: api_mst_hist_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ml_pg_user
--

SELECT pg_catalog.setval('public.api_mst_hist_id_seq', 1, false);


--
-- TOC entry 4349 (class 0 OID 0)
-- Dependencies: 234
-- Name: api_mst_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ml_pg_user
--

SELECT pg_catalog.setval('public.api_mst_id_seq', 1, false);


--
-- TOC entry 4350 (class 0 OID 0)
-- Dependencies: 295
-- Name: banner_mgmt_hist_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ml_pg_user
--

SELECT pg_catalog.setval('public.banner_mgmt_hist_id_seq', 1, false);


--
-- TOC entry 4351 (class 0 OID 0)
-- Dependencies: 261
-- Name: banner_mgmt_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ml_pg_user
--

SELECT pg_catalog.setval('public.banner_mgmt_id_seq', 1, false);


--
-- TOC entry 4352 (class 0 OID 0)
-- Dependencies: 287
-- Name: category_mgmt_hist_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ml_pg_user
--

SELECT pg_catalog.setval('public.category_mgmt_hist_id_seq', 1, false);


--
-- TOC entry 4353 (class 0 OID 0)
-- Dependencies: 252
-- Name: category_mgmt_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ml_pg_user
--

SELECT pg_catalog.setval('public.category_mgmt_id_seq', 1, false);


--
-- TOC entry 4354 (class 0 OID 0)
-- Dependencies: 271
-- Name: department_mst_hist_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ml_pg_user
--

SELECT pg_catalog.setval('public.department_mst_hist_id_seq', 1, false);


--
-- TOC entry 4355 (class 0 OID 0)
-- Dependencies: 237
-- Name: department_mst_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ml_pg_user
--

SELECT pg_catalog.setval('public.department_mst_id_seq', 1, false);


--
-- TOC entry 4356 (class 0 OID 0)
-- Dependencies: 225
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ml_pg_user
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- TOC entry 4357 (class 0 OID 0)
-- Dependencies: 277
-- Name: feature_mst_hist_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ml_pg_user
--

SELECT pg_catalog.setval('public.feature_mst_hist_id_seq', 1, false);


--
-- TOC entry 4358 (class 0 OID 0)
-- Dependencies: 232
-- Name: feature_mst_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ml_pg_user
--

SELECT pg_catalog.setval('public.feature_mst_id_seq', 1, false);


--
-- TOC entry 4359 (class 0 OID 0)
-- Dependencies: 222
-- Name: jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ml_pg_user
--

SELECT pg_catalog.setval('public.jobs_id_seq', 1, false);


--
-- TOC entry 4360 (class 0 OID 0)
-- Dependencies: 283
-- Name: language_mst_hist_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ml_pg_user
--

SELECT pg_catalog.setval('public.language_mst_hist_id_seq', 1, false);


--
-- TOC entry 4361 (class 0 OID 0)
-- Dependencies: 247
-- Name: language_mst_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ml_pg_user
--

SELECT pg_catalog.setval('public.language_mst_id_seq', 1, false);


--
-- TOC entry 4362 (class 0 OID 0)
-- Dependencies: 214
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ml_pg_user
--

SELECT pg_catalog.setval('public.migrations_id_seq', 48, true);


--
-- TOC entry 4363 (class 0 OID 0)
-- Dependencies: 281
-- Name: original_translator_mst_hist_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ml_pg_user
--

SELECT pg_catalog.setval('public.original_translator_mst_hist_id_seq', 1, false);


--
-- TOC entry 4364 (class 0 OID 0)
-- Dependencies: 243
-- Name: original_translator_mst_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ml_pg_user
--

SELECT pg_catalog.setval('public.original_translator_mst_id_seq', 1, false);


--
-- TOC entry 4365 (class 0 OID 0)
-- Dependencies: 273
-- Name: policy_department_mst_hist_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ml_pg_user
--

SELECT pg_catalog.setval('public.policy_department_mst_hist_id_seq', 1, false);


--
-- TOC entry 4366 (class 0 OID 0)
-- Dependencies: 240
-- Name: policy_department_mst_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ml_pg_user
--

SELECT pg_catalog.setval('public.policy_department_mst_id_seq', 1, false);


--
-- TOC entry 4367 (class 0 OID 0)
-- Dependencies: 275
-- Name: role_mst_hist_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ml_pg_user
--

SELECT pg_catalog.setval('public.role_mst_hist_id_seq', 1, false);


--
-- TOC entry 4368 (class 0 OID 0)
-- Dependencies: 229
-- Name: role_mst_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ml_pg_user
--

SELECT pg_catalog.setval('public.role_mst_id_seq', 1, false);


--
-- TOC entry 4369 (class 0 OID 0)
-- Dependencies: 299
-- Name: setting_link_mgmt_hist_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ml_pg_user
--

SELECT pg_catalog.setval('public.setting_link_mgmt_hist_id_seq', 1, false);


--
-- TOC entry 4370 (class 0 OID 0)
-- Dependencies: 263
-- Name: setting_link_mgmt_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ml_pg_user
--

SELECT pg_catalog.setval('public.setting_link_mgmt_id_seq', 1, false);


--
-- TOC entry 4371 (class 0 OID 0)
-- Dependencies: 291
-- Name: skill_description_mgmt_hist_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ml_pg_user
--

SELECT pg_catalog.setval('public.skill_description_mgmt_hist_id_seq', 1, false);


--
-- TOC entry 4372 (class 0 OID 0)
-- Dependencies: 257
-- Name: skill_description_mgmt_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ml_pg_user
--

SELECT pg_catalog.setval('public.skill_description_mgmt_id_seq', 1, false);


--
-- TOC entry 4373 (class 0 OID 0)
-- Dependencies: 289
-- Name: skill_mgmt_hist_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ml_pg_user
--

SELECT pg_catalog.setval('public.skill_mgmt_hist_id_seq', 1, false);


--
-- TOC entry 4374 (class 0 OID 0)
-- Dependencies: 254
-- Name: skill_mgmt_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ml_pg_user
--

SELECT pg_catalog.setval('public.skill_mgmt_id_seq', 1, false);


--
-- TOC entry 4375 (class 0 OID 0)
-- Dependencies: 293
-- Name: slider_mgmt_hist_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ml_pg_user
--

SELECT pg_catalog.setval('public.slider_mgmt_hist_id_seq', 1, false);


--
-- TOC entry 4376 (class 0 OID 0)
-- Dependencies: 259
-- Name: slider_mgmt_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ml_pg_user
--

SELECT pg_catalog.setval('public.slider_mgmt_id_seq', 1, false);


--
-- TOC entry 4377 (class 0 OID 0)
-- Dependencies: 297
-- Name: social_mgmt_hist_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ml_pg_user
--

SELECT pg_catalog.setval('public.social_mgmt_hist_id_seq', 1, false);


--
-- TOC entry 4378 (class 0 OID 0)
-- Dependencies: 267
-- Name: social_mgmt_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ml_pg_user
--

SELECT pg_catalog.setval('public.social_mgmt_id_seq', 1, false);


--
-- TOC entry 4379 (class 0 OID 0)
-- Dependencies: 285
-- Name: translation_mst_hist_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ml_pg_user
--

SELECT pg_catalog.setval('public.translation_mst_hist_id_seq', 1, false);


--
-- TOC entry 4380 (class 0 OID 0)
-- Dependencies: 245
-- Name: translation_mst_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ml_pg_user
--

SELECT pg_catalog.setval('public.translation_mst_id_seq', 1, false);


--
-- TOC entry 4381 (class 0 OID 0)
-- Dependencies: 301
-- Name: user_mgmt_hist_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ml_pg_user
--

SELECT pg_catalog.setval('public.user_mgmt_hist_id_seq', 1, false);


--
-- TOC entry 4382 (class 0 OID 0)
-- Dependencies: 265
-- Name: user_mgmt_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ml_pg_user
--

SELECT pg_catalog.setval('public.user_mgmt_id_seq', 1, false);


--
-- TOC entry 4383 (class 0 OID 0)
-- Dependencies: 250
-- Name: user_mst_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ml_pg_user
--

SELECT pg_catalog.setval('public.user_mst_id_seq', 1, false);


--
-- TOC entry 4384 (class 0 OID 0)
-- Dependencies: 216
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ml_pg_user
--

SELECT pg_catalog.setval('public.users_id_seq', 1, false);


--
-- TOC entry 3636 (class 2606 OID 16559)
-- Name: admin_department_mst admin_department_mst_pkey; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.admin_department_mst
    ADD CONSTRAINT admin_department_mst_pkey PRIMARY KEY (admin_mst_id, department_mst_id);


--
-- TOC entry 3616 (class 2606 OID 16478)
-- Name: admin_mst admin_mst_email_unique; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.admin_mst
    ADD CONSTRAINT admin_mst_email_unique UNIQUE (email);


--
-- TOC entry 3674 (class 2606 OID 16775)
-- Name: admin_mst_hist admin_mst_hist_pkey; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.admin_mst_hist
    ADD CONSTRAINT admin_mst_hist_pkey PRIMARY KEY (id);


--
-- TOC entry 3618 (class 2606 OID 16476)
-- Name: admin_mst admin_mst_pkey; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.admin_mst
    ADD CONSTRAINT admin_mst_pkey PRIMARY KEY (id);


--
-- TOC entry 3620 (class 2606 OID 16480)
-- Name: admin_mst admin_mst_user_name_unique; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.admin_mst
    ADD CONSTRAINT admin_mst_user_name_unique UNIQUE (user_name);


--
-- TOC entry 3626 (class 2606 OID 16496)
-- Name: admin_role_mst admin_role_mst_pkey; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.admin_role_mst
    ADD CONSTRAINT admin_role_mst_pkey PRIMARY KEY (admin_mst_id, role_mst_id);


--
-- TOC entry 3684 (class 2606 OID 16835)
-- Name: api_mst_hist api_mst_hist_pkey; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.api_mst_hist
    ADD CONSTRAINT api_mst_hist_pkey PRIMARY KEY (id);


--
-- TOC entry 3630 (class 2606 OID 16525)
-- Name: api_mst api_mst_pkey; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.api_mst
    ADD CONSTRAINT api_mst_pkey PRIMARY KEY (id);


--
-- TOC entry 3632 (class 2606 OID 16535)
-- Name: api_role_mst api_role_mst_pkey; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.api_role_mst
    ADD CONSTRAINT api_role_mst_pkey PRIMARY KEY (api_mst_id, role_mst_id);


--
-- TOC entry 3700 (class 2606 OID 16942)
-- Name: banner_mgmt_hist banner_mgmt_hist_pkey; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.banner_mgmt_hist
    ADD CONSTRAINT banner_mgmt_hist_pkey PRIMARY KEY (id);


--
-- TOC entry 3666 (class 2606 OID 16741)
-- Name: banner_mgmt banner_mgmt_pkey; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.banner_mgmt
    ADD CONSTRAINT banner_mgmt_pkey PRIMARY KEY (id);


--
-- TOC entry 3605 (class 2606 OID 16436)
-- Name: cache_locks cache_locks_pkey; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);


--
-- TOC entry 3603 (class 2606 OID 16429)
-- Name: cache cache_pkey; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);


--
-- TOC entry 3692 (class 2606 OID 16890)
-- Name: category_mgmt_hist category_mgmt_hist_pkey; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.category_mgmt_hist
    ADD CONSTRAINT category_mgmt_hist_pkey PRIMARY KEY (id);


--
-- TOC entry 3656 (class 2606 OID 16660)
-- Name: category_mgmt category_mgmt_pkey; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.category_mgmt
    ADD CONSTRAINT category_mgmt_pkey PRIMARY KEY (id);


--
-- TOC entry 3660 (class 2606 OID 16687)
-- Name: category_skill_mgmt category_skill_mgmt_pkey; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.category_skill_mgmt
    ADD CONSTRAINT category_skill_mgmt_pkey PRIMARY KEY (category_mgmt_id, skill_mgmt_id);


--
-- TOC entry 3640 (class 2606 OID 16582)
-- Name: department_management_mst department_management_mst_pkey; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.department_management_mst
    ADD CONSTRAINT department_management_mst_pkey PRIMARY KEY (department_mst_id, policy_department_mst_id);


--
-- TOC entry 3676 (class 2606 OID 16787)
-- Name: department_mst_hist department_mst_hist_pkey; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.department_mst_hist
    ADD CONSTRAINT department_mst_hist_pkey PRIMARY KEY (id);


--
-- TOC entry 3634 (class 2606 OID 16554)
-- Name: department_mst department_mst_pkey; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.department_mst
    ADD CONSTRAINT department_mst_pkey PRIMARY KEY (id);


--
-- TOC entry 3612 (class 2606 OID 16463)
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- TOC entry 3614 (class 2606 OID 16465)
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- TOC entry 3682 (class 2606 OID 16823)
-- Name: feature_mst_hist feature_mst_hist_pkey; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.feature_mst_hist
    ADD CONSTRAINT feature_mst_hist_pkey PRIMARY KEY (id);


--
-- TOC entry 3628 (class 2606 OID 16515)
-- Name: feature_mst feature_mst_pkey; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.feature_mst
    ADD CONSTRAINT feature_mst_pkey PRIMARY KEY (id);


--
-- TOC entry 3610 (class 2606 OID 16453)
-- Name: job_batches job_batches_pkey; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.job_batches
    ADD CONSTRAINT job_batches_pkey PRIMARY KEY (id);


--
-- TOC entry 3607 (class 2606 OID 16445)
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- TOC entry 3688 (class 2606 OID 16864)
-- Name: language_mst_hist language_mst_hist_pkey; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.language_mst_hist
    ADD CONSTRAINT language_mst_hist_pkey PRIMARY KEY (id);


--
-- TOC entry 3646 (class 2606 OID 16618)
-- Name: language_mst language_mst_pkey; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.language_mst
    ADD CONSTRAINT language_mst_pkey PRIMARY KEY (id);


--
-- TOC entry 3591 (class 2606 OID 16395)
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- TOC entry 3686 (class 2606 OID 16852)
-- Name: original_translator_mst_hist original_translator_mst_hist_pkey; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.original_translator_mst_hist
    ADD CONSTRAINT original_translator_mst_hist_pkey PRIMARY KEY (id);


--
-- TOC entry 3642 (class 2606 OID 16600)
-- Name: original_translator_mst original_translator_mst_pkey; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.original_translator_mst
    ADD CONSTRAINT original_translator_mst_pkey PRIMARY KEY (id);


--
-- TOC entry 3597 (class 2606 OID 16413)
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- TOC entry 3678 (class 2606 OID 16799)
-- Name: policy_department_mst_hist policy_department_mst_hist_pkey; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.policy_department_mst_hist
    ADD CONSTRAINT policy_department_mst_hist_pkey PRIMARY KEY (id);


--
-- TOC entry 3638 (class 2606 OID 16577)
-- Name: policy_department_mst policy_department_mst_pkey; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.policy_department_mst
    ADD CONSTRAINT policy_department_mst_pkey PRIMARY KEY (id);


--
-- TOC entry 3680 (class 2606 OID 16811)
-- Name: role_mst_hist role_mst_hist_pkey; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.role_mst_hist
    ADD CONSTRAINT role_mst_hist_pkey PRIMARY KEY (id);


--
-- TOC entry 3622 (class 2606 OID 16491)
-- Name: role_mst role_mst_name_unique; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.role_mst
    ADD CONSTRAINT role_mst_name_unique UNIQUE (name);


--
-- TOC entry 3624 (class 2606 OID 16489)
-- Name: role_mst role_mst_pkey; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.role_mst
    ADD CONSTRAINT role_mst_pkey PRIMARY KEY (id);


--
-- TOC entry 3600 (class 2606 OID 16420)
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- TOC entry 3704 (class 2606 OID 16968)
-- Name: setting_link_mgmt_hist setting_link_mgmt_hist_pkey; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.setting_link_mgmt_hist
    ADD CONSTRAINT setting_link_mgmt_hist_pkey PRIMARY KEY (id);


--
-- TOC entry 3668 (class 2606 OID 16749)
-- Name: setting_link_mgmt setting_link_mgmt_pkey; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.setting_link_mgmt
    ADD CONSTRAINT setting_link_mgmt_pkey PRIMARY KEY (id);


--
-- TOC entry 3696 (class 2606 OID 16916)
-- Name: skill_description_mgmt_hist skill_description_mgmt_hist_pkey; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.skill_description_mgmt_hist
    ADD CONSTRAINT skill_description_mgmt_hist_pkey PRIMARY KEY (id);


--
-- TOC entry 3662 (class 2606 OID 16711)
-- Name: skill_description_mgmt skill_description_mgmt_pkey; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.skill_description_mgmt
    ADD CONSTRAINT skill_description_mgmt_pkey PRIMARY KEY (id);


--
-- TOC entry 3694 (class 2606 OID 16902)
-- Name: skill_mgmt_hist skill_mgmt_hist_pkey; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.skill_mgmt_hist
    ADD CONSTRAINT skill_mgmt_hist_pkey PRIMARY KEY (id);


--
-- TOC entry 3658 (class 2606 OID 16677)
-- Name: skill_mgmt skill_mgmt_pkey; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.skill_mgmt
    ADD CONSTRAINT skill_mgmt_pkey PRIMARY KEY (id);


--
-- TOC entry 3698 (class 2606 OID 16928)
-- Name: slider_mgmt_hist slider_mgmt_hist_pkey; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.slider_mgmt_hist
    ADD CONSTRAINT slider_mgmt_hist_pkey PRIMARY KEY (id);


--
-- TOC entry 3664 (class 2606 OID 16730)
-- Name: slider_mgmt slider_mgmt_pkey; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.slider_mgmt
    ADD CONSTRAINT slider_mgmt_pkey PRIMARY KEY (id);


--
-- TOC entry 3702 (class 2606 OID 16956)
-- Name: social_mgmt_hist social_mgmt_hist_pkey; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.social_mgmt_hist
    ADD CONSTRAINT social_mgmt_hist_pkey PRIMARY KEY (id);


--
-- TOC entry 3672 (class 2606 OID 16768)
-- Name: social_mgmt social_mgmt_pkey; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.social_mgmt
    ADD CONSTRAINT social_mgmt_pkey PRIMARY KEY (id);


--
-- TOC entry 3648 (class 2606 OID 16623)
-- Name: translation_language_mst translation_language_mst_pkey; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.translation_language_mst
    ADD CONSTRAINT translation_language_mst_pkey PRIMARY KEY (translation_mst_id, language_mst_id);


--
-- TOC entry 3690 (class 2606 OID 16878)
-- Name: translation_mst_hist translation_mst_hist_pkey; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.translation_mst_hist
    ADD CONSTRAINT translation_mst_hist_pkey PRIMARY KEY (id);


--
-- TOC entry 3644 (class 2606 OID 16607)
-- Name: translation_mst translation_mst_pkey; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.translation_mst
    ADD CONSTRAINT translation_mst_pkey PRIMARY KEY (id);


--
-- TOC entry 3706 (class 2606 OID 16980)
-- Name: user_mgmt_hist user_mgmt_hist_pkey; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.user_mgmt_hist
    ADD CONSTRAINT user_mgmt_hist_pkey PRIMARY KEY (id);


--
-- TOC entry 3670 (class 2606 OID 16760)
-- Name: user_mgmt user_mgmt_pkey; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.user_mgmt
    ADD CONSTRAINT user_mgmt_pkey PRIMARY KEY (id);


--
-- TOC entry 3650 (class 2606 OID 16646)
-- Name: user_mst user_mst_email_unique; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.user_mst
    ADD CONSTRAINT user_mst_email_unique UNIQUE (email);


--
-- TOC entry 3652 (class 2606 OID 16644)
-- Name: user_mst user_mst_pkey; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.user_mst
    ADD CONSTRAINT user_mst_pkey PRIMARY KEY (id);


--
-- TOC entry 3654 (class 2606 OID 16648)
-- Name: user_mst user_mst_user_name_unique; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.user_mst
    ADD CONSTRAINT user_mst_user_name_unique UNIQUE (user_name);


--
-- TOC entry 3593 (class 2606 OID 16406)
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- TOC entry 3595 (class 2606 OID 16404)
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- TOC entry 3608 (class 1259 OID 16446)
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: ml_pg_user
--

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


--
-- TOC entry 3598 (class 1259 OID 16422)
-- Name: sessions_last_activity_index; Type: INDEX; Schema: public; Owner: ml_pg_user
--

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);


--
-- TOC entry 3601 (class 1259 OID 16421)
-- Name: sessions_user_id_index; Type: INDEX; Schema: public; Owner: ml_pg_user
--

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


--
-- TOC entry 3742 (class 2620 OID 16987)
-- Name: api_mst after_api_insert; Type: TRIGGER; Schema: public; Owner: ml_pg_user
--

CREATE TRIGGER after_api_insert AFTER INSERT ON public.api_mst FOR EACH ROW EXECUTE FUNCTION public.insert_into_api_role_from_api();


--
-- TOC entry 3743 (class 2620 OID 16989)
-- Name: policy_department_mst after_policy_department_insert; Type: TRIGGER; Schema: public; Owner: ml_pg_user
--

CREATE TRIGGER after_policy_department_insert AFTER INSERT ON public.policy_department_mst FOR EACH ROW EXECUTE FUNCTION public.insert_into_department_management();


--
-- TOC entry 3712 (class 2606 OID 16560)
-- Name: admin_department_mst admin_department_mst_admin_mst_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.admin_department_mst
    ADD CONSTRAINT admin_department_mst_admin_mst_id_foreign FOREIGN KEY (admin_mst_id) REFERENCES public.admin_mst(id);


--
-- TOC entry 3713 (class 2606 OID 16565)
-- Name: admin_department_mst admin_department_mst_department_mst_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.admin_department_mst
    ADD CONSTRAINT admin_department_mst_department_mst_id_foreign FOREIGN KEY (department_mst_id) REFERENCES public.department_mst(id);


--
-- TOC entry 3724 (class 2606 OID 16776)
-- Name: admin_mst_hist admin_mst_hist_admin_mst_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.admin_mst_hist
    ADD CONSTRAINT admin_mst_hist_admin_mst_id_foreign FOREIGN KEY (admin_mst_id) REFERENCES public.admin_mst(id);


--
-- TOC entry 3707 (class 2606 OID 16497)
-- Name: admin_role_mst admin_role_mst_admin_mst_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.admin_role_mst
    ADD CONSTRAINT admin_role_mst_admin_mst_id_foreign FOREIGN KEY (admin_mst_id) REFERENCES public.admin_mst(id);


--
-- TOC entry 3708 (class 2606 OID 16502)
-- Name: admin_role_mst admin_role_mst_role_mst_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.admin_role_mst
    ADD CONSTRAINT admin_role_mst_role_mst_id_foreign FOREIGN KEY (role_mst_id) REFERENCES public.role_mst(id);


--
-- TOC entry 3709 (class 2606 OID 16526)
-- Name: api_mst api_mst_feature_mst_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.api_mst
    ADD CONSTRAINT api_mst_feature_mst_id_foreign FOREIGN KEY (feature_mst_id) REFERENCES public.feature_mst(id);


--
-- TOC entry 3729 (class 2606 OID 16836)
-- Name: api_mst_hist api_mst_hist_api_mst_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.api_mst_hist
    ADD CONSTRAINT api_mst_hist_api_mst_id_foreign FOREIGN KEY (api_mst_id) REFERENCES public.api_mst(id);


--
-- TOC entry 3730 (class 2606 OID 16841)
-- Name: api_mst_hist api_mst_hist_feature_mst_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.api_mst_hist
    ADD CONSTRAINT api_mst_hist_feature_mst_id_foreign FOREIGN KEY (feature_mst_id) REFERENCES public.feature_mst(id);


--
-- TOC entry 3710 (class 2606 OID 16536)
-- Name: api_role_mst api_role_mst_api_mst_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.api_role_mst
    ADD CONSTRAINT api_role_mst_api_mst_id_foreign FOREIGN KEY (api_mst_id) REFERENCES public.api_mst(id);


--
-- TOC entry 3711 (class 2606 OID 16541)
-- Name: api_role_mst api_role_mst_role_mst_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.api_role_mst
    ADD CONSTRAINT api_role_mst_role_mst_id_foreign FOREIGN KEY (role_mst_id) REFERENCES public.role_mst(id);


--
-- TOC entry 3738 (class 2606 OID 16943)
-- Name: banner_mgmt_hist banner_mgmt_hist_banner_mgmt_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.banner_mgmt_hist
    ADD CONSTRAINT banner_mgmt_hist_banner_mgmt_id_foreign FOREIGN KEY (banner_mgmt_id) REFERENCES public.banner_mgmt(id);


--
-- TOC entry 3734 (class 2606 OID 16891)
-- Name: category_mgmt_hist category_mgmt_hist_category_mgmt_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.category_mgmt_hist
    ADD CONSTRAINT category_mgmt_hist_category_mgmt_id_foreign FOREIGN KEY (category_mgmt_id) REFERENCES public.category_mgmt(id);


--
-- TOC entry 3718 (class 2606 OID 16661)
-- Name: category_mgmt category_mgmt_parent_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.category_mgmt
    ADD CONSTRAINT category_mgmt_parent_id_foreign FOREIGN KEY (parent_id) REFERENCES public.category_mgmt(id);


--
-- TOC entry 3720 (class 2606 OID 16688)
-- Name: category_skill_mgmt category_skill_mgmt_category_mgmt_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.category_skill_mgmt
    ADD CONSTRAINT category_skill_mgmt_category_mgmt_id_foreign FOREIGN KEY (category_mgmt_id) REFERENCES public.category_mgmt(id);


--
-- TOC entry 3721 (class 2606 OID 16693)
-- Name: category_skill_mgmt category_skill_mgmt_skill_mgmt_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.category_skill_mgmt
    ADD CONSTRAINT category_skill_mgmt_skill_mgmt_id_foreign FOREIGN KEY (skill_mgmt_id) REFERENCES public.skill_mgmt(id);


--
-- TOC entry 3714 (class 2606 OID 16583)
-- Name: department_management_mst department_management_mst_department_mst_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.department_management_mst
    ADD CONSTRAINT department_management_mst_department_mst_id_foreign FOREIGN KEY (department_mst_id) REFERENCES public.department_mst(id);


--
-- TOC entry 3715 (class 2606 OID 16588)
-- Name: department_management_mst department_management_mst_policy_department_mst_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.department_management_mst
    ADD CONSTRAINT department_management_mst_policy_department_mst_id_foreign FOREIGN KEY (policy_department_mst_id) REFERENCES public.policy_department_mst(id);


--
-- TOC entry 3725 (class 2606 OID 16788)
-- Name: department_mst_hist department_mst_hist_department_mst_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.department_mst_hist
    ADD CONSTRAINT department_mst_hist_department_mst_id_foreign FOREIGN KEY (department_mst_id) REFERENCES public.department_mst(id);


--
-- TOC entry 3728 (class 2606 OID 16824)
-- Name: feature_mst_hist feature_mst_hist_feature_mst_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.feature_mst_hist
    ADD CONSTRAINT feature_mst_hist_feature_mst_id_foreign FOREIGN KEY (feature_mst_id) REFERENCES public.feature_mst(id);


--
-- TOC entry 3732 (class 2606 OID 16865)
-- Name: language_mst_hist language_mst_hist_language_mst_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.language_mst_hist
    ADD CONSTRAINT language_mst_hist_language_mst_id_foreign FOREIGN KEY (language_mst_id) REFERENCES public.language_mst(id);


--
-- TOC entry 3731 (class 2606 OID 16853)
-- Name: original_translator_mst_hist original_translator_mst_hist_original_translator_mst_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.original_translator_mst_hist
    ADD CONSTRAINT original_translator_mst_hist_original_translator_mst_id_foreign FOREIGN KEY (original_translator_mst_id) REFERENCES public.original_translator_mst(id);


--
-- TOC entry 3726 (class 2606 OID 16800)
-- Name: policy_department_mst_hist policy_department_mst_hist_policy_department_mst_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.policy_department_mst_hist
    ADD CONSTRAINT policy_department_mst_hist_policy_department_mst_id_foreign FOREIGN KEY (policy_department_mst_id) REFERENCES public.policy_department_mst(id);


--
-- TOC entry 3727 (class 2606 OID 16812)
-- Name: role_mst_hist role_mst_hist_role_mst_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.role_mst_hist
    ADD CONSTRAINT role_mst_hist_role_mst_id_foreign FOREIGN KEY (role_mst_id) REFERENCES public.role_mst(id);


--
-- TOC entry 3740 (class 2606 OID 16969)
-- Name: setting_link_mgmt_hist setting_link_mgmt_hist_setting_link_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.setting_link_mgmt_hist
    ADD CONSTRAINT setting_link_mgmt_hist_setting_link_id_foreign FOREIGN KEY (setting_link_id) REFERENCES public.setting_link_mgmt(id);


--
-- TOC entry 3736 (class 2606 OID 16917)
-- Name: skill_description_mgmt_hist skill_description_mgmt_hist_skill_description_mgmt_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.skill_description_mgmt_hist
    ADD CONSTRAINT skill_description_mgmt_hist_skill_description_mgmt_id_foreign FOREIGN KEY (skill_description_mgmt_id) REFERENCES public.skill_description_mgmt(id);


--
-- TOC entry 3722 (class 2606 OID 16712)
-- Name: skill_description_mgmt skill_description_mgmt_parent_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.skill_description_mgmt
    ADD CONSTRAINT skill_description_mgmt_parent_id_foreign FOREIGN KEY (parent_id) REFERENCES public.skill_description_mgmt(id);


--
-- TOC entry 3723 (class 2606 OID 16717)
-- Name: skill_description_mgmt skill_description_mgmt_skill_mgmt_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.skill_description_mgmt
    ADD CONSTRAINT skill_description_mgmt_skill_mgmt_id_foreign FOREIGN KEY (skill_mgmt_id) REFERENCES public.skill_mgmt(id);


--
-- TOC entry 3735 (class 2606 OID 16903)
-- Name: skill_mgmt_hist skill_mgmt_hist_skill_mgmt_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.skill_mgmt_hist
    ADD CONSTRAINT skill_mgmt_hist_skill_mgmt_id_foreign FOREIGN KEY (skill_mgmt_id) REFERENCES public.skill_mgmt(id);


--
-- TOC entry 3719 (class 2606 OID 16678)
-- Name: skill_mgmt skill_mgmt_parent_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.skill_mgmt
    ADD CONSTRAINT skill_mgmt_parent_id_foreign FOREIGN KEY (parent_id) REFERENCES public.skill_mgmt(id);


--
-- TOC entry 3737 (class 2606 OID 16929)
-- Name: slider_mgmt_hist slider_mgmt_hist_slider_mgmt_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.slider_mgmt_hist
    ADD CONSTRAINT slider_mgmt_hist_slider_mgmt_id_foreign FOREIGN KEY (slider_mgmt_id) REFERENCES public.slider_mgmt(id);


--
-- TOC entry 3739 (class 2606 OID 16957)
-- Name: social_mgmt_hist social_mgmt_hist_social_mgmt_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.social_mgmt_hist
    ADD CONSTRAINT social_mgmt_hist_social_mgmt_id_foreign FOREIGN KEY (social_mgmt_id) REFERENCES public.social_mgmt(id);


--
-- TOC entry 3716 (class 2606 OID 16629)
-- Name: translation_language_mst translation_language_mst_language_mst_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.translation_language_mst
    ADD CONSTRAINT translation_language_mst_language_mst_id_foreign FOREIGN KEY (language_mst_id) REFERENCES public.language_mst(id);


--
-- TOC entry 3717 (class 2606 OID 16624)
-- Name: translation_language_mst translation_language_mst_translation_mst_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.translation_language_mst
    ADD CONSTRAINT translation_language_mst_translation_mst_id_foreign FOREIGN KEY (translation_mst_id) REFERENCES public.translation_mst(id);


--
-- TOC entry 3733 (class 2606 OID 16879)
-- Name: translation_mst_hist translation_mst_hist_translation_mst_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.translation_mst_hist
    ADD CONSTRAINT translation_mst_hist_translation_mst_id_foreign FOREIGN KEY (translation_mst_id) REFERENCES public.translation_mst(id);


--
-- TOC entry 3741 (class 2606 OID 16981)
-- Name: user_mgmt_hist user_mgmt_hist_user_mgmt_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: ml_pg_user
--

ALTER TABLE ONLY public.user_mgmt_hist
    ADD CONSTRAINT user_mgmt_hist_user_mgmt_id_foreign FOREIGN KEY (user_mgmt_id) REFERENCES public.user_mgmt(id);


-- Completed on 2025-10-01 17:39:09

--
-- PostgreSQL database dump complete
--

