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
      CREATE OR REPLACE FUNCTION insert_into_api_role_from_api() RETURNS TRIGGER AS $$
        BEGIN
          -- Insert a new record into t_api_role table
          INSERT INTO t_api_role (api_id, role_id)
          SELECT NEW.id, id FROM t_role WHERE name = 'root';
          RETURN NEW;
        END;
      $$ LANGUAGE plpgsql;
    ");

    // Create the trigger
    DB::unprepared("
      CREATE TRIGGER after_api_insert AFTER INSERT ON t_api
      FOR EACH ROW
      EXECUTE FUNCTION insert_into_api_role_from_api();
    ");
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    // Drop the trigger
    DB::unprepared('DROP TRIGGER IF EXISTS after_api_insert ON t_api');

    // Drop the function
    DB::unprepared('DROP FUNCTION IF EXISTS insert_into_api_role_from_api');
  }
};
