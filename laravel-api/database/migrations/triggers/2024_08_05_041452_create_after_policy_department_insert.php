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
    // Create the function
    DB::unprepared("
      CREATE OR REPLACE FUNCTION insert_into_department_management() RETURNS TRIGGER AS $$
        BEGIN
          -- Insert a new record into t_department_management table
          INSERT INTO t_department_management (department_id, policy_department_id, created_at, updated_at)
          SELECT id, NEW.id, now(), now() FROM t_department WHERE name = 'root';
          RETURN NEW;
        END;
      $$ LANGUAGE plpgsql;
    ");

    // Create the trigger
    DB::unprepared("
      CREATE TRIGGER after_policy_department_insert
      AFTER INSERT ON t_policy_department
      FOR EACH ROW
      EXECUTE FUNCTION insert_into_department_management();
    ");
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    // Drop the trigger
    DB::unprepared('DROP TRIGGER IF EXISTS after_policy_department_insert ON t_policy_department');

    // Drop the function
    DB::unprepared('DROP FUNCTION IF EXISTS insert_into_department_management');
  }
};
