Generate a complete CRUD API module from the following migration file content:
- Scope: master
- Table: admin_department_mst
- Column 
	admin_id integer NOT NULL, (foreign key to admin_mst)
	department_id integer NOT NULL, (foreign key to department_mst)
	created_at timestamp(0) NULL,
	updated_at timestamp(0) NULL,

Generate a complete CRUD API module from the following migration file content:
- Scope: master
- Table: admin_mst
- Column 
	id integer NOT NULL,
	email varchar(30) NOT NULL,
	user_name varchar(50) NOT NULL,
	password varchar(100) NOT NULL,
	first_name varchar(20) NOT NULL,
	last_name varchar(20) NOT NULL,
	address varchar(100) NULL,
	phone_number varchar(20) NULL,
	birth timestamp(0) NULL,
	gender integer DEFAULT 0 NOT NULL,
	status integer DEFAULT 0 NOT NULL,
	is_active bool DEFAULT false NOT NULL,
	avatar varchar(30) NULL,
	email_verified_at timestamp(0) NULL,
	remember_token varchar(100) NULL,
	created_at timestamp(0) NULL,
	updated_at timestamp(0) NULL,

Generate a complete CRUD API module from the following migration file content:
- Scope: history
- Table: admin_mst_hist
- Column 
	id integer NOT NULL,
	admin_mst_id integer NOT NULL, (foreign key to admin_mst)
	email varchar(30) NULL,
	user_name varchar(50) NULL,
	password varchar(100) NULL,
	first_name varchar(20) NULL,
	last_name varchar(20) NULL,
	address varchar(100) NULL,
	phone_number varchar(20) NULL,
	birth timestamp(0) NULL,
	gender integer NULL,
	status integer NULL,
	is_active bool NULL,
	avatar varchar(30) NULL,
	email_verified_at timestamp(0) NULL,
	remember_token varchar(100) NULL,
	action integer NOT NULL,
	author_id integer NOT NULL,
	created_at timestamp(0) NOT NULL,

Generate a complete CRUD API module from the following migration file content:
- Scope: master
- Table: admin_role_mst
- Column 
	admin_id integer NOT NULL, (foreign key to admin_mst)
	role_id integer NOT NULL, (foreign key to role_mst)
	created_at timestamp(0) NULL,
	updated_at timestamp(0) NULL,

Generate a complete CRUD API module from the following migration file content:
- Scope: master
- Table: api_mst
- Column 
	id integer NOT NULL,
	type integer DEFAULT 0 NOT NULL,
	name varchar(50) NOT NULL,
	path varchar(100) NOT NULL,
	is_active bool DEFAULT false NOT NULL,
	feature_id integer NOT NULL, (foreign key to feature_mst)
	created_at timestamp(0) NULL,
	updated_at timestamp(0) NULL,

Generate a complete CRUD API module from the following migration file content:
- Scope: master
- Table: api_role_mst
- Column 
	api_id integer NOT NULL, (foreign key to api_mst)
	role_id integer NOT NULL, (foreign key to role_mst)
	created_at timestamp(0) NULL,
	updated_at timestamp(0) NULL,

Generate a complete CRUD API module from the following migration file content:
- Scope: master
- Table: feature_mst
- Column 
	id integer NOT NULL,
	name varchar(50) NOT NULL,
	group_name varchar(50) NOT NULL,
	description varchar(100) NOT NULL,
	status integer DEFAULT 0 NOT NULL,
	created_at timestamp(0) NULL,
	updated_at timestamp(0) NULL,

Generate a complete CRUD API module from the following migration file content:
- Scope: master
- Table: role_mst
- Column 
	id integer NOT NULL,
	name varchar(30) NOT NULL,
	permission varchar(50) NOT NULL,
	is_active bool DEFAULT false NOT NULL,
	created_at timestamp(0) NULL,
	updated_at timestamp(0) NULL,

Generate a complete CRUD API module from the following migration file content:
- Scope: management
- Table: department_management_mst
- Column 
	department_id integer NOT NULL, (foreign key to department_mst)
	policy_department_id integer NOT NULL, (foreign key to policy_department_mst)
	created_at timestamp(0) NULL,
	updated_at timestamp(0) NULL,

Generate a complete CRUD API module from the following migration file content:
- Scope: master
- Table: department_mst
- Column 
	id integer NOT NULL,
	code varchar(50) NOT NULL,
	name varchar(50) NOT NULL,
	status integer DEFAULT 0 NOT NULL,
	created_at timestamp(0) NULL,
	updated_at timestamp(0) NULL,

Generate a complete CRUD API module from the following migration file content:
- Scope: master
- Table: policy_department_mst
- Column 
	id integer NOT NULL,
	table_name varchar(20) NOT NULL,
	row_id integer NOT NULL,
	created_at timestamp(0) NULL,
	updated_at timestamp(0) NULL,
