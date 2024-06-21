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
            CREATE TRIGGER reduce_barang_stok
            AFTER INSERT ON barangkeluar
            FOR EACH ROW
            BEGIN
                IF NEW.qty_keluar <= (SELECT stok FROM barang WHERE id = NEW.barang_id) THEN
                    UPDATE barang
                    SET barang.stok = barang.stok - NEW.qty_keluar
                    WHERE barang.id = NEW.barang_id;
                ELSE
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Stock level cannot be negative';
                END IF;
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
        DB::statement("DROP TRIGGER reduce_barang_stok");
    }
};