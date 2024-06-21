<?php

namespace App\Http\Controllers\Api;

//import model Post
use App\Models\Kategori;

use App\Http\Controllers\Controller;

//import resource PostResource
use App\Http\Resources\KategoriResource;

//import Http request
use Illuminate\Http\Request;

//import facade Validator
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{    
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        //get all posts
        $apiKategoris = Kategori::all();

        //return collection of posts as a resource
        return new KategoriResource($apiKategoris);
    }
    public function store(Request $request)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'deskripsi'              => 'required',
            'kategori'              => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        //create post
        $apiKategori = Kategori::create([
            'deskripsi'          => $request->deskripsi,
            'kategori'          => $request->kategori,
        ]);

        //return response
        return new KategoriResource($apiKategori);
    }
    public function show($id)
    {
        // Find post by ID
        $apiKategori = Kategori::find($id);
    
        if (!$apiKategori) {
            // Return error response
            return response()->json(['status' => 'Kategori tidak ditemukan'], 404);
        }
    
        // Return single post as a resource
        return new KategoriResource($apiKategori);
    }
    public function update(Request $request, $id)
    {
        // Define validation rules
        $validator = Validator::make($request->all(), [
            'deskripsi' => 'required',
            'kategori' => 'required',
        ]);
    
        // Check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    
        // Find post by ID
        $apiKategori = Kategori::find($id);
    
        // Check if kategori is found
        if (!$apiKategori) {
            return response()->json(['status' => 'Kategori tidak ditemukan'], 404);
        }
    
        // Update kategori
        $apiKategori->update([
            'deskripsi' => $request->deskripsi,
            'kategori' => $request->kategori,
        ]);
    
        // Return success response
        return response()->json(['status' => 'Kategori berhasil diubah']);
    }    
    
    public function destroy($id)
    {
        // Find post by ID
        $apiKategori = Kategori::find($id);
    
        // Check if kategori is found
        if (!$apiKategori) {
            return response()->json(['status' => 'Kategori tidak ditemukan'], 404);
        }
    
        try {
            // Delete post  
            $apiKategori->delete();
    
            // Return success response
            return response()->json(['status' => 'Kategori berhasil dihapus'], 200);
        } catch (\Exception $e) {
            // Return error response
            return response()->json(['status' => 'Kategori tidak dapat dihapus'], 500);
        }
    }
    
    
}   