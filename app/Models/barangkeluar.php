<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class barangkeluar extends Model
{
    use HasFactory;
    protected $table = 'barangkeluar';
    protected $fillable = [
        'tgl_keluar',
        'qty_keluar',
        'barang_id',
    ];
}
