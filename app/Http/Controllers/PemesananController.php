<?php

namespace App\Http\Controllers;

use App\Models\BerasModel;
use App\Models\PemesananModel;
use App\Models\ProdusenModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PemesananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $dataProdusen = ProdusenModel::with(['daftarBeras:id_beras,id_produsen,nama_beras'])->get(['id_produsen','nama_produsen']);
        $dataBeras = BerasModel::get(['id_beras','id_produsen','nama_beras']);
        $dataProdusen = ProdusenModel::get(['id_produsen','nama_produsen']);
        $dataPemesanan = PemesananModel::with(['beras:id_beras,nama_beras','produsen:id_produsen,nama_produsen'])->get();
        return Inertia::render('Admin/Pemesanan/Index', [
            'dataBeras' => $dataBeras,
            'dataProdusen' => $dataProdusen,
            'dataPemesanan' => $dataPemesanan,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $req)
    {
        // Format ulang tanggal dari ISO menjadi Y-m-d
        $req->merge([
            'tgl_pemesanan' => Carbon::parse($req->tgl_produksi)->timezone('Asia/Jayapura')->format('Y-m-d'),
        ]);
        $validated = $req->validate([
            'id_beras' => 'required|exists:tb_beras,id_beras',
            'id_produsen' => 'required|exists:tb_produsen,id_produsen',
            'jmlh' => 'required|max:100',
            'tgl_pemesanan' => 'required|date',
            'catatan' => 'required',
        ],
        [
            'required' => ':attribute wajib diisi',
            'max' => 'tidak boleh melebihi :max',
        ]);

        $validated['status_pesanan'] = 'Pending';

        $insert = PemesananModel::create($validated);

        if ($insert)
        {
            return redirect()->back()->with([
                'notif_status' => 'success',
                'notif_message' => 'Data pemesanan berhasil ditambahkan!',
            ]);
        }
        else
        {
            return redirect()->back()->with([
                'notif_status' => 'error',
                'notif_message' => 'Gagal menambahkan data pemesanan :(',
            ]);
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $req, string $id)
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
