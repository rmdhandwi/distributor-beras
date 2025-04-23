<?php

namespace App\Http\Controllers;

use App\Models\BerasModel;
use App\Models\ProdusenModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BerasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $dataProdusen = ProdusenModel::select('id_produsen','nama_produsen')->get();
        $dataBeras = BerasModel::with(['produsen:id_produsen,nama_produsen'])->get();
        return Inertia::render('Admin/Beras/Index', [
            'dataProdusen' => $dataProdusen,
            'dataBeras' => $dataBeras,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $req)
    {
        // Format ulang tanggal dari ISO menjadi Y-m-d
        $req->merge([
            'tgl_produksi' => Carbon::parse($req->tgl_produksi)->format('Y-m-d'),
            'tgl_kadaluarsa' => Carbon::parse($req->tgl_kadaluarsa)->format('Y-m-d'),
        ]);

        $validated = $req->validate([
            'nama_beras'       => 'required|string|max:255|unique:tb_beras,nama_beras',
            'id_produsen'      => 'required|exists:tb_produsen,id_produsen',
            'jenis_beras'      => 'required|string|max:255',
            'harga_jual'       => 'required|integer|min:0',
            'stok_awal'        => 'required|integer|min:0',
            'stok_tersedia'    => 'required|integer|min:0',
            'tgl_produksi'     => 'required|date',
            'tgl_kadaluarsa'   => 'required|date|after_or_equal:tgl_produksi',
            'kualitas_beras'   => 'nullable|string|max:255',
            'sertifikat_beras' => 'nullable|string|max:255',
        ], [
            'required'               => ':attribute wajib diisi.',
            'unique'                 => ':attribute sudah terdaftar.',
            'exists'                 => ':attribute tidak valid.',
            'min'                    => ':attribute tidak boleh kurang dari :min.',
            'max'                    => ':attribute terlalu panjang.',
            'after_or_equal'         => 'Tanggal kadaluarsa harus setelah atau sama dengan tanggal produksi.',
        ]);

        $insert = BerasModel::create($validated);

        if ($insert) {
            return redirect()->back()->with([
                'notif_status' => 'success',
                'notif_message' => 'Data beras berhasil ditambahkan!',
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
        $id = $req->id_beras;
        // Format tanggal ISO ke format MySQL
        $req->merge([
            'tgl_produksi' => Carbon::parse($req->tgl_produksi)->format('Y-m-d'),
            'tgl_kadaluarsa' => Carbon::parse($req->tgl_kadaluarsa)->format('Y-m-d'),
        ]);

        // Validasi input
        $validated = $req->validate([
            'nama_beras'       => 'required|string|max:255|unique:tb_beras,nama_beras,' . $id . ',id_beras',
            'id_produsen'      => 'required|exists:tb_produsen,id_produsen',
            'jenis_beras'      => 'required|string|max:255',
            'harga_jual'       => 'required|integer|min:0',
            'stok_awal'        => 'required|integer|min:0',
            'stok_tersedia'    => 'required|integer|min:0',
            'tgl_produksi'     => 'required|date',
            'tgl_kadaluarsa'   => 'required|date|after_or_equal:tgl_produksi',
            'kualitas_beras'   => 'nullable|string|max:255',
            'sertifikat_beras' => 'nullable|string|max:255',
            'status_beras'     => 'required|string|max:255',
        ]);

        // Cari data berdasarkan ID
        $beras = BerasModel::findOrFail($id);

        // Update data
        $beras->update($validated);

        return redirect()->back()->with([
            'notif_status' => 'success',
            'notif_message' => 'Data beras berhasil diperbarui.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $req)
    {
        $beras = BerasModel::findOrFail($req->id_beras);

        if ($beras->delete()) {
            return redirect()->back()->with([
                'notif_status' => 'success',
                'notif_message' => 'Data beras berhasil dihapus.',
            ]);
        } else {
            return redirect()->back()->with([
                'notif_status' => 'error',
                'notif_message' => 'Gagal menghapus data beras.',
            ]);
        }
    }
}
