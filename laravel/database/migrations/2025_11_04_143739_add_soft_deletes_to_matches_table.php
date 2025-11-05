<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Add soft delete column (jika belum ada)
        if (!Schema::hasColumn('matches', 'deleted_at')) {
            Schema::table('matches', function (Blueprint $table) {
                $table->softDeletes()->after('updated_at');
            });
        }

        // Step 2: Drop old unique constraint (jika masih ada)
        try {
            Schema::table('matches', function (Blueprint $table) {
                $table->dropUnique('matches_lost_report_id_found_report_id_unique');
            });
        } catch (\Exception $e) {
            // Jika constraint tidak ada, skip
        }

        // Step 3: Drop trigger jika sudah ada (untuk avoid error)
        DB::unprepared('DROP TRIGGER IF EXISTS before_match_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS before_match_update');

        // Step 4: Create trigger to prevent duplicate active matches (INSERT)
        DB::unprepared('
            CREATE TRIGGER before_match_insert
            BEFORE INSERT ON matches
            FOR EACH ROW
            BEGIN
                DECLARE existing_count INT;
                
                SELECT COUNT(*) INTO existing_count
                FROM matches
                WHERE lost_report_id = NEW.lost_report_id
                  AND found_report_id = NEW.found_report_id
                  AND deleted_at IS NULL;
                
                IF existing_count > 0 THEN
                    SIGNAL SQLSTATE "45000"
                    SET MESSAGE_TEXT = "Duplicate active match: This combination of lost and found reports already exists";
                END IF;
            END
        ');

        // Step 5: Create trigger to prevent duplicate active matches (UPDATE/RESTORE)
        DB::unprepared('
            CREATE TRIGGER before_match_update
            BEFORE UPDATE ON matches
            FOR EACH ROW
            BEGIN
                DECLARE existing_count INT;
                
                -- Hanya check jika deleted_at berubah menjadi NULL (restore)
                IF NEW.deleted_at IS NULL THEN
                    SELECT COUNT(*) INTO existing_count
                    FROM matches
                    WHERE lost_report_id = NEW.lost_report_id
                      AND found_report_id = NEW.found_report_id
                      AND deleted_at IS NULL
                      AND match_id != NEW.match_id;
                    
                    IF existing_count > 0 THEN
                        SIGNAL SQLSTATE "45000"
                        SET MESSAGE_TEXT = "Duplicate active match: This combination of lost and found reports already exists";
                    END IF;
                END IF;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop triggers first
        DB::unprepared('DROP TRIGGER IF EXISTS before_match_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS before_match_update');

        // Recreate old unique constraint (jika belum ada)
        try {
            Schema::table('matches', function (Blueprint $table) {
                $table->unique(['lost_report_id', 'found_report_id'], 'matches_lost_report_id_found_report_id_unique');
            });
        } catch (\Exception $e) {
            // Skip jika sudah ada
        }

        // Remove soft delete column (jika ada)
        if (Schema::hasColumn('matches', 'deleted_at')) {
            Schema::table('matches', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
};