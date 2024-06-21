<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\barangkeluar; // Pastikan untuk mengimpor model Kategori
use App\Models\barangmasuk; // Pastikan untuk mengimpor model Kategori
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class BarangkeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;
        
        $query = DB::table('barangkeluar')
                    ->select('barangkeluar.id', 'barangkeluar.tgl_keluar', 'barangkeluar.qty_keluar','barangkeluar.barang_id', 'barang.merk')
                    ->leftJoin('barang', 'barangkeluar.barang_id', '=', 'barang.id');
    
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('barang.id', 'like', '%' . $search . '%')
                  ->orWhere('barang.merk', 'like', '%' . $search . '%')
                  ->orWhere('barangkeluar.tgl_keluar', 'like', '%' . $search . '%')
                  ->orWhere('barangkeluar.qty_keluar', 'like', '%' . $search . '%');
            });
        }
    
        $rsetBarangKeluar = $query->paginate(5);

        Paginator::useBootstrap();
        $barangIds = $rsetBarangKeluar->pluck('barang_id')->toArray();
        $barangData = DB::table('barang')->whereIn('id', $barangIds)->get()->keyBy('id');
    
        return view('v_barangkeluar.index', compact('rsetBarangKeluar', 'barangData'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $rsetBarang = Barang::all();
        return view('v_barangkeluar.create', compact('rsetBarang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $barang = Barang::find($request->barang_id);

        $validator = Validator::make($request->all(), [
            'tgl_keluar' => 'required|date|after_or_equal:tgl_masuk',
            'qty_keluar' => 'nullable|integer',
            'barang_id' => 'required|exists:barang,id',
        ],[
            'tgl_keluar.after_or_equal' => 'Tanggal keluar harus setelah atau sama dengan tanggal masuk.',
        ]);
        
        $tgl_masuk = BarangMasuk::where('barang_id', $request->barang_id)->value('tgl_masuk');
        $tgl_keluar = $request->input('tgl_keluar'); // Get the tgl_keluar value from the request

        // dd($validator);
        // Jika tanggal keluar lebih awal dari tanggal masuk, tampilkan pesan kesalahan
        if ($tgl_keluar < $tgl_masuk) {
            return redirect()->route('barangkeluar.create')->with(['Gagal' => 'Tanggal Keluar Tidak Boleh Lebih Awal Dari Tanggal Masuk']);
        }
        
        if ($validator->fails()) {
            return redirect()->route('barangkeluar.create')
                ->withErrors($validator)
                ->withInput();
        }
        

        $validator->after(function ($validator) use ($barang, $request) {
            if ($barang->stok < $request->qty_keluar) {
                $validator->errors()->add('qty_keluar', 'Stok tidak mencukupi.');
            }
        });

    
        if ($validator->fails()) {
            return redirect()->route('barangkeluar.create')
                             ->withErrors($validator)
                             ->withInput();
        }

        DB::beginTransaction();

        try {
            BarangKeluar::create([
                'tgl_keluar' => $request->tgl_keluar,
                'qty_keluar' => $request->qty_keluar,
                'barang_id' => $request->barang_id,
            ]);
    
            $barang->stok -= $request->qty_keluar;
            $barang->save();
    
            DB::commit();
    
            return redirect()->route('barangkeluar.index')->with(['success' => 'Data Berhasil Disimpan!']);
        } catch (QueryException $e) {
            DB::rollBack();
    
            return redirect()->route('barangkeluar.create')
                             ->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data.'])
                             ->withInput();
        }
    
        
        // return redirect()->route('barangkeluar.index')->with(['success' => 'Data Barang Berhasil Disimpan!']);
    
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $rsetBarangkeluar = barangkeluar::find($id);

        return view('v_barangkeluar.show', compact('rsetBarangkeluar'));
    
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $rsetBarangkeluar = barangkeluar::find($id);
        $rsetBarang = Barang::all(); // Anda mungkin perlu menyesuaikan ini sesuai dengan model dan tabel kategori Anda
        return view('v_barangkeluar.edit', compact('rsetBarangkeluar', 'rsetBarang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'tgl_keluar' => 'required|date',
            'qty_keluar' => 'nullable|integer',
            'barang_id' => 'required|exists:barang,id',
        ]);

        if ($validator->fails()) {
            return redirect()->route('barangkeluar.edit')
                ->withErrors($validator)
                ->withInput();
        }

        $rsetBarangkeluar = barangkeluar::find($id);

        $rsetBarangkeluar->update([
            'tgl_keluar' => $request->tgl_keluar,
            'qty_keluar' => $request->qty_keluar,
            'barang_id' => $request->barang_id,
        ]);

        return redirect()->route('barangkeluar.index')->with(['success' => 'Data Barang Berhasil Disimpan!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $barangkeluar = barangkeluar::find($id);
        Storage::delete('public/foto/' . $barangkeluar->foto);
        $barangkeluar->delete();
        return redirect()->route('barangkeluar.index')->with(['success' => 'Data Barang Berhasil Dihapus!']);
    }
}