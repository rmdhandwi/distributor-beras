<?php

namespace App\Http\Controllers;

use App\Models\BerasModel;
use App\Models\GudangModel;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GudangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $loggedInUser = auth()->guard()->user();

        if($loggedInUser->role === 'Admin')
        {
            $dataBeras = BerasModel::whereDoesntHave('gudang')->with(['produsen:id_produsen,nama_produsen'])->get();
            $dataGudang = GudangModel::with(['beras:id_beras,nama_beras','produsen:id_produsen,nama_produsen'])->get();

            return Inertia::render('Admin/Gudang/Index', [
                'dataBeras' => $dataBeras,
                'dataGudang' => $dataGudang,
            ]);
        }
        if($loggedInUser->role === 'Pemilik')
        {
            $dataBeras = BerasModel::whereDoesntHave('gudang')->with(['produsen:id_produsen,nama_produsen'])->get();
            $dataGudang = GudangModel::with(['beras:id_beras,nama_beras','produsen:id_produsen,nama_produsen'])->get();

            return Inertia::render('Pemilik/Gudang/Index', [
                'dataBeras' => $dataBeras,
                'dataGudang' => $dataGudang,
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $req)
    {
        //
        $validated = $req->validate([
            'id_beras'      => 'required|exists:tb_beras,id_beras',
            'id_produsen'      => 'required|exists:tb_produsen,id_produsen',
            'stok_awal'        => 'required|integer|min:0',
            'rusak'        => 'required|integer|min:0',
            'hilang'        => 'required|integer|min:0',
            'stok_sisa'    => 'required|integer|min:0',
        ], [
            'required'               => ':attribute wajib diisi.',
            'exists'                 => ':attribute tidak valid.',
            'min'                    => ':attribute tidak boleh kurang dari :min.',
            'max'                    => ':attribute terlalu panjang.',
        ]);

        $insert = GudangModel::create($validated);

        if ($insert) {
            return redirect()->back()->with([
                'notif_status' => 'success',
                'notif_message' => 'Data stok berhasil ditambahkan!',
            ]);
        } else {
            return redirect()->back()->with([
                'notif_status' => 'error',
                'notif_message' => 'Gagal menambahkan data beras :(',
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $req)
    {
        //
        $validated = $req->validate([
            'id_beras' => 'required|exists:tb_beras,id_beras',
            'id_produsen' => 'required|exists:tb_produsen,id_produsen',
            'stok_awal' => 'required|integer|min:0',
            'rusak' => 'required|integer|min:0',
            'hilang' => 'required|integer|min:0',
            'stok_sisa' => 'required|integer|min:0',
        ], [
            'required' => ':attribute wajib diisi.',
            'exists' => ':attribute tidak valid.',
            'min' => ':attribute tidak boleh kurang dari :min.',
            'max' => ':attribute terlalu panjang.',
        ]);

        $gudang = GudangModel::findOrFail($req->id_gudang);

        $insert = $gudang->update($validated);

        if ($insert) {
            return redirect()->back()->with([
                'notif_status' => 'success',
                'notif_message' => 'Data stok berhasil diupdate!',
            ]);
        } else {
            return redirect()->back()->with([
                'notif_status' => 'error',
                'notif_message' => 'Gagal update data stok :(',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $req)
    {
        //
        $gudang = GudangModel::findOrFail($req->id_gudang);

        if ($gudang->delete()) {
            return redirect()->back()->with([
                'notif_status' => 'success',
                'notif_message' => 'Data stok berhasil dihapus.',
            ]);
        } else {
            return redirect()->back()->with([
                'notif_status' => 'error',
                'notif_message' => 'Gagal menghapus data stok.',
            ]);
        }
    }
}
