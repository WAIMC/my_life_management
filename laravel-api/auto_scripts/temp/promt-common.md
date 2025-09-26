"prompt": "You are a Senior Backend Engineer.
Generate a COMPLETE Laravel CRUD API module based on this migration:

- Scope: master
- Table: language_mst_hist
- Columns:
  id int4 NOT NULL,
  language_mst_id int4 NOT NULL (foreign key to language_mst),
  abbreviation varchar(10) NULL,
  name varchar(30) NULL,
  is_active bool NULL DEFAULT,
  action int4 NOT NULL,
  author_id int4 NOT NULL,
  created_at varchar NOT NULL
