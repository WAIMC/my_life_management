-- ****************************** TRUNCATE ALL DATA ***********************
SET CONSTRAINTS ALL DEFERRED;
TRUNCATE TABLE t_admin, t_admin_role, t_role, t_api_role, t_api, t_feature,
t_admin_department, t_department, t_department_management, t_policy_department;
SET CONSTRAINTS ALL IMMEDIATE;



-- ****************************** INSERT ALL DATA ***********************
-- t_admin pass: 12345678
INSERT INTO t_admin
(id, email, user_name, "password", first_name, last_name, address, phone_number, birth, gender, status, is_active, avatar, email_verified_at, remember_token, created_at, updated_at)
VALUES(1, 'root@gmail.com', 'root', '$2y$12$O/vJUxJjBm/.cHCu7ewYfOOJboA4cUMrxtEoBGgfSYk3fNPTzjF6K', 'first', 'last', NULL, NULL, NULL, 0, 1, true, NULL, NULL, NULL, now(), now());
SELECT setval(pg_get_serial_sequence('t_admin', 'id'), (SELECT MAX(id) FROM t_admin) + 1);

-- t_role
INSERT INTO t_role
(id, "name", "permission", is_active, created_at, updated_at)
VALUES(1, 'root', 'root', true, now(), now());
SELECT setval(pg_get_serial_sequence('t_role', 'id'), (SELECT MAX(id) FROM t_role) + 1);

-- t_admin_role
INSERT INTO t_admin_role
(admin_id, role_id, created_at, updated_at)
VALUES(1, 1, now(), now());

-- t_feature
INSERT INTO t_feature
(id, "name", "group_name", description, status, created_at, updated_at)
VALUES
(1, 'admin', 'admin', 'admin management', 1, now(), now()),
(2, 'role', 'admin', 'role management', 1, now(), now()),
(3, 'admin role', 'admin', 'admin role management', 1, now(), now()),
(4, 'feature', 'admin', 'feature management', 1, now(), now()),
(5, 'api', 'admin', 'api management', 1, now(), now()),
(6, 'api role', 'admin', 'api role management', 1, now(), now());
SELECT setval(pg_get_serial_sequence('t_feature', 'id'), (SELECT MAX(id) FROM t_feature) + 1);

-- t_api
INSERT INTO t_api
("type", "name", "path", is_active, feature_id, created_at, updated_at)
VALUES
(0, 'current admin', 'api/admin/me', true, 1, now(), now()),
(1, 'logout admin', 'api/admin/logout', true, 1, now(), now()),
(0, 'admin list', 'api/admin/list', true, 1, now(), now()),
(1, 'admin store', 'api/admin/store', true, 1, now(), now()),
(2, 'update admin', 'api/admin/update/{id}', true, 1, now(), now()),
(4, 'delete admin', 'api/admin/delete/{id}', true, 1, now(), now()),
(0, 'role list', 'api/admin/role/list', true, 2, now(), now()),
(1, 'role store', 'api/admin/role/store', true, 2, now(), now()),
(2, 'update role', 'api/admin/role/update/{id}', true, 2, now(), now()),
(4, 'delete role', 'api/admin/role/delete/{id}', true, 2, now(), now()),
(0, 'admin role list', 'api/admin/admin-role/list', true, 3, now(), now()),
(2, 'admin role update', 'api/admin/admin-role/update', true, 3, now(), now()),
(0, 'feature list', 'api/admin/feature/list', true, 4, now(), now()),
(1, 'feature store', 'api/admin/feature/store', true, 4, now(), now()),
(2, 'update feature', 'api/admin/feature/update/{id}', true, 4, now(), now()),
(4, 'delete feature', 'api/admin/feature/delete/{id}', true, 4, now(), now()),
(0, 'api list', 'api/admin/api/list', true, 5, now(), now()),
(1, 'api store', 'api/admin/api/store', true, 5, now(), now()),
(2, 'update api', 'api/admin/api/update/{id}', true, 5, now(), now()),
(4, 'delete api', 'api/admin/api/delete/{id}', true, 5, now(), now()),
(0, 'api role list', 'api/admin/api-role/list', true, 6, now(), now()),
(2, 'api role update', 'api/admin/api-role/update', true, 6, now(), now());
SELECT setval(pg_get_serial_sequence('t_api', 'id'), (SELECT MAX(id) FROM t_api) + 1);

-- t_department
INSERT INTO t_department
(id, code, "name", status, created_at, updated_at)
VALUES(1, '1', 'root', 1, now(), now());
SELECT setval(pg_get_serial_sequence('t_department', 'id'), (SELECT MAX(id) FROM t_department) + 1);

-- t_admin_department
INSERT INTO t_admin_department
(admin_id, dept_id, created_at, updated_at)
VALUES(1, 1, now(), now());