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
(6, 'api role', 'admin', 'api role management', 1, now(), now()),
(7, 'department', 'admin', 'department management', 1, now(), now()),
(8, 'admin department', 'admin', 'admin department management', 1, now(), now()),
(9, 'policy department', 'admin', 'policy department management', 1, now(), now()),
(10, 'department management', 'admin', 'department management', 1, now(), now()),
(11, 'category', 'admin', 'category management', 1, now(), now()),
(12, 'skill', 'admin', 'skill management', 1, now(), now());
SELECT setval(pg_get_serial_sequence('t_feature', 'id'), (SELECT MAX(id) FROM t_feature) + 1);

-- t_api
INSERT INTO t_api
("type", "name", "path", is_active, feature_id, created_at, updated_at)
VALUES
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
(2, 'api role update', 'api/admin/api-role/update', true, 6, now(), now()),
(0, 'department list', 'api/admin/department/list', true, 7, now(), now()),
(1, 'department store', 'api/admin/department/store', true, 7, now(), now()),
(2, 'update department', 'api/admin/department/update/{id}', true, 7, now(), now()),
(4, 'delete department', 'api/admin/department/delete/{id}', true, 7, now(), now()),
(0, 'admin department list', 'api/admin/admin-department/list', true, 8, now(), now()),
(2, 'admin department update', 'api/admin/admin-department/update', true, 8, now(), now()),
(0, 'policy department list', 'api/admin/policy-department/list', true, 9, now(), now()),
(1, 'policy department store', 'api/admin/policy-department/store', true, 9, now(), now()),
(2, 'update policy department', 'api/admin/policy-department/update/{id}', true, 9, now(), now()),
(4, 'delete policy department', 'api/admin/policy-department/delete/{id}', true, 9, now(), now()),
(0, 'department management list', 'api/admin/department-management/list', true, 10, now(), now()),
(2, 'department management update', 'api/admin/department-management/update', true, 10, now(), now()),
(0, 'category list', 'api/admin/category/list', true, 11, now(), now()),
(1, 'category store', 'api/admin/category/store', true, 11, now(), now()),
(2, 'update category', 'api/admin/category/update/{id}', true, 11, now(), now()),
(4, 'delete category', 'api/admin/category/delete/{id}', true, 11, now(), now()),
(0, 'skill list', 'api/admin/skill/list', true, 12, now(), now()),
(1, 'skill store', 'api/admin/skill/store', true, 12, now(), now()),
(2, 'update skill', 'api/admin/skill/update/{id}', true, 12, now(), now()),
(4, 'delete skill', 'api/admin/skill/delete/{id}', true, 12, now(), now());
SELECT setval(pg_get_serial_sequence('t_api', 'id'), (SELECT MAX(id) FROM t_api) + 1);

-- t_department
INSERT INTO t_department
(id, code, "name", status, created_at, updated_at)
VALUES(1, '1', 'root', 1, now(), now());
SELECT setval(pg_get_serial_sequence('t_department', 'id'), (SELECT MAX(id) FROM t_department) + 1);

-- t_admin_department
INSERT INTO t_admin_department
(admin_id, department_id, created_at, updated_at)
VALUES(1, 1, now(), now());