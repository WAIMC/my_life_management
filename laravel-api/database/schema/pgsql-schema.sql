--
-- PostgreSQL database dump
--

\restrict H3X1v26r7oBW8Sl54yPNMbTGg93bxVkdUSWw7d1ivVY26DwpyHyZyzdHS2HLoWd

-- Dumped from database version 15.14
-- Dumped by pg_dump version 17.6

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
-- Name: insert_into_api_role_from_api(); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION public.insert_into_api_role_from_api() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
            BEGIN
              -- Insert a new record into api_role_mst table
              INSERT INTO api_role_mst (api_id, role_id, created_at, updated_at)
              SELECT NEW.id, id, now(), now() FROM role_mst WHERE name = 'root';
              RETURN NEW;
            END;
          $$;


--
-- Name: insert_into_department_management(); Type: FUNCTION; Schema: public; Owner: -
--

CREATE FUNCTION public.insert_into_department_management() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
            BEGIN
              -- Insert a new record into department_management_mst table
              INSERT INTO department_management_mst (department_id, policy_department_id, created_at, updated_at)
              SELECT id, NEW.id, now(), now() FROM department_mst WHERE name = 'root';
              RETURN NEW;
            END;
          $$;


SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: admin_department_mst; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.admin_department_mst (
    admin_id integer NOT NULL,
    department_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: admin_mst; Type: TABLE; Schema: public; Owner: -
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
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: COLUMN admin_mst.email; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.admin_mst.email IS 'Admin email';


--
-- Name: COLUMN admin_mst.user_name; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.admin_mst.user_name IS 'Admin user name';


--
-- Name: COLUMN admin_mst.password; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.admin_mst.password IS 'Admin password';


--
-- Name: COLUMN admin_mst.first_name; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.admin_mst.first_name IS 'Admin first name';


--
-- Name: COLUMN admin_mst.last_name; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.admin_mst.last_name IS 'Admin last name';


--
-- Name: COLUMN admin_mst.address; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.admin_mst.address IS 'Admin address';


--
-- Name: COLUMN admin_mst.phone_number; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.admin_mst.phone_number IS 'Admin phone number';


--
-- Name: COLUMN admin_mst.birth; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.admin_mst.birth IS 'Admin birth';


--
-- Name: COLUMN admin_mst.gender; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.admin_mst.gender IS 'Admin gender';


--
-- Name: COLUMN admin_mst.status; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.admin_mst.status IS 'Admin status';


--
-- Name: COLUMN admin_mst.is_active; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.admin_mst.is_active IS 'Admin active';


--
-- Name: COLUMN admin_mst.avatar; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.admin_mst.avatar IS 'Admin avatar name';


--
-- Name: COLUMN admin_mst.email_verified_at; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.admin_mst.email_verified_at IS 'Verified email time';


--
-- Name: admin_mst_hist; Type: TABLE; Schema: public; Owner: -
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


--
-- Name: COLUMN admin_mst_hist.admin_mst_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.admin_mst_hist.admin_mst_id IS 'Admin id';


--
-- Name: COLUMN admin_mst_hist.email; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.admin_mst_hist.email IS 'Admin email';


--
-- Name: COLUMN admin_mst_hist.user_name; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.admin_mst_hist.user_name IS 'Admin user name';


--
-- Name: COLUMN admin_mst_hist.password; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.admin_mst_hist.password IS 'Admin password';


--
-- Name: COLUMN admin_mst_hist.first_name; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.admin_mst_hist.first_name IS 'Admin first name';


--
-- Name: COLUMN admin_mst_hist.last_name; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.admin_mst_hist.last_name IS 'Admin last name';


--
-- Name: COLUMN admin_mst_hist.address; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.admin_mst_hist.address IS 'Admin address';


--
-- Name: COLUMN admin_mst_hist.phone_number; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.admin_mst_hist.phone_number IS 'Admin phone number';


--
-- Name: COLUMN admin_mst_hist.birth; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.admin_mst_hist.birth IS 'Admin birth';


--
-- Name: COLUMN admin_mst_hist.gender; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.admin_mst_hist.gender IS 'Admin gender';


--
-- Name: COLUMN admin_mst_hist.status; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.admin_mst_hist.status IS 'Admin status';


--
-- Name: COLUMN admin_mst_hist.is_active; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.admin_mst_hist.is_active IS 'Admin active';


--
-- Name: COLUMN admin_mst_hist.avatar; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.admin_mst_hist.avatar IS 'Admin avatar name';


--
-- Name: COLUMN admin_mst_hist.email_verified_at; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.admin_mst_hist.email_verified_at IS 'Verified email time';


--
-- Name: COLUMN admin_mst_hist.remember_token; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.admin_mst_hist.remember_token IS 'Admin remember token';


--
-- Name: COLUMN admin_mst_hist.action; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.admin_mst_hist.action IS 'Admin action';


--
-- Name: COLUMN admin_mst_hist.author_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.admin_mst_hist.author_id IS 'Author id';


--
-- Name: COLUMN admin_mst_hist.created_at; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.admin_mst_hist.created_at IS 'Created time';


--
-- Name: admin_mst_hist_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.admin_mst_hist_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: admin_mst_hist_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.admin_mst_hist_id_seq OWNED BY public.admin_mst_hist.id;


--
-- Name: admin_mst_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.admin_mst_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: admin_mst_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.admin_mst_id_seq OWNED BY public.admin_mst.id;


--
-- Name: admin_role_mst; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.admin_role_mst (
    admin_id integer NOT NULL,
    role_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: api_mst; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.api_mst (
    id integer NOT NULL,
    type smallint DEFAULT '0'::smallint NOT NULL,
    name character varying(50) NOT NULL,
    path character varying(100) NOT NULL,
    is_active boolean DEFAULT false NOT NULL,
    feature_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: COLUMN api_mst.type; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.api_mst.type IS 'Api type';


--
-- Name: COLUMN api_mst.name; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.api_mst.name IS 'Api name';


--
-- Name: COLUMN api_mst.path; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.api_mst.path IS 'Api path';


--
-- Name: COLUMN api_mst.is_active; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.api_mst.is_active IS 'Api status';


--
-- Name: COLUMN api_mst.feature_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.api_mst.feature_id IS 'Feature ID';


--
-- Name: api_role_mst; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.api_role_mst (
    api_id integer NOT NULL,
    role_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: feature_mst; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.feature_mst (
    id integer NOT NULL,
    name character varying(50) NOT NULL,
    group_name character varying(50) NOT NULL,
    description character varying(100) NOT NULL,
    status smallint DEFAULT '0'::smallint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: COLUMN feature_mst.name; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.feature_mst.name IS 'Feature name';


--
-- Name: COLUMN feature_mst.group_name; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.feature_mst.group_name IS 'Feature group name';


--
-- Name: COLUMN feature_mst.description; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.feature_mst.description IS 'Feature description';


--
-- Name: COLUMN feature_mst.status; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.feature_mst.status IS 'Feature status';


--
-- Name: role_mst; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.role_mst (
    id integer NOT NULL,
    name character varying(30) NOT NULL,
    permission character varying(50) NOT NULL,
    is_active boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: COLUMN role_mst.name; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.role_mst.name IS 'Role name';


--
-- Name: COLUMN role_mst.permission; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.role_mst.permission IS 'Role description';


--
-- Name: COLUMN role_mst.is_active; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.role_mst.is_active IS 'Role active';


--
-- Name: admin_permission_view; Type: VIEW; Schema: public; Owner: -
--

CREATE VIEW public.admin_permission_view AS
 SELECT am.id AS admin_id,
    rm.id AS role_id,
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
     JOIN public.admin_role_mst arm ON ((arm.admin_id = am.id)))
     JOIN public.role_mst rm ON ((rm.id = arm.role_id)))
     JOIN public.api_role_mst arm2 ON ((arm2.role_id = rm.id)))
     JOIN public.api_mst am2 ON ((am2.id = arm2.api_id)))
     JOIN public.feature_mst fm ON ((fm.id = am2.feature_id)))
  WHERE ((am.status = 1) AND (am.is_active = true) AND (rm.is_active = true) AND (am2.is_active = true) AND (fm.status = 1));


--
-- Name: department_management_mst; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.department_management_mst (
    department_id integer NOT NULL,
    policy_department_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: department_mst; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.department_mst (
    id integer NOT NULL,
    code character varying(50) NOT NULL,
    name character varying(50) NOT NULL,
    status smallint DEFAULT '0'::smallint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: COLUMN department_mst.code; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.department_mst.code IS 'Department code';


--
-- Name: COLUMN department_mst.name; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.department_mst.name IS 'Department name';


--
-- Name: COLUMN department_mst.status; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.department_mst.status IS 'Department status';


--
-- Name: policy_department_mst; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.policy_department_mst (
    id integer NOT NULL,
    table_name character varying(20) NOT NULL,
    row_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: COLUMN policy_department_mst.table_name; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.policy_department_mst.table_name IS 'Table name';


--
-- Name: COLUMN policy_department_mst.row_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.policy_department_mst.row_id IS 'Row id';


--
-- Name: admin_policy_view; Type: VIEW; Schema: public; Owner: -
--

CREATE VIEW public.admin_policy_view AS
 SELECT am.id AS admin_id,
    dm.code AS department_code,
    dm.name AS department_name,
    pdm.table_name,
    pdm.row_id
   FROM ((((public.admin_mst am
     JOIN public.admin_department_mst adm ON ((adm.admin_id = am.id)))
     JOIN public.department_mst dm ON ((dm.id = adm.department_id)))
     JOIN public.department_management_mst dmm ON ((dmm.department_id = dm.id)))
     JOIN public.policy_department_mst pdm ON ((pdm.id = dmm.policy_department_id)))
  WHERE ((am.status = 1) AND (dm.status = 1));


--
-- Name: api_mst_hist; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.api_mst_hist (
    id integer NOT NULL,
    api_mst_id integer NOT NULL,
    type smallint,
    name character varying(50),
    path character varying(100),
    is_active smallint,
    feature_id integer NOT NULL,
    action smallint NOT NULL,
    author_id integer NOT NULL,
    created_at timestamp(0) without time zone NOT NULL
);


--
-- Name: COLUMN api_mst_hist.api_mst_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.api_mst_hist.api_mst_id IS 'Api id';


--
-- Name: COLUMN api_mst_hist.type; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.api_mst_hist.type IS 'Api type';


--
-- Name: COLUMN api_mst_hist.name; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.api_mst_hist.name IS 'Api name';


--
-- Name: COLUMN api_mst_hist.path; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.api_mst_hist.path IS 'Api path';


--
-- Name: COLUMN api_mst_hist.is_active; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.api_mst_hist.is_active IS 'Api status';


--
-- Name: COLUMN api_mst_hist.feature_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.api_mst_hist.feature_id IS 'Feature id';


--
-- Name: COLUMN api_mst_hist.action; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.api_mst_hist.action IS 'Api action';


--
-- Name: COLUMN api_mst_hist.author_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.api_mst_hist.author_id IS 'Author id';


--
-- Name: COLUMN api_mst_hist.created_at; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.api_mst_hist.created_at IS 'Created time';


--
-- Name: api_mst_hist_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.api_mst_hist_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: api_mst_hist_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.api_mst_hist_id_seq OWNED BY public.api_mst_hist.id;


--
-- Name: api_mst_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.api_mst_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: api_mst_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.api_mst_id_seq OWNED BY public.api_mst.id;


--
-- Name: banner_mgmt; Type: TABLE; Schema: public; Owner: -
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
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: COLUMN banner_mgmt.title; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.banner_mgmt.title IS 'Banner title';


--
-- Name: COLUMN banner_mgmt.slug; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.banner_mgmt.slug IS 'Banner slug';


--
-- Name: COLUMN banner_mgmt.description; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.banner_mgmt.description IS 'Banner description';


--
-- Name: COLUMN banner_mgmt.link; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.banner_mgmt.link IS 'Banner path image';


--
-- Name: COLUMN banner_mgmt.image; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.banner_mgmt.image IS 'Banner image name';


--
-- Name: COLUMN banner_mgmt."position"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.banner_mgmt."position" IS 'Banner display position';


--
-- Name: COLUMN banner_mgmt.status; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.banner_mgmt.status IS 'Banner status';


--
-- Name: banner_mgmt_hist; Type: TABLE; Schema: public; Owner: -
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


--
-- Name: COLUMN banner_mgmt_hist.banner_mgmt_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.banner_mgmt_hist.banner_mgmt_id IS 'Banner id';


--
-- Name: COLUMN banner_mgmt_hist.title; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.banner_mgmt_hist.title IS 'Banner title';


--
-- Name: COLUMN banner_mgmt_hist.slug; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.banner_mgmt_hist.slug IS 'Banner slug';


--
-- Name: COLUMN banner_mgmt_hist.description; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.banner_mgmt_hist.description IS 'Banner description';


--
-- Name: COLUMN banner_mgmt_hist.link; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.banner_mgmt_hist.link IS 'Banner path image';


--
-- Name: COLUMN banner_mgmt_hist.image; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.banner_mgmt_hist.image IS 'Banner image name';


--
-- Name: COLUMN banner_mgmt_hist."position"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.banner_mgmt_hist."position" IS 'Banner display position';


--
-- Name: COLUMN banner_mgmt_hist.status; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.banner_mgmt_hist.status IS 'Banner status';


--
-- Name: COLUMN banner_mgmt_hist.action; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.banner_mgmt_hist.action IS 'Banner action';


--
-- Name: COLUMN banner_mgmt_hist.author_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.banner_mgmt_hist.author_id IS 'Author id';


--
-- Name: COLUMN banner_mgmt_hist.created_at; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.banner_mgmt_hist.created_at IS 'Created time';


--
-- Name: banner_mgmt_hist_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.banner_mgmt_hist_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: banner_mgmt_hist_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.banner_mgmt_hist_id_seq OWNED BY public.banner_mgmt_hist.id;


--
-- Name: banner_mgmt_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.banner_mgmt_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: banner_mgmt_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.banner_mgmt_id_seq OWNED BY public.banner_mgmt.id;


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
-- Name: category_mgmt; Type: TABLE; Schema: public; Owner: -
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
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: COLUMN category_mgmt.parent_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.category_mgmt.parent_id IS 'Parent category';


--
-- Name: COLUMN category_mgmt.name; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.category_mgmt.name IS 'Category name';


--
-- Name: COLUMN category_mgmt.slug; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.category_mgmt.slug IS 'Category slug';


--
-- Name: COLUMN category_mgmt.description; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.category_mgmt.description IS 'Category description';


--
-- Name: COLUMN category_mgmt.status; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.category_mgmt.status IS 'Category status';


--
-- Name: COLUMN category_mgmt.is_display; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.category_mgmt.is_display IS 'Display category';


--
-- Name: COLUMN category_mgmt.rank_order; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.category_mgmt.rank_order IS 'Category order';


--
-- Name: category_mgmt_hist; Type: TABLE; Schema: public; Owner: -
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


--
-- Name: COLUMN category_mgmt_hist.category_mgmt_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.category_mgmt_hist.category_mgmt_id IS 'Category mgmt hist id';


--
-- Name: COLUMN category_mgmt_hist.parent_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.category_mgmt_hist.parent_id IS 'Parent category';


--
-- Name: COLUMN category_mgmt_hist.name; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.category_mgmt_hist.name IS 'Category name';


--
-- Name: COLUMN category_mgmt_hist.slug; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.category_mgmt_hist.slug IS 'Category slug';


--
-- Name: COLUMN category_mgmt_hist.description; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.category_mgmt_hist.description IS 'Category description';


--
-- Name: COLUMN category_mgmt_hist.status; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.category_mgmt_hist.status IS 'Category status';


--
-- Name: COLUMN category_mgmt_hist.is_display; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.category_mgmt_hist.is_display IS 'Display category';


--
-- Name: COLUMN category_mgmt_hist.rank_order; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.category_mgmt_hist.rank_order IS 'Category order';


--
-- Name: COLUMN category_mgmt_hist.action; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.category_mgmt_hist.action IS 'Category action';


--
-- Name: COLUMN category_mgmt_hist.author_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.category_mgmt_hist.author_id IS 'Author id';


--
-- Name: COLUMN category_mgmt_hist.created_at; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.category_mgmt_hist.created_at IS 'Created time';


--
-- Name: category_mgmt_hist_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.category_mgmt_hist_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: category_mgmt_hist_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.category_mgmt_hist_id_seq OWNED BY public.category_mgmt_hist.id;


--
-- Name: category_mgmt_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.category_mgmt_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: category_mgmt_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.category_mgmt_id_seq OWNED BY public.category_mgmt.id;


--
-- Name: category_skill_mgmt; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.category_skill_mgmt (
    category_id integer NOT NULL,
    skill_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: department_mst_hist; Type: TABLE; Schema: public; Owner: -
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


--
-- Name: COLUMN department_mst_hist.department_mst_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.department_mst_hist.department_mst_id IS 'Department id';


--
-- Name: COLUMN department_mst_hist.code; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.department_mst_hist.code IS 'Department code';


--
-- Name: COLUMN department_mst_hist.name; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.department_mst_hist.name IS 'Department name';


--
-- Name: COLUMN department_mst_hist.status; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.department_mst_hist.status IS 'Department status';


--
-- Name: COLUMN department_mst_hist.action; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.department_mst_hist.action IS 'Department action';


--
-- Name: COLUMN department_mst_hist.author_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.department_mst_hist.author_id IS 'Author id';


--
-- Name: COLUMN department_mst_hist.created_at; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.department_mst_hist.created_at IS 'Created time';


--
-- Name: department_mst_hist_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.department_mst_hist_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: department_mst_hist_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.department_mst_hist_id_seq OWNED BY public.department_mst_hist.id;


--
-- Name: department_mst_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.department_mst_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: department_mst_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.department_mst_id_seq OWNED BY public.department_mst.id;


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
-- Name: feature_mst_hist; Type: TABLE; Schema: public; Owner: -
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


--
-- Name: COLUMN feature_mst_hist.feature_mst_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.feature_mst_hist.feature_mst_id IS 'Feature id';


--
-- Name: COLUMN feature_mst_hist.name; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.feature_mst_hist.name IS 'Feature name';


--
-- Name: COLUMN feature_mst_hist.group_name; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.feature_mst_hist.group_name IS 'Feature group name';


--
-- Name: COLUMN feature_mst_hist.description; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.feature_mst_hist.description IS 'Feature description';


--
-- Name: COLUMN feature_mst_hist.status; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.feature_mst_hist.status IS 'Feature status';


--
-- Name: COLUMN feature_mst_hist.action; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.feature_mst_hist.action IS 'Feature action';


--
-- Name: COLUMN feature_mst_hist.author_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.feature_mst_hist.author_id IS 'Author id';


--
-- Name: COLUMN feature_mst_hist.created_at; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.feature_mst_hist.created_at IS 'Created time';


--
-- Name: feature_mst_hist_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.feature_mst_hist_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: feature_mst_hist_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.feature_mst_hist_id_seq OWNED BY public.feature_mst_hist.id;


--
-- Name: feature_mst_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.feature_mst_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: feature_mst_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.feature_mst_id_seq OWNED BY public.feature_mst.id;


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
-- Name: language_mst; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.language_mst (
    id integer NOT NULL,
    abbreviation character varying(10) NOT NULL,
    name character varying(30) NOT NULL,
    is_active boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: COLUMN language_mst.abbreviation; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.language_mst.abbreviation IS 'Language abbreviation';


--
-- Name: COLUMN language_mst.name; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.language_mst.name IS 'Language name';


--
-- Name: COLUMN language_mst.is_active; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.language_mst.is_active IS 'Language active';


--
-- Name: language_mst_hist; Type: TABLE; Schema: public; Owner: -
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


--
-- Name: COLUMN language_mst_hist.language_mst_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.language_mst_hist.language_mst_id IS 'Language id';


--
-- Name: COLUMN language_mst_hist.abbreviation; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.language_mst_hist.abbreviation IS 'Language abbreviation';


--
-- Name: COLUMN language_mst_hist.name; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.language_mst_hist.name IS 'Language name';


--
-- Name: COLUMN language_mst_hist.is_active; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.language_mst_hist.is_active IS 'Language active';


--
-- Name: COLUMN language_mst_hist.action; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.language_mst_hist.action IS 'Language action';


--
-- Name: COLUMN language_mst_hist.author_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.language_mst_hist.author_id IS 'Author id';


--
-- Name: COLUMN language_mst_hist.created_at; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.language_mst_hist.created_at IS 'Created time';


--
-- Name: language_mst_hist_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.language_mst_hist_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: language_mst_hist_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.language_mst_hist_id_seq OWNED BY public.language_mst_hist.id;


--
-- Name: language_mst_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.language_mst_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: language_mst_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.language_mst_id_seq OWNED BY public.language_mst.id;


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
-- Name: original_translator_mst; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.original_translator_mst (
    id integer NOT NULL,
    "table" character varying(64) NOT NULL,
    "column" character varying(64) NOT NULL,
    field_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: COLUMN original_translator_mst."table"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.original_translator_mst."table" IS 'Table name';


--
-- Name: COLUMN original_translator_mst."column"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.original_translator_mst."column" IS 'Column name';


--
-- Name: COLUMN original_translator_mst.field_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.original_translator_mst.field_id IS 'Field id';


--
-- Name: original_translator_mst_hist; Type: TABLE; Schema: public; Owner: -
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


--
-- Name: COLUMN original_translator_mst_hist.original_translator_mst_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.original_translator_mst_hist.original_translator_mst_id IS 'Original translator id';


--
-- Name: COLUMN original_translator_mst_hist."table"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.original_translator_mst_hist."table" IS 'Table name';


--
-- Name: COLUMN original_translator_mst_hist."column"; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.original_translator_mst_hist."column" IS 'Column name';


--
-- Name: COLUMN original_translator_mst_hist.field_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.original_translator_mst_hist.field_id IS 'Field id';


--
-- Name: COLUMN original_translator_mst_hist.action; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.original_translator_mst_hist.action IS 'Original translator action';


--
-- Name: COLUMN original_translator_mst_hist.author_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.original_translator_mst_hist.author_id IS 'Author id';


--
-- Name: COLUMN original_translator_mst_hist.created_at; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.original_translator_mst_hist.created_at IS 'Created time';


--
-- Name: original_translator_mst_hist_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.original_translator_mst_hist_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: original_translator_mst_hist_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.original_translator_mst_hist_id_seq OWNED BY public.original_translator_mst_hist.id;


--
-- Name: original_translator_mst_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.original_translator_mst_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: original_translator_mst_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.original_translator_mst_id_seq OWNED BY public.original_translator_mst.id;


--
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


--
-- Name: policy_department_mst_hist; Type: TABLE; Schema: public; Owner: -
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


--
-- Name: COLUMN policy_department_mst_hist.policy_department_mst_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.policy_department_mst_hist.policy_department_mst_id IS 'Policy department id';


--
-- Name: COLUMN policy_department_mst_hist.table_name; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.policy_department_mst_hist.table_name IS 'Policy department';


--
-- Name: COLUMN policy_department_mst_hist.row_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.policy_department_mst_hist.row_id IS 'Row id';


--
-- Name: COLUMN policy_department_mst_hist.action; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.policy_department_mst_hist.action IS 'Policy department action';


--
-- Name: COLUMN policy_department_mst_hist.author_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.policy_department_mst_hist.author_id IS 'Author id';


--
-- Name: COLUMN policy_department_mst_hist.created_at; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.policy_department_mst_hist.created_at IS 'Created time';


--
-- Name: policy_department_mst_hist_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.policy_department_mst_hist_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: policy_department_mst_hist_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.policy_department_mst_hist_id_seq OWNED BY public.policy_department_mst_hist.id;


--
-- Name: policy_department_mst_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.policy_department_mst_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: policy_department_mst_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.policy_department_mst_id_seq OWNED BY public.policy_department_mst.id;


--
-- Name: role_mst_hist; Type: TABLE; Schema: public; Owner: -
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


--
-- Name: COLUMN role_mst_hist.role_mst_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.role_mst_hist.role_mst_id IS 'role_id';


--
-- Name: COLUMN role_mst_hist.name; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.role_mst_hist.name IS 'Role name';


--
-- Name: COLUMN role_mst_hist.permission; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.role_mst_hist.permission IS 'Role description';


--
-- Name: COLUMN role_mst_hist.is_active; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.role_mst_hist.is_active IS 'Role active';


--
-- Name: COLUMN role_mst_hist.action; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.role_mst_hist.action IS 'Role action';


--
-- Name: COLUMN role_mst_hist.author_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.role_mst_hist.author_id IS 'Author id';


--
-- Name: COLUMN role_mst_hist.created_at; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.role_mst_hist.created_at IS 'Created time';


--
-- Name: role_mst_hist_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.role_mst_hist_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: role_mst_hist_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.role_mst_hist_id_seq OWNED BY public.role_mst_hist.id;


--
-- Name: role_mst_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.role_mst_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: role_mst_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.role_mst_id_seq OWNED BY public.role_mst.id;


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
-- Name: setting_link_mgmt; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.setting_link_mgmt (
    id integer NOT NULL,
    key character varying(30) NOT NULL,
    value character varying(100) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: COLUMN setting_link_mgmt.key; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.setting_link_mgmt.key IS 'Setting link key';


--
-- Name: COLUMN setting_link_mgmt.value; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.setting_link_mgmt.value IS 'Setting link value';


--
-- Name: setting_link_mgmt_hist; Type: TABLE; Schema: public; Owner: -
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


--
-- Name: COLUMN setting_link_mgmt_hist.setting_link_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.setting_link_mgmt_hist.setting_link_id IS 'Setting link id';


--
-- Name: COLUMN setting_link_mgmt_hist.key; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.setting_link_mgmt_hist.key IS 'Setting link key';


--
-- Name: COLUMN setting_link_mgmt_hist.value; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.setting_link_mgmt_hist.value IS 'Setting link value';


--
-- Name: COLUMN setting_link_mgmt_hist.action; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.setting_link_mgmt_hist.action IS 'Setting link action';


--
-- Name: COLUMN setting_link_mgmt_hist.author_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.setting_link_mgmt_hist.author_id IS 'Author id';


--
-- Name: COLUMN setting_link_mgmt_hist.created_at; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.setting_link_mgmt_hist.created_at IS 'Created time';


--
-- Name: setting_link_mgmt_hist_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.setting_link_mgmt_hist_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: setting_link_mgmt_hist_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.setting_link_mgmt_hist_id_seq OWNED BY public.setting_link_mgmt_hist.id;


--
-- Name: setting_link_mgmt_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.setting_link_mgmt_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: setting_link_mgmt_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.setting_link_mgmt_id_seq OWNED BY public.setting_link_mgmt.id;


--
-- Name: skill_description_mgmt; Type: TABLE; Schema: public; Owner: -
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
    skill_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: COLUMN skill_description_mgmt.parent_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.skill_description_mgmt.parent_id IS 'Parent skill description';


--
-- Name: COLUMN skill_description_mgmt.title; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.skill_description_mgmt.title IS 'Title skill description';


--
-- Name: COLUMN skill_description_mgmt.summary; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.skill_description_mgmt.summary IS 'Summary skill description';


--
-- Name: COLUMN skill_description_mgmt.article; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.skill_description_mgmt.article IS 'Article skill description';


--
-- Name: COLUMN skill_description_mgmt.status; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.skill_description_mgmt.status IS 'Skill description status';


--
-- Name: COLUMN skill_description_mgmt.is_display; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.skill_description_mgmt.is_display IS 'Display skill description';


--
-- Name: COLUMN skill_description_mgmt.rank_order; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.skill_description_mgmt.rank_order IS 'Skill description order';


--
-- Name: COLUMN skill_description_mgmt.skill_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.skill_description_mgmt.skill_id IS 'Skill id primary key';


--
-- Name: skill_description_mgmt_hist; Type: TABLE; Schema: public; Owner: -
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


--
-- Name: COLUMN skill_description_mgmt_hist.skill_description_mgmt_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.skill_description_mgmt_hist.skill_description_mgmt_id IS 'Skill description mgmt hist id';


--
-- Name: COLUMN skill_description_mgmt_hist.parent_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.skill_description_mgmt_hist.parent_id IS 'Parent skill description';


--
-- Name: COLUMN skill_description_mgmt_hist.title; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.skill_description_mgmt_hist.title IS 'Title skill description';


--
-- Name: COLUMN skill_description_mgmt_hist.summary; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.skill_description_mgmt_hist.summary IS 'Summary skill description';


--
-- Name: COLUMN skill_description_mgmt_hist.article; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.skill_description_mgmt_hist.article IS 'Article skill description';


--
-- Name: COLUMN skill_description_mgmt_hist.status; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.skill_description_mgmt_hist.status IS 'Skill description status';


--
-- Name: COLUMN skill_description_mgmt_hist.is_display; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.skill_description_mgmt_hist.is_display IS 'Display skill description';


--
-- Name: COLUMN skill_description_mgmt_hist.rank_order; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.skill_description_mgmt_hist.rank_order IS 'Skill description order';


--
-- Name: COLUMN skill_description_mgmt_hist.skill_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.skill_description_mgmt_hist.skill_id IS 'Skill id primary key';


--
-- Name: COLUMN skill_description_mgmt_hist.action; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.skill_description_mgmt_hist.action IS 'Skill description action';


--
-- Name: COLUMN skill_description_mgmt_hist.author_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.skill_description_mgmt_hist.author_id IS 'Author id';


--
-- Name: COLUMN skill_description_mgmt_hist.created_at; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.skill_description_mgmt_hist.created_at IS 'Created time';


--
-- Name: skill_description_mgmt_hist_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.skill_description_mgmt_hist_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: skill_description_mgmt_hist_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.skill_description_mgmt_hist_id_seq OWNED BY public.skill_description_mgmt_hist.id;


--
-- Name: skill_description_mgmt_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.skill_description_mgmt_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: skill_description_mgmt_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.skill_description_mgmt_id_seq OWNED BY public.skill_description_mgmt.id;


--
-- Name: skill_mgmt; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.skill_mgmt (
    id integer NOT NULL,
    parent_id integer DEFAULT 0 NOT NULL,
    name character varying(50) NOT NULL,
    slug character varying(50) NOT NULL,
    status smallint DEFAULT '0'::smallint NOT NULL,
    is_display boolean DEFAULT false NOT NULL,
    rank_order smallint DEFAULT '0'::smallint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: COLUMN skill_mgmt.parent_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.skill_mgmt.parent_id IS 'Parent skill';


--
-- Name: COLUMN skill_mgmt.name; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.skill_mgmt.name IS 'Skill name';


--
-- Name: COLUMN skill_mgmt.slug; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.skill_mgmt.slug IS 'Skill slug';


--
-- Name: COLUMN skill_mgmt.status; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.skill_mgmt.status IS 'Skill status';


--
-- Name: COLUMN skill_mgmt.is_display; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.skill_mgmt.is_display IS 'Display skill';


--
-- Name: COLUMN skill_mgmt.rank_order; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.skill_mgmt.rank_order IS 'Skill order';


--
-- Name: skill_mgmt_hist; Type: TABLE; Schema: public; Owner: -
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


--
-- Name: COLUMN skill_mgmt_hist.skill_mgmt_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.skill_mgmt_hist.skill_mgmt_id IS 'Skill id';


--
-- Name: COLUMN skill_mgmt_hist.parent_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.skill_mgmt_hist.parent_id IS 'Parent skill';


--
-- Name: COLUMN skill_mgmt_hist.name; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.skill_mgmt_hist.name IS 'Skill name';


--
-- Name: COLUMN skill_mgmt_hist.slug; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.skill_mgmt_hist.slug IS 'Skill slug';


--
-- Name: COLUMN skill_mgmt_hist.status; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.skill_mgmt_hist.status IS 'Skill status';


--
-- Name: COLUMN skill_mgmt_hist.is_display; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.skill_mgmt_hist.is_display IS 'Display skill';


--
-- Name: COLUMN skill_mgmt_hist.rank_order; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.skill_mgmt_hist.rank_order IS 'Skill order';


--
-- Name: COLUMN skill_mgmt_hist.action; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.skill_mgmt_hist.action IS 'Skill action';


--
-- Name: COLUMN skill_mgmt_hist.author_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.skill_mgmt_hist.author_id IS 'Author id';


--
-- Name: COLUMN skill_mgmt_hist.created_at; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.skill_mgmt_hist.created_at IS 'Created time';


--
-- Name: skill_mgmt_hist_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.skill_mgmt_hist_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: skill_mgmt_hist_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.skill_mgmt_hist_id_seq OWNED BY public.skill_mgmt_hist.id;


--
-- Name: skill_mgmt_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.skill_mgmt_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: skill_mgmt_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.skill_mgmt_id_seq OWNED BY public.skill_mgmt.id;


--
-- Name: slider_mgmt; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.slider_mgmt (
    id integer NOT NULL,
    title character varying(50) NOT NULL,
    slug character varying(50) NOT NULL,
    link character varying(100) NOT NULL,
    image character varying(100) NOT NULL,
    status smallint DEFAULT '0'::smallint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: COLUMN slider_mgmt.title; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.slider_mgmt.title IS 'Slider title';


--
-- Name: COLUMN slider_mgmt.slug; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.slider_mgmt.slug IS 'Slider slug';


--
-- Name: COLUMN slider_mgmt.link; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.slider_mgmt.link IS 'Slider path image';


--
-- Name: COLUMN slider_mgmt.image; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.slider_mgmt.image IS 'Slider image name';


--
-- Name: COLUMN slider_mgmt.status; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.slider_mgmt.status IS 'Slider image';


--
-- Name: slider_mgmt_hist; Type: TABLE; Schema: public; Owner: -
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


--
-- Name: COLUMN slider_mgmt_hist.slider_mgmt_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.slider_mgmt_hist.slider_mgmt_id IS 'Slider id';


--
-- Name: COLUMN slider_mgmt_hist.title; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.slider_mgmt_hist.title IS 'Slider title';


--
-- Name: COLUMN slider_mgmt_hist.slug; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.slider_mgmt_hist.slug IS 'Slider slug';


--
-- Name: COLUMN slider_mgmt_hist.link; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.slider_mgmt_hist.link IS 'Slider path image';


--
-- Name: COLUMN slider_mgmt_hist.image; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.slider_mgmt_hist.image IS 'Slider image name';


--
-- Name: COLUMN slider_mgmt_hist.status; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.slider_mgmt_hist.status IS 'Slider image';


--
-- Name: COLUMN slider_mgmt_hist.action; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.slider_mgmt_hist.action IS 'Slider action';


--
-- Name: COLUMN slider_mgmt_hist.author_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.slider_mgmt_hist.author_id IS 'Author id';


--
-- Name: COLUMN slider_mgmt_hist.created_at; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.slider_mgmt_hist.created_at IS 'Created time';


--
-- Name: slider_mgmt_hist_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.slider_mgmt_hist_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: slider_mgmt_hist_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.slider_mgmt_hist_id_seq OWNED BY public.slider_mgmt_hist.id;


--
-- Name: slider_mgmt_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.slider_mgmt_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: slider_mgmt_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.slider_mgmt_id_seq OWNED BY public.slider_mgmt.id;


--
-- Name: social_mgmt; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.social_mgmt (
    id integer NOT NULL,
    name character varying(30) NOT NULL,
    url character varying(100) NOT NULL,
    icon character varying(30) NOT NULL,
    description character varying(100) NOT NULL,
    status smallint DEFAULT '0'::smallint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: COLUMN social_mgmt.name; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.social_mgmt.name IS 'Social name';


--
-- Name: COLUMN social_mgmt.url; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.social_mgmt.url IS 'Social url';


--
-- Name: COLUMN social_mgmt.icon; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.social_mgmt.icon IS 'Social icon name';


--
-- Name: COLUMN social_mgmt.description; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.social_mgmt.description IS 'Social description';


--
-- Name: COLUMN social_mgmt.status; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.social_mgmt.status IS 'Social status';


--
-- Name: social_mgmt_hist; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.social_mgmt_hist (
    id integer NOT NULL,
    social_mgmt_id integer NOT NULL,
    name character varying(30),
    url character varying(100),
    icon character varying(30),
    description character varying(100),
    status smallint,
    action smallint NOT NULL,
    author_id integer NOT NULL,
    created_at timestamp(0) without time zone NOT NULL
);


--
-- Name: COLUMN social_mgmt_hist.social_mgmt_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.social_mgmt_hist.social_mgmt_id IS 'Social mgmt hist id';


--
-- Name: COLUMN social_mgmt_hist.name; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.social_mgmt_hist.name IS 'Social name';


--
-- Name: COLUMN social_mgmt_hist.url; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.social_mgmt_hist.url IS 'Social url';


--
-- Name: COLUMN social_mgmt_hist.icon; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.social_mgmt_hist.icon IS 'Social icon name';


--
-- Name: COLUMN social_mgmt_hist.description; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.social_mgmt_hist.description IS 'Social description';


--
-- Name: COLUMN social_mgmt_hist.status; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.social_mgmt_hist.status IS 'Social status';


--
-- Name: COLUMN social_mgmt_hist.action; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.social_mgmt_hist.action IS 'Social action';


--
-- Name: COLUMN social_mgmt_hist.author_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.social_mgmt_hist.author_id IS 'Author id';


--
-- Name: COLUMN social_mgmt_hist.created_at; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.social_mgmt_hist.created_at IS 'Created time';


--
-- Name: social_mgmt_hist_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.social_mgmt_hist_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: social_mgmt_hist_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.social_mgmt_hist_id_seq OWNED BY public.social_mgmt_hist.id;


--
-- Name: social_mgmt_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.social_mgmt_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: social_mgmt_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.social_mgmt_id_seq OWNED BY public.social_mgmt.id;


--
-- Name: translation_language_mst; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.translation_language_mst (
    translation_id integer NOT NULL,
    language_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: translation_mst; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.translation_mst (
    id integer NOT NULL,
    original_translator_id integer NOT NULL,
    translate text NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: COLUMN translation_mst.original_translator_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.translation_mst.original_translator_id IS 'Original translator id';


--
-- Name: COLUMN translation_mst.translate; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.translation_mst.translate IS 'Translate text';


--
-- Name: translation_mst_hist; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.translation_mst_hist (
    id integer NOT NULL,
    translation_mst_id integer NOT NULL,
    original_translator_id integer,
    translate text,
    action smallint NOT NULL,
    author_id integer NOT NULL,
    created_at timestamp(0) without time zone NOT NULL
);


--
-- Name: COLUMN translation_mst_hist.translation_mst_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.translation_mst_hist.translation_mst_id IS 'Translation id';


--
-- Name: COLUMN translation_mst_hist.original_translator_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.translation_mst_hist.original_translator_id IS 'Original translator id';


--
-- Name: COLUMN translation_mst_hist.translate; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.translation_mst_hist.translate IS 'Translate text';


--
-- Name: COLUMN translation_mst_hist.action; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.translation_mst_hist.action IS 'Translation action';


--
-- Name: COLUMN translation_mst_hist.author_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.translation_mst_hist.author_id IS 'Author id';


--
-- Name: COLUMN translation_mst_hist.created_at; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.translation_mst_hist.created_at IS 'Created time';


--
-- Name: translation_mst_hist_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.translation_mst_hist_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: translation_mst_hist_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.translation_mst_hist_id_seq OWNED BY public.translation_mst_hist.id;


--
-- Name: translation_mst_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.translation_mst_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: translation_mst_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.translation_mst_id_seq OWNED BY public.translation_mst.id;


--
-- Name: user_mgmt_hist; Type: TABLE; Schema: public; Owner: -
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


--
-- Name: COLUMN user_mgmt_hist.user_mgmt_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.user_mgmt_hist.user_mgmt_id IS 'User mgmt hist id';


--
-- Name: COLUMN user_mgmt_hist.email; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.user_mgmt_hist.email IS 'User email';


--
-- Name: COLUMN user_mgmt_hist.user_name; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.user_mgmt_hist.user_name IS 'User name';


--
-- Name: COLUMN user_mgmt_hist.password; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.user_mgmt_hist.password IS 'User password';


--
-- Name: COLUMN user_mgmt_hist.first_name; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.user_mgmt_hist.first_name IS 'First name';


--
-- Name: COLUMN user_mgmt_hist.last_name; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.user_mgmt_hist.last_name IS 'Last name';


--
-- Name: COLUMN user_mgmt_hist.address; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.user_mgmt_hist.address IS 'User address';


--
-- Name: COLUMN user_mgmt_hist.phone_number; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.user_mgmt_hist.phone_number IS 'User phone number';


--
-- Name: COLUMN user_mgmt_hist.birth; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.user_mgmt_hist.birth IS 'User birth';


--
-- Name: COLUMN user_mgmt_hist.gender; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.user_mgmt_hist.gender IS 'User gender';


--
-- Name: COLUMN user_mgmt_hist.status; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.user_mgmt_hist.status IS 'User status';


--
-- Name: COLUMN user_mgmt_hist.is_active; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.user_mgmt_hist.is_active IS 'User active';


--
-- Name: COLUMN user_mgmt_hist.avatar; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.user_mgmt_hist.avatar IS 'User avatar name';


--
-- Name: COLUMN user_mgmt_hist.email_verified_at; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.user_mgmt_hist.email_verified_at IS 'Verified email time';


--
-- Name: COLUMN user_mgmt_hist.remember_token; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.user_mgmt_hist.remember_token IS 'Remember token';


--
-- Name: COLUMN user_mgmt_hist.action; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.user_mgmt_hist.action IS 'User action';


--
-- Name: COLUMN user_mgmt_hist.author_id; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.user_mgmt_hist.author_id IS 'Author id';


--
-- Name: COLUMN user_mgmt_hist.created_at; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.user_mgmt_hist.created_at IS 'Created time';


--
-- Name: user_mgmt_hist_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.user_mgmt_hist_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: user_mgmt_hist_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.user_mgmt_hist_id_seq OWNED BY public.user_mgmt_hist.id;


--
-- Name: user_mst; Type: TABLE; Schema: public; Owner: -
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
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: COLUMN user_mst.email; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.user_mst.email IS 'User email';


--
-- Name: COLUMN user_mst.user_name; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.user_mst.user_name IS 'User name';


--
-- Name: COLUMN user_mst.password; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.user_mst.password IS 'User password';


--
-- Name: COLUMN user_mst.first_name; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.user_mst.first_name IS 'First name';


--
-- Name: COLUMN user_mst.last_name; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.user_mst.last_name IS 'Last name';


--
-- Name: COLUMN user_mst.address; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.user_mst.address IS 'User address';


--
-- Name: COLUMN user_mst.phone_number; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.user_mst.phone_number IS 'User phone number';


--
-- Name: COLUMN user_mst.birth; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.user_mst.birth IS 'User birth';


--
-- Name: COLUMN user_mst.gender; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.user_mst.gender IS 'User gender';


--
-- Name: COLUMN user_mst.status; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.user_mst.status IS 'User status';


--
-- Name: COLUMN user_mst.is_active; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.user_mst.is_active IS 'User active';


--
-- Name: COLUMN user_mst.avatar; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.user_mst.avatar IS 'User avatar name';


--
-- Name: COLUMN user_mst.email_verified_at; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.user_mst.email_verified_at IS 'Verified email time';


--
-- Name: user_mst_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.user_mst_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: user_mst_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.user_mst_id_seq OWNED BY public.user_mst.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: -
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
-- Name: admin_mst id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.admin_mst ALTER COLUMN id SET DEFAULT nextval('public.admin_mst_id_seq'::regclass);


--
-- Name: admin_mst_hist id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.admin_mst_hist ALTER COLUMN id SET DEFAULT nextval('public.admin_mst_hist_id_seq'::regclass);


--
-- Name: api_mst id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.api_mst ALTER COLUMN id SET DEFAULT nextval('public.api_mst_id_seq'::regclass);


--
-- Name: api_mst_hist id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.api_mst_hist ALTER COLUMN id SET DEFAULT nextval('public.api_mst_hist_id_seq'::regclass);


--
-- Name: banner_mgmt id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.banner_mgmt ALTER COLUMN id SET DEFAULT nextval('public.banner_mgmt_id_seq'::regclass);


--
-- Name: banner_mgmt_hist id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.banner_mgmt_hist ALTER COLUMN id SET DEFAULT nextval('public.banner_mgmt_hist_id_seq'::regclass);


--
-- Name: category_mgmt id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.category_mgmt ALTER COLUMN id SET DEFAULT nextval('public.category_mgmt_id_seq'::regclass);


--
-- Name: category_mgmt_hist id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.category_mgmt_hist ALTER COLUMN id SET DEFAULT nextval('public.category_mgmt_hist_id_seq'::regclass);


--
-- Name: department_mst id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.department_mst ALTER COLUMN id SET DEFAULT nextval('public.department_mst_id_seq'::regclass);


--
-- Name: department_mst_hist id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.department_mst_hist ALTER COLUMN id SET DEFAULT nextval('public.department_mst_hist_id_seq'::regclass);


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: feature_mst id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.feature_mst ALTER COLUMN id SET DEFAULT nextval('public.feature_mst_id_seq'::regclass);


--
-- Name: feature_mst_hist id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.feature_mst_hist ALTER COLUMN id SET DEFAULT nextval('public.feature_mst_hist_id_seq'::regclass);


--
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- Name: language_mst id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.language_mst ALTER COLUMN id SET DEFAULT nextval('public.language_mst_id_seq'::regclass);


--
-- Name: language_mst_hist id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.language_mst_hist ALTER COLUMN id SET DEFAULT nextval('public.language_mst_hist_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: original_translator_mst id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.original_translator_mst ALTER COLUMN id SET DEFAULT nextval('public.original_translator_mst_id_seq'::regclass);


--
-- Name: original_translator_mst_hist id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.original_translator_mst_hist ALTER COLUMN id SET DEFAULT nextval('public.original_translator_mst_hist_id_seq'::regclass);


--
-- Name: policy_department_mst id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.policy_department_mst ALTER COLUMN id SET DEFAULT nextval('public.policy_department_mst_id_seq'::regclass);


--
-- Name: policy_department_mst_hist id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.policy_department_mst_hist ALTER COLUMN id SET DEFAULT nextval('public.policy_department_mst_hist_id_seq'::regclass);


--
-- Name: role_mst id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.role_mst ALTER COLUMN id SET DEFAULT nextval('public.role_mst_id_seq'::regclass);


--
-- Name: role_mst_hist id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.role_mst_hist ALTER COLUMN id SET DEFAULT nextval('public.role_mst_hist_id_seq'::regclass);


--
-- Name: setting_link_mgmt id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.setting_link_mgmt ALTER COLUMN id SET DEFAULT nextval('public.setting_link_mgmt_id_seq'::regclass);


--
-- Name: setting_link_mgmt_hist id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.setting_link_mgmt_hist ALTER COLUMN id SET DEFAULT nextval('public.setting_link_mgmt_hist_id_seq'::regclass);


--
-- Name: skill_description_mgmt id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.skill_description_mgmt ALTER COLUMN id SET DEFAULT nextval('public.skill_description_mgmt_id_seq'::regclass);


--
-- Name: skill_description_mgmt_hist id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.skill_description_mgmt_hist ALTER COLUMN id SET DEFAULT nextval('public.skill_description_mgmt_hist_id_seq'::regclass);


--
-- Name: skill_mgmt id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.skill_mgmt ALTER COLUMN id SET DEFAULT nextval('public.skill_mgmt_id_seq'::regclass);


--
-- Name: skill_mgmt_hist id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.skill_mgmt_hist ALTER COLUMN id SET DEFAULT nextval('public.skill_mgmt_hist_id_seq'::regclass);


--
-- Name: slider_mgmt id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.slider_mgmt ALTER COLUMN id SET DEFAULT nextval('public.slider_mgmt_id_seq'::regclass);


--
-- Name: slider_mgmt_hist id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.slider_mgmt_hist ALTER COLUMN id SET DEFAULT nextval('public.slider_mgmt_hist_id_seq'::regclass);


--
-- Name: social_mgmt id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.social_mgmt ALTER COLUMN id SET DEFAULT nextval('public.social_mgmt_id_seq'::regclass);


--
-- Name: social_mgmt_hist id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.social_mgmt_hist ALTER COLUMN id SET DEFAULT nextval('public.social_mgmt_hist_id_seq'::regclass);


--
-- Name: translation_mst id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.translation_mst ALTER COLUMN id SET DEFAULT nextval('public.translation_mst_id_seq'::regclass);


--
-- Name: translation_mst_hist id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.translation_mst_hist ALTER COLUMN id SET DEFAULT nextval('public.translation_mst_hist_id_seq'::regclass);


--
-- Name: user_mgmt_hist id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.user_mgmt_hist ALTER COLUMN id SET DEFAULT nextval('public.user_mgmt_hist_id_seq'::regclass);


--
-- Name: user_mst id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.user_mst ALTER COLUMN id SET DEFAULT nextval('public.user_mst_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Name: admin_department_mst admin_department_mst_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.admin_department_mst
    ADD CONSTRAINT admin_department_mst_pkey PRIMARY KEY (admin_id, department_id);


--
-- Name: admin_mst admin_mst_email_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.admin_mst
    ADD CONSTRAINT admin_mst_email_unique UNIQUE (email);


--
-- Name: admin_mst_hist admin_mst_hist_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.admin_mst_hist
    ADD CONSTRAINT admin_mst_hist_pkey PRIMARY KEY (id);


--
-- Name: admin_mst admin_mst_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.admin_mst
    ADD CONSTRAINT admin_mst_pkey PRIMARY KEY (id);


--
-- Name: admin_mst admin_mst_user_name_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.admin_mst
    ADD CONSTRAINT admin_mst_user_name_unique UNIQUE (user_name);


--
-- Name: admin_role_mst admin_role_mst_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.admin_role_mst
    ADD CONSTRAINT admin_role_mst_pkey PRIMARY KEY (admin_id, role_id);


--
-- Name: api_mst_hist api_mst_hist_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.api_mst_hist
    ADD CONSTRAINT api_mst_hist_pkey PRIMARY KEY (id);


--
-- Name: api_mst api_mst_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.api_mst
    ADD CONSTRAINT api_mst_pkey PRIMARY KEY (id);


--
-- Name: api_role_mst api_role_mst_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.api_role_mst
    ADD CONSTRAINT api_role_mst_pkey PRIMARY KEY (api_id, role_id);


--
-- Name: banner_mgmt_hist banner_mgmt_hist_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.banner_mgmt_hist
    ADD CONSTRAINT banner_mgmt_hist_pkey PRIMARY KEY (id);


--
-- Name: banner_mgmt banner_mgmt_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.banner_mgmt
    ADD CONSTRAINT banner_mgmt_pkey PRIMARY KEY (id);


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
-- Name: category_mgmt_hist category_mgmt_hist_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.category_mgmt_hist
    ADD CONSTRAINT category_mgmt_hist_pkey PRIMARY KEY (id);


--
-- Name: category_mgmt category_mgmt_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.category_mgmt
    ADD CONSTRAINT category_mgmt_pkey PRIMARY KEY (id);


--
-- Name: category_skill_mgmt category_skill_mgmt_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.category_skill_mgmt
    ADD CONSTRAINT category_skill_mgmt_pkey PRIMARY KEY (category_id, skill_id);


--
-- Name: department_management_mst department_management_mst_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.department_management_mst
    ADD CONSTRAINT department_management_mst_pkey PRIMARY KEY (department_id, policy_department_id);


--
-- Name: department_mst_hist department_mst_hist_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.department_mst_hist
    ADD CONSTRAINT department_mst_hist_pkey PRIMARY KEY (id);


--
-- Name: department_mst department_mst_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.department_mst
    ADD CONSTRAINT department_mst_pkey PRIMARY KEY (id);


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
-- Name: feature_mst_hist feature_mst_hist_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.feature_mst_hist
    ADD CONSTRAINT feature_mst_hist_pkey PRIMARY KEY (id);


--
-- Name: feature_mst feature_mst_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.feature_mst
    ADD CONSTRAINT feature_mst_pkey PRIMARY KEY (id);


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
-- Name: language_mst_hist language_mst_hist_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.language_mst_hist
    ADD CONSTRAINT language_mst_hist_pkey PRIMARY KEY (id);


--
-- Name: language_mst language_mst_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.language_mst
    ADD CONSTRAINT language_mst_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: original_translator_mst_hist original_translator_mst_hist_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.original_translator_mst_hist
    ADD CONSTRAINT original_translator_mst_hist_pkey PRIMARY KEY (id);


--
-- Name: original_translator_mst original_translator_mst_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.original_translator_mst
    ADD CONSTRAINT original_translator_mst_pkey PRIMARY KEY (id);


--
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- Name: policy_department_mst_hist policy_department_mst_hist_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.policy_department_mst_hist
    ADD CONSTRAINT policy_department_mst_hist_pkey PRIMARY KEY (id);


--
-- Name: policy_department_mst policy_department_mst_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.policy_department_mst
    ADD CONSTRAINT policy_department_mst_pkey PRIMARY KEY (id);


--
-- Name: role_mst_hist role_mst_hist_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.role_mst_hist
    ADD CONSTRAINT role_mst_hist_pkey PRIMARY KEY (id);


--
-- Name: role_mst role_mst_name_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.role_mst
    ADD CONSTRAINT role_mst_name_unique UNIQUE (name);


--
-- Name: role_mst role_mst_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.role_mst
    ADD CONSTRAINT role_mst_pkey PRIMARY KEY (id);


--
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- Name: setting_link_mgmt_hist setting_link_mgmt_hist_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.setting_link_mgmt_hist
    ADD CONSTRAINT setting_link_mgmt_hist_pkey PRIMARY KEY (id);


--
-- Name: setting_link_mgmt setting_link_mgmt_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.setting_link_mgmt
    ADD CONSTRAINT setting_link_mgmt_pkey PRIMARY KEY (id);


--
-- Name: skill_description_mgmt_hist skill_description_mgmt_hist_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.skill_description_mgmt_hist
    ADD CONSTRAINT skill_description_mgmt_hist_pkey PRIMARY KEY (id);


--
-- Name: skill_description_mgmt skill_description_mgmt_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.skill_description_mgmt
    ADD CONSTRAINT skill_description_mgmt_pkey PRIMARY KEY (id);


--
-- Name: skill_mgmt_hist skill_mgmt_hist_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.skill_mgmt_hist
    ADD CONSTRAINT skill_mgmt_hist_pkey PRIMARY KEY (id);


--
-- Name: skill_mgmt skill_mgmt_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.skill_mgmt
    ADD CONSTRAINT skill_mgmt_pkey PRIMARY KEY (id);


--
-- Name: slider_mgmt_hist slider_mgmt_hist_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.slider_mgmt_hist
    ADD CONSTRAINT slider_mgmt_hist_pkey PRIMARY KEY (id);


--
-- Name: slider_mgmt slider_mgmt_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.slider_mgmt
    ADD CONSTRAINT slider_mgmt_pkey PRIMARY KEY (id);


--
-- Name: social_mgmt_hist social_mgmt_hist_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.social_mgmt_hist
    ADD CONSTRAINT social_mgmt_hist_pkey PRIMARY KEY (id);


--
-- Name: social_mgmt social_mgmt_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.social_mgmt
    ADD CONSTRAINT social_mgmt_pkey PRIMARY KEY (id);


--
-- Name: translation_language_mst translation_language_mst_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.translation_language_mst
    ADD CONSTRAINT translation_language_mst_pkey PRIMARY KEY (translation_id, language_id);


--
-- Name: translation_mst_hist translation_mst_hist_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.translation_mst_hist
    ADD CONSTRAINT translation_mst_hist_pkey PRIMARY KEY (id);


--
-- Name: translation_mst translation_mst_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.translation_mst
    ADD CONSTRAINT translation_mst_pkey PRIMARY KEY (id);


--
-- Name: user_mgmt_hist user_mgmt_hist_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.user_mgmt_hist
    ADD CONSTRAINT user_mgmt_hist_pkey PRIMARY KEY (id);


--
-- Name: user_mst user_mst_email_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.user_mst
    ADD CONSTRAINT user_mst_email_unique UNIQUE (email);


--
-- Name: user_mst user_mst_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.user_mst
    ADD CONSTRAINT user_mst_pkey PRIMARY KEY (id);


--
-- Name: user_mst user_mst_user_name_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.user_mst
    ADD CONSTRAINT user_mst_user_name_unique UNIQUE (user_name);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


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
-- Name: api_mst after_api_insert; Type: TRIGGER; Schema: public; Owner: -
--

CREATE TRIGGER after_api_insert AFTER INSERT ON public.api_mst FOR EACH ROW EXECUTE FUNCTION public.insert_into_api_role_from_api();


--
-- Name: policy_department_mst after_policy_department_insert; Type: TRIGGER; Schema: public; Owner: -
--

CREATE TRIGGER after_policy_department_insert AFTER INSERT ON public.policy_department_mst FOR EACH ROW EXECUTE FUNCTION public.insert_into_department_management();


--
-- PostgreSQL database dump complete
--

\unrestrict H3X1v26r7oBW8Sl54yPNMbTGg93bxVkdUSWw7d1ivVY26DwpyHyZyzdHS2HLoWd

--
-- PostgreSQL database dump
--

\restrict eS7ihaWz03WvW1ScuzC5cFpbzX2eVeP4ELI0Li5ykDLEma4LfsNgozjbOIdIwFW

-- Dumped from database version 15.14
-- Dumped by pg_dump version 17.6

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
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: -
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
16	2024_06_09_024200_create_language_mst_table	2
17	2024_06_09_024216_create_translation_language_mst_table	2
18	2024_06_09_024226_create_user_mst_table	2
19	2024_06_09_023253_create_category_mgmt_table	3
20	2024_06_09_023335_create_skill_mgmt_table	3
21	2024_06_09_023354_create_category_skill_mgmt_table	3
22	2024_06_09_023425_create_skill_description_mgmt_table	3
23	2024_06_09_023447_create_slider_mgmt_table	3
24	2024_06_09_023455_create_banner_mgmt_table	3
25	2024_06_09_023505_create_social_mgmt_table	3
26	2024_06_09_023521_create_setting_link_mgmt_table	3
27	2024_06_09_024447_create_admin_mst_hist_table	4
28	2024_06_09_024510_create_department_mst_hist_table	4
29	2024_06_09_024530_create_policy_department_mst_hist_table	4
30	2024_06_09_024539_create_role_mst_hist_table	4
31	2024_06_09_024557_create_feature_mst_hist_table	4
32	2024_06_09_024608_create_api_mst_hist_table	4
33	2024_06_09_024636_create_original_translator_mst_hist_table	4
34	2024_06_09_024810_create_translation_mst_hist_table	4
35	2024_06_09_024823_create_language_mst_hist_table	4
36	2024_06_09_024258_create_category_mgmt_hist_table	5
37	2024_06_09_024306_create_skill_mgmt_hist_table	5
38	2024_06_09_024320_create_skill_description_mgmt_hist_table	5
39	2024_06_09_024343_create_slider_mgmt_hist_table	5
40	2024_06_09_024352_create_banner_mgmt_hist_table	5
41	2024_06_09_024408_create_social_mgmt_hist_table	5
42	2024_06_09_024422_create_setting_link_mgmt_hist_table	5
43	2024_06_09_024830_create_user_mgmt_hist_table	5
44	2024_08_05_041318_create_after_api_insert	6
45	2024_08_05_041452_create_after_policy_department_insert	6
46	2024_08_02_100908_create_admin_permission_view	7
47	2024_08_02_102814_create_admin_policy_view	7
\.


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.migrations_id_seq', 47, true);


--
-- PostgreSQL database dump complete
--

\unrestrict eS7ihaWz03WvW1ScuzC5cFpbzX2eVeP4ELI0Li5ykDLEma4LfsNgozjbOIdIwFW

