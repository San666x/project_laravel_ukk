<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            CREATE TRIGGER update_barang_stok
            AFTER INSERT ON barangmasuks
            FOR EACH ROW
            BEGIN
                UPDATE barang
                SET barang.stok = barang.stok + NEW.qty_masuk
                WHERE barang.id = NEW.barang_id;
            END;
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP TRIGGER update_barang_stok");
    }
};