<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class barangmasuk extends Model
{
    use HasFactory;
    protected $table = 'barangmasuks';
    protected $fillable = [
        'tgl_masuk',
        'qty_masuk',
        'barang_id',
    ];
}
