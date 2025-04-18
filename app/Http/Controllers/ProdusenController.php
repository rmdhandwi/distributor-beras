<?php

namespace App\Http\Controllers;

use App\Models\ProdusenModel;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProdusenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $dataProdusen = ProdusenModel::all();

        return Inertia::render('Admin/Produsen/Index', [
            'dataProdusen' => $dataProdusen,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $req)
    {
        $req->validate([
            '*' => 'required',
            'nama_produsen' => 'required|unique:tb_produsen,nama_produsen',
            'email' => 'required|email:rfc|unique:tb_produsen,email',
            'no_telp' => 'required|numeric|min_digits:12|unique:tb_produsen,no_telp',
        ],
        [
            '*.required' => 'Kolom wajib diisi',
            'nama_produsen.unique' => $req->nama_produsen.' telah terdaftar',
            'email.unique' => $req->email.' telah terdaftar',
            'no_telp.min_digits' => 'nomor telepon minimal 12 angka',
            'no_telp.numeric' => 'nomor telepon harus berupa angka',
            'no_telp.unique' => $req->no_telp.' telah terdaftar',
        ]);

        $insert = ProdusenModel::create([
            'nama_produsen' => $req->nama_produsen,
            'alamat' => $req->alamat,
            'no_telp' => $req->no_telp,
            'email' => $req->email,
            'jenis_beras' => $req->jenis_beras,
            'harga_beras' => $req->harga_beras,
            'jml_stok' => $req->jml_stok,
        ]);

        if($insert)
        {
            return redirect()->back()->with([
                'notif_status' => 'success',
                'notif_message' => 'Berhasil menambahkan produsen : ' .$req->nama_produsen,
            ]);
        }
        else
        {
            return redirect()->back()->with([
                'notif_status' => 'error',
                'notif_message' => 'Gagal menambahkan produsen :(',
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
