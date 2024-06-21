<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\barangmasuk; // Pastikan untuk mengimpor model Kategori
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;



class BarangMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;
    
        $query = DB::table('barangmasuks')
                    ->select('barangmasuks.id', 'barangmasuks.tgl_masuk', 'barangmasuks.qty_masuk','barangmasuks.barang_id', 'barang.merk')
                    ->leftjoin('barang', 'barangmasuks.barang_id', '=', 'barang.id');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('barang.id', 'like', '%' . $search . '%')
                ->orWhere('barang.merk', 'like', '%' . $search . '%')
                ->orWhere('barangmasuks.tgl_masuk', 'like', '%' . $search . '%')
                ->orWhere('barangmasuks.qty_masuk', 'like', '%' . $search . '%');
            });
        }

        $rsetBarangMasuk = $query->paginate(5);
        Paginator::useBootstrap();

        $barangIds = $rsetBarangMasuk->pluck('barang_id')->toArray();
        $barangData = DB::table('barang')->whereIn('id', $barangIds)->get()->keyBy('id');

        return view('v_barangmasuk.index', compact('rsetBarangMasuk', 'barangData'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $rsetBarang = Barang::all();
        return view('v_barangmasuk.create', compact('rsetBarang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tgl_masuk' => 'required|date',
            'qty_masuk' => 'nullable|integer',
            'barang_id' => 'required|exists:barang,id',
        ], [
            'tgl_masuk.required' => 'Tanggal masuk harus diisi.',
            'qty_masuk.required' => 'Qty masuk harus diisi.',
            'qty_masuk.integer' => 'Qty masuk harus berupa angka.',
            'qty_masuk.min' => 'Qty masuk minimal adalah 1.',
            'barang_id.required' => 'Barang harus dipilih.',
        ]);

        DB::beginTransaction();

        try {
            barangmasuk::create([
                'tgl_masuk' => $request->tgl_masuk,
                'qty_masuk' => $request->qty_masuk,
                'barang_id' => $request->barang_id,
            ]);

            // Commit transaksi jika semua operasi berhasil
            DB::commit();
            return redirect()->route('barangmasuk.index')->with(['success' => 'Data Barang Masuk Berhasil Disimpan!']);
        } catch (\Exception $e) { 
            DB::rollback();
            return back()->withErrors(['message' => 'Terjadi kesalahan saat menyimpan data.'])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $rsetBarangmasuk = barangmasuk::find($id);

        return view('v_barangmasuk.show', compact('rsetBarangmasuk'));
    
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $rsetBarangmasuk = barangmasuk::find($id);
        $rsetBarang = Barang::all(); // Anda mungkin perlu menyesuaikan ini sesuai dengan model dan tabel kategori Anda
        return view('v_barangmasuk.edit', compact('rsetBarangmasuk', 'rsetBarang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'tgl_masuk' => 'required|date',
            'qty_masuk' => 'nullable|integer',
            'barang_id' => 'required|exists:barang,id',
        ]);

        if ($validator->fails()) {
            return redirect()->route('v_barangmasuk.edit')
                ->withErrors($validator)
                ->withInput();
        }

        $rsetBarangmasuk = barangmasuk::find($id);

        $rsetBarangmasuk->update([
            'tgl_masuk' => $request->tgl_masuk,
            'qty_masuk' => $request->qty_masuk,
            'barang_id' => $request->barang_id,
        ]);

        return redirect()->route('barangmasuk.index')->with(['success' => 'Data Barang Berhasil Disimpan!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $rsetBarangMasuk = barangmasuk::find($id);
        
            if ($rsetBarangMasuk === null) {
                throw new \Exception('Barang masuk tidak ditemukan');
            }
        
            $rsetBarang = Barang::find($rsetBarangMasuk->barang_id);
        
            if ($rsetBarang === null) {
                throw new \Exception('Barang tidak ditemukan');
            }
        
            $stok_barang = $rsetBarang->stok;
            $qty_masuk = $rsetBarangMasuk->qty_masuk;
            if ($stok_barang < $qty_masuk) {
                throw new \Exception('Stok barang tidak mencukupi untuk menghapus entri barang masuk ini');
            }
        
            $rsetBarangMasuk->delete();
        
            return redirect()->route('barangmasuk.index')->with(['success' => 'Data Berhasil Dihapus!']);
        } catch (\Exception $e) {
            return redirect()->route('barangmasuk.index')->with(['error' => $e->getMessage()]);
        }
    }
}