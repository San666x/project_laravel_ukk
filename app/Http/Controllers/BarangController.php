<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Kategori; // Pastikan untuk mengimpor model Kategori
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        
        $query = DB::table('barang')
                    ->select('barang.id', 'barang.merk', 'barang.seri', 'barang.spesifikasi', 'barang.stok', 'barang.kategori_id', 'kategori.deskripsi');
    
        $query->leftJoin('kategori', 'barang.kategori_id', '=', 'kategori.id');
    
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('barang.merk', 'like', '%' . $search . '%')
                  ->orWhere('barang.seri', 'like', '%' . $search . '%')
                  ->orWhere('barang.spesifikasi', 'like', '%' . $search . '%')
                  ->orWhere('kategori.deskripsi', 'like', '%' . $search . '%'); // Search in category name
            });
        }
    
        $rsetBarang = $query->paginate(5);
        Paginator::useBootstrap();
        
        return view('v_barang.index', compact('rsetBarang'));

    }

    public function create()
    {
        $rsetKategori = Kategori::all();
        return view('v_barang.create', compact('rsetKategori'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'merk' => 'required|string|max:50|unique:barang,merk',
            'seri' => 'nullable|string|max:50',
            'spesifikasi' => 'nullable|string',
            'stok' => 'nullable|numeric',
            'kategori_id' => 'required|exists:kategori,id',
        ]);

        if ($validator->fails()) {
            return redirect()->route('barang.create')
                ->withErrors($validator)
                ->withInput();
        }

        Barang::create([
            'merk' => $request->merk,
            'seri' => $request->seri,
            'spesifikasi' => $request->spesifikasi,
            'stok' => $request->stok,
            'kategori_id' => $request->kategori_id,
        ]);

        return redirect()->route('barang.index')->with(['success' => 'Data Barang Berhasil Disimpan!']);
    }

    public function show(string $id)
    {
        $rsetBarang = Barang::find($id);

        return view('v_barang.show', compact('rsetBarang'));
    }

    public function edit(string $id)
    {
        $rsetBarang = Barang::find($id);
        $rsetKategori = Kategori::all(); // Anda mungkin perlu menyesuaikan ini sesuai dengan model dan tabel kategori Anda
        return view('v_barang.edit', compact('rsetBarang', 'rsetKategori'));
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'merk' => 'required|string|max:50',
            'seri' => 'nullable|string|max:50',
            'spesifikasi' => 'nullable|string',
            
            'kategori_id' => 'required|exists:kategori,id',
        ]);

        if ($validator->fails()) {
            return redirect()->route('barang.edit', $id)
                ->withErrors($validator)
                ->withInput();
        }

        $barang = Barang::find($id);

        $barang->update([
            'merk' => $request->merk,
            'seri' => $request->seri,
            'spesifikasi' => $request->spesifikasi,
            'stok' => $request->stok,
            'kategori_id' => $request->kategori_id,
        ]);

        return redirect()->route('barang.index')->with(['success' => 'Data Barang Berhasil Diubah!']);
    }

    public function destroy($id)
    {
        if (DB::table('barangmasuks')->where('barang_id', $id)->exists() || DB::table('barangkeluar')->where('barang_id', $id)->exists()){
            return redirect()->route('barang.index')->with(['Gagal' => 'Data Gagal Dihapus!']);
        } else {
            $rsetKategori = Barang::find($id);
            $rsetKategori->delete();
            return redirect()->route('barang.index')->with(['success' => 'Data Berhasil Dihapus!']);
        }
    }

    
}