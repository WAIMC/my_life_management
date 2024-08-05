<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('
            CREATE VIEW admin_policy_view AS
            SELECT
                ta.id          AS admin_id,
                td.code        AS department_code,
                td.name        AS department_name,
                tpd.table_name AS table_name,
                tpd.row_id     AS row_id
            FROM
                t_admin ta                                                                  -- Account
                INNER JOIN t_admin_department tad ON tad.admin_id = ta.id                   -- 
                INNER JOIN t_department td ON td.id = tad.dept_id                           -- Department
                INNER JOIN t_department_management tdm ON tdm.department_id = td.id         -- 
                INNER JOIN t_policy_department tpd ON tpd.id = tdm.policy_department_id     -- Policy
            WHERE
                ta.status = 1
                AND td.status = 1
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS admin_policy_view");
    }
};
