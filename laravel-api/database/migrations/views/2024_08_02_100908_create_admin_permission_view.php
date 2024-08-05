<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            CREATE VIEW admin_permission_view AS
            SELECT
            ta.id    AS admin_id,
            tr.name  AS role_name,
            CASE
                WHEN ta2.type = 0 THEN 'GET'
                WHEN ta2.type = 1 THEN 'POST'
                WHEN ta2.type = 2 THEN 'PUT'
                WHEN ta2.type = 3 THEN 'PATCH'
                WHEN ta2.type = 4 THEN 'DELETE'
                ELSE null
            END AS type,
            ta2.name AS api_name,
            ta2.path AS path,
            tf.name  AS feature_name,
            tf.group AS feature_group
        FROM
            t_admin ta												-- Account
            INNER JOIN t_admin_role tar ON tar.admin_id = ta.id 	-- Admin role 
            INNER JOIN t_role tr ON tr.id = tar.role_id				-- Role 
            INNER JOIN t_api_role tar2 ON tar2.role_id = tr.id		-- Api feature
            INNER JOIN t_api ta2 ON ta2.id = tar2.api_id 			-- Api id
            INNER JOIN t_feature tf ON tf.id = ta2.feature_id 		-- Feature
        WHERE
            ta.status = 1
            AND ta.is_active = TRUE
            AND tr.is_active = TRUE 
            AND ta2.is_valid = TRUE 
            AND tf.status = 1
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS admin_permission_view");
    }
};
