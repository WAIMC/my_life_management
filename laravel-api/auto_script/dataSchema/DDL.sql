CREATE TABLE admin_department_mst (
    admin_mst_id integer NOT NULL,
    department_mst_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
CREATE TABLE admin_mst (
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
CREATE TABLE admin_mst_hist (
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
CREATE TABLE admin_role_mst (
    admin_mst_id integer NOT NULL,
    role_mst_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
CREATE TABLE api_mst (
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
CREATE TABLE api_role_mst (
    api_mst_id integer NOT NULL,
    role_mst_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
CREATE TABLE feature_mst (
    id integer NOT NULL,
    name character varying(50) NOT NULL,
    group_name character varying(50) NOT NULL,
    description character varying(100) NOT NULL,
    status smallint DEFAULT '0'::smallint NOT NULL,
    is_delete boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
CREATE TABLE role_mst (
    id integer NOT NULL,
    name character varying(30) NOT NULL,
    permission character varying(50) NOT NULL,
    is_active boolean DEFAULT false NOT NULL,
    is_delete boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
CREATE TABLE department_management_mst (
    department_mst_id integer NOT NULL,
    policy_department_mst_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
CREATE TABLE department_mst (
    id integer NOT NULL,
    code character varying(50) NOT NULL,
    name character varying(50) NOT NULL,
    status smallint DEFAULT '0'::smallint NOT NULL,
    is_delete boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
CREATE TABLE policy_department_mst (
    id integer NOT NULL,
    table_name character varying(20) NOT NULL,
    row_id integer NOT NULL,
    is_delete boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
CREATE TABLE api_mst_hist (
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
CREATE TABLE banner_mgmt (
    id integer NOT NULL,
    title character varying(50) NOT NULL,
    slug character varying(50) NOT NULL,
    description character varying(255) NOT NULL,
    link character varying(100) NOT NULL,
    image character varying(100) NOT NULL,
    position character varying(50) NOT NULL,
    status smallint DEFAULT '0'::smallint NOT NULL,
    is_delete boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
CREATE TABLE banner_mgmt_hist (
    id integer NOT NULL,
    banner_mgmt_id integer NOT NULL,
    title character varying(50),
    slug character varying(50),
    description character varying(255),
    link character varying(100),
    image character varying(100),
    position character varying(50),
    status smallint,
    action smallint NOT NULL,
    author_id integer NOT NULL,
    created_at timestamp(0) without time zone NOT NULL
);
CREATE TABLE category_mgmt (
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
CREATE TABLE category_mgmt_hist (
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
CREATE TABLE category_skill_mgmt (
    category_mgmt_id integer NOT NULL,
    skill_mgmt_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
CREATE TABLE department_mst_hist (
    id integer NOT NULL,
    department_mst_id integer NOT NULL,
    code character varying(50),
    name character varying(50),
    status smallint,
    action smallint NOT NULL,
    author_id integer NOT NULL,
    created_at timestamp(0) without time zone NOT NULL
);
CREATE TABLE feature_mst_hist (
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
CREATE TABLE language_mst (
    id integer NOT NULL,
    abbreviation character varying(10) NOT NULL,
    name character varying(30) NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    is_delete boolean DEFAULT false NOT NULL,
    created_at character varying(255),
    updated_at character varying(255)
);
CREATE TABLE language_mst_hist (
    id integer NOT NULL,
    language_mst_id integer NOT NULL,
    abbreviation character varying(10),
    name character varying(30),
    is_active boolean,
    action smallint NOT NULL,
    author_id integer NOT NULL,
    created_at timestamp(0) without time zone NOT NULL
);
CREATE TABLE original_translator_mst (
    id integer NOT NULL,
    table character varying(64) NOT NULL,
    column character varying(64) NOT NULL,
    field_id integer NOT NULL,
    is_delete boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
CREATE TABLE original_translator_mst_hist (
    id integer NOT NULL,
    original_translator_mst_id integer NOT NULL,
    table character varying(64),
    column character varying(64),
    field_id integer,
    action smallint NOT NULL,
    author_id integer NOT NULL,
    created_at timestamp(0) without time zone NOT NULL
);
CREATE TABLE policy_department_mst_hist (
    id integer NOT NULL,
    policy_department_mst_id integer NOT NULL,
    table_name integer,
    row_id integer,
    action smallint NOT NULL,
    author_id integer NOT NULL,
    created_at timestamp(0) without time zone NOT NULL
);
CREATE TABLE role_mst_hist (
    id integer NOT NULL,
    role_mst_id integer NOT NULL,
    name character varying(30),
    permission character varying(50),
    is_active boolean,
    action smallint NOT NULL,
    author_id integer NOT NULL,
    created_at timestamp(0) without time zone NOT NULL
);
CREATE TABLE setting_link_mgmt (
    id integer NOT NULL,
    key character varying(30) NOT NULL,
    value character varying(100) NOT NULL,
    is_delete boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
CREATE TABLE setting_link_mgmt_hist (
    id integer NOT NULL,
    setting_link_id integer NOT NULL,
    key character varying(30),
    value character varying(100),
    action smallint NOT NULL,
    author_id integer NOT NULL,
    created_at timestamp(0) without time zone NOT NULL
);
CREATE TABLE skill_description_mgmt (
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
CREATE TABLE skill_description_mgmt_hist (
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
CREATE TABLE skill_mgmt (
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
CREATE TABLE skill_mgmt_hist (
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
CREATE TABLE slider_mgmt (
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
CREATE TABLE slider_mgmt_hist (
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
CREATE TABLE social_mgmt (
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
CREATE TABLE social_mgmt_hist (
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
CREATE TABLE translation_language_mst (
    translation_mst_id integer NOT NULL,
    language_mst_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
CREATE TABLE translation_mst (
    id integer NOT NULL,
    language_id integer NOT NULL,
    original_id integer NOT NULL,
    value character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);
CREATE TABLE translation_mst_hist (
    id integer NOT NULL,
    translation_mst_id integer NOT NULL,
    language_id integer,
    original_id integer,
    value character varying(255),
    action integer NOT NULL,
    author_id integer NOT NULL,
    created_at character varying(255) NOT NULL
);
CREATE TABLE user_mgmt (
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
CREATE TABLE user_mgmt_hist (
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
CREATE TABLE public.token_mst (
	id integer NOT NULL,
	token_hash character varying(255),
	account_id integer NOT NULL,
	device_name character varying(255) NULL,
	ip_address character varying(255) NULL,
	expired_at timestamp(0) NULL,
	created_at timestamp(0) NULL,
	updated_at timestamp(0) NULL,
	CONSTRAINT token_mst_pkey PRIMARY KEY (id)
);
