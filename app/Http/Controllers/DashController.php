<?php

namespace App\Http\Controllers;
use App\Models\Barang;
use App\Models\barangkeluar;
use App\Models\barangmasuk;
use App\Models\Kategori;
use Illuminate\Http\Request;

class DashController extends Controller
{
    public function index(){
        $barang = Barang::all()->count();
        $kategori = Kategori::all()->count();
        $barangMasuk = barangmasuk::all()->count();
        $barangKeluar = barangkeluar::all()->count();


        
        return view('dash', compact('barang', 'kategori', 'barangMasuk', 'barangKeluar'));
    }
}