<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
            CREATE FUNCTION getKategori(kat VARCHAR(4))
            RETURNS VARCHAR(30)
            DETERMINISTIC
            BEGIN
                IF kat = "B" THEN
                    RETURN "Bahan";
                ELSEIF kat = "A" THEN
                    RETURN "Alat";
                ELSE
                    RETURN "Unknown";
                END IF;
            END
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP FUNCTION IF EXISTS getKategori');
    }
};