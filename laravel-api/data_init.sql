-- ****************************** TRUNCATE ALL DATA ***********************
SET CONSTRAINTS ALL DEFERRED;
TRUNCATE TABLE t_admin, t_admin_role, t_role, t_api_role, t_api, t_feature,
t_admin_department, t_department, t_department_management, t_policy_department;
SET CONSTRAINTS ALL IMMEDIATE;



-- ****************************** INSERT ALL DATA ***********************
-- t_admin pass: 12345678
INSERT INTO t_admin
(id, email, user_name, "password", first_name, last_name, address, phone_number, birth, gender, status, is_active, avatar, email_verified_at, remember_token, created_at, updated_at)
VALUES(1, 'root@gmail.com', 'root', '$2y$12$O/vJUxJjBm/.cHCu7ewYfOOJboA4cUMrxtEoBGgfSYk3fNPTzjF6K', 'first', 'last', NULL, NULL, NULL, 0, 1, true, NULL, NULL, NULL, null, null);

-- t_role
INSERT INTO t_role
(id, "name", "permission", is_active, created_at, updated_at)
VALUES(1, 'root', 'root', true, null, null);

-- t_admin_role
INSERT INTO t_admin_role
(admin_id, role_id, created_at, updated_at)
VALUES(1, 1, null, null);

-- t_feature
INSERT INTO t_feature
(id, "name", "group", description, status, created_at, updated_at)
VALUES(1, 'admin', 'admin', 'admin management', 1, null, null),
(2, 'role', 'admin', 'role management', 1, null, null);

-- t_api
INSERT INTO t_api
("type", "name", "path", is_valid, feature_id, created_at, updated_at)
VALUES(1, 'register admin account', 'api/admin/register', true, 1, null, null),
(0, 'current admin account', 'api/admin/me', true, 1, null, null),
(1, 'logout admin account', 'api/admin/logout', true, 1, null, null),
(0, 'role list', 'api/admin/role', true, 2, null, null),
(1, 'create role', 'api/admin/role', true, 2, null, null),
(2, 'update role', 'api/admin/role', true, 2, null, null),
(4, 'delete role', 'api/admin/role', true, 2, null, null);

-- t_department
INSERT INTO t_department
(id, code, "name", status, created_at, updated_at)
VALUES(1, '1', 'root', 1, NULL, NULL);

-- t_admin_department
INSERT INTO t_admin_department
(admin_id, dept_id, created_at, updated_at)
VALUES(1, 1, NULL, NULL);