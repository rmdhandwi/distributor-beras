<?php

namespace App\Http\Controllers;

use App\Models\BerasModel;
use App\Models\DetailGudangModel;
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
            $dataBeras = BerasModel::whereDoesntHave('gudang')->with([
                'produsen:id_produsen,nama_produsen',
                'detail:id_detail,id_beras,berat,jumlah,harga',
            ])->get();

            // pisahkan detail berdasarkan berat
            foreach ($dataBeras as $item) {
                $detailMap = $item->detail->keyBy('berat');

                $item->stok10kg = $detailMap->get(10); // bisa null kalau tidak ada
                $item->stok20kg = $detailMap->get(20);
                $item->stok50kg = $detailMap->get(50);
            }

            $dataGudang = GudangModel::with([
                'beras:id_beras,nama_beras,jenis_beras',
                'produsen:id_produsen,nama_produsen',
                'detail:id_detail_gudang,id_gudang,berat,stok_awal,rusak,hilang,stok_sisa'
            ])->get();

            // pisahkan detail berdasarkan berat
            foreach ($dataGudang as $item) {
                $detailMap = $item->detail->keyBy('berat');

                $item->stok10kg = $detailMap->get(10); // bisa null kalau tidak ada
                $item->stok20kg = $detailMap->get(20);
                $item->stok50kg = $detailMap->get(50);
            }

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
            'id_beras' => 'required|exists:tb_beras,id_beras',
            'id_produsen' => 'required|exists:tb_produsen,id_produsen',
            'stok10kg.stok_awal' => 'integer|min:0',
            'stok10kg.rusak' => 'integer|min:0',
            'stok10kg.hilang' => 'integer|min:0',
            'stok10kg.stok_sisa' => 'integer|min:0',
            'stok20kg.stok_awal' => 'integer|min:0',
            'stok20kg.rusak' => 'integer|min:0',
            'stok20kg.hilang' => 'integer|min:0',
            'stok20kg.stok_sisa' => 'integer|min:0',
            'stok50kg.stok_awal' => 'integer|min:0',
            'stok50kg.rusak' => 'integer|min:0',
            'stok50kg.hilang' => 'integer|min:0',
            'stok50kg.stok_sisa' => 'integer|min:0',
        ], [
            'required' => ':attribute wajib diisi.',
            'exists' => ':attribute tidak valid.',
            'min' => ':attribute tidak boleh kurang dari :min.',
            'max' => ':attribute terlalu panjang.',
        ]);

        $insert = GudangModel::create($validated);

        $idGudang = $insert->id_gudang;

        if ($idGudang) {

            foreach (['stok10kg', 'stok20kg', 'stok50kg'] as $key) {
                $stok = $req->$key;
                if ($stok !== null && (int)$stok['stok_awal'] > 0)
                {
                    DetailGudangModel::create([
                        'id_gudang' => $idGudang,
                        'berat' => $stok['berat'],
                        'stok_awal' => $stok['stok_awal'],
                        'rusak' => $stok['rusak'],
                        'hilang' => $stok['hilang'],
                        'stok_sisa' => $stok['stok_sisa'],
                    ]);
                }
            }

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
            'stok10kg.stok_awal' => 'integer|min:0',
            'stok10kg.rusak' => 'integer|min:0',
            'stok10kg.hilang' => 'integer|min:0',
            'stok10kg.stok_sisa' => 'integer|min:0',
            'stok20kg.stok_awal' => 'integer|min:0',
            'stok20kg.rusak' => 'integer|min:0',
            'stok20kg.hilang' => 'integer|min:0',
            'stok20kg.stok_sisa' => 'integer|min:0',
            'stok50kg.stok_awal' => 'integer|min:0',
            'stok50kg.rusak' => 'integer|min:0',
            'stok50kg.hilang' => 'integer|min:0',
            'stok50kg.stok_sisa' => 'integer|min:0',
        ], [
            'required' => ':attribute wajib diisi.',
            'exists' => ':attribute tidak valid.',
            'min' => ':attribute tidak boleh kurang dari :min.',
            'max' => ':attribute terlalu panjang.',
        ]);

        $gudang = GudangModel::findOrFail($req->id_gudang);

        $update = $gudang->update($validated);

        if ($update) {
            $beratAktif = [];

            foreach (['stok10kg', 'stok20kg', 'stok50kg'] as $key) {
                $stok = $req->$key;
                if ($stok !== null && (int)$stok['stok_awal'] > 0)
                {
                    $beratAktif[] = $stok['berat'];

                    DetailGudangModel::updateOrCreate([
                        'id_detail_gudang' => $stok['id_detail_gudang']
                    ],[
                        'id_gudang' => $req->id_gudang,
                        'berat' => $stok['berat'],
                        'stok_awal' => $stok['stok_awal'],
                        'rusak' => $stok['rusak'],
                        'hilang' => $stok['hilang'],
                        'stok_sisa' => $stok['stok_sisa'],
                    ]);
                }
            }

            DetailGudangModel::where('id_gudang', $req->id_gudang)->whereNotIn('berat', $beratAktif)->delete();

            return redirect()->back()->with([
                'notif_status' => 'success',
                'notif_message' => 'Data stok berhasil diupdate!',
            ]);
        }
        else
        {
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
