<?php

namespace App\Http\Controllers;

use App\Models\BerasModel;
use App\Models\DetailPemesananModel;
use App\Models\PemesananModel;
use App\Models\ProdusenModel;
use App\Models\TransaksiModel;
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

        $loggedInUser = auth()->guard()->user();

        if($loggedInUser->role === 'Admin')
        {
            $dataBeras = BerasModel::with([
                'detail:id_detail,id_beras,berat,jumlah,harga',
                'produsen:id_produsen,nama_produsen'
            ])->get(['id_beras','id_produsen','nama_beras']);
            // pisahkan detail berdasarkan berat
            foreach ($dataBeras as $item) {
                $detailMap = $item->detail->keyBy('berat');

                $item->stok10kg = $detailMap->get(10); // bisa null kalau tidak ada
                $item->stok20kg = $detailMap->get(20);
                $item->stok50kg = $detailMap->get(50);
            }
            $dataProdusen = ProdusenModel::get(['id_produsen','nama_produsen']);
            $dataPemesanan = PemesananModel::with([
                'beras:id_beras,nama_beras',
                'produsen:id_produsen,nama_produsen',
                'detail'
            ])->get();


            // pisahkan detail berdasarkan berat
            foreach ($dataPemesanan as $item) {
                $detailMap = $item->detail->keyBy('berat');

                $item->stok10kg = $detailMap->get(10); // bisa null kalau tidak ada
                $item->stok20kg = $detailMap->get(20);
                $item->stok50kg = $detailMap->get(50);
            }

            return Inertia::render('Admin/Pemesanan/Index', [
                'dataBeras' => $dataBeras,
                'dataProdusen' => $dataProdusen,
                'dataPemesanan' => $dataPemesanan,
            ]);
        }
        else if($loggedInUser->role === 'Produsen')
        {
            $produsen = ProdusenModel::where('user_id', $loggedInUser->user_id)->first();
            $dataPemesanan = PemesananModel::where('id_produsen', $produsen->id_produsen)->with(['beras:id_beras,nama_beras','produsen:id_produsen,nama_produsen'])->get();

            return Inertia::render('Produsen/Pemesanan/Index', [
                'dataPemesanan' => $dataPemesanan,
            ]);
        }
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
            'jmlh' => 'required|integer|min:1',
            'tgl_pemesanan' => 'required|date',
            'catatan' => 'required',
            'stok10kg.jumlah' => 'integer|min:0',
            'stok10kg.harga_satuan' => 'integer|min:0',
            'stok10kg.total_harga' => 'integer|min:0',
            'stok20kg.jumlah' => 'integer|min:0',
            'stok20kg.harga_satuan' => 'integer|min:0',
            'stok20kg.total_harga' => 'integer|min:0',
            'stok50kg.jumlah' => 'integer|min:0',
            'stok50kg.harga_satuan' => 'integer|min:0',
            'stok50kg.total_harga' => 'integer|min:0',
        ],
        [
            'required' => ':attribute wajib diisi',
            'max' => 'tidak boleh melebihi :max',
        ]);

        $validated['status_pesanan'] = 'Pending';

        $insert = PemesananModel::create($validated);

        $idPemesanan = $insert->id_pemesanan;

        if ($idPemesanan)
        {
            foreach (['stok10kg', 'stok20kg', 'stok50kg'] as $key) {
                $stok = $req->$key;

                if ($stok !== null && (int)$stok['jumlah'] > 0)
                {
                    DetailPemesananModel::create([
                        'id_pemesanan' => $idPemesanan,
                        'berat' => $stok['berat'],
                        'jumlah' => $stok['jumlah'],
                        'harga_satuan' => $stok['harga_satuan'],
                        'total_harga' => $stok['total_harga'],
                    ]);
                }
            }

            return redirect()->back()->with([
                'notif_status' => 'success',
                'notif_message' => 'Data stok berhasil ditambahkan!',
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
    public function update(Request $req)
    {
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

        $pemesanan = PemesananModel::findOrFail($req->id_pemesanan);

        $update = $pemesanan->update($validated);

        if ($update)
        {
            return redirect()->back()->with([
                'notif_status' => 'success',
                'notif_message' => 'Data pemesanan berhasil update!',
            ]);
        }
        else
        {
            return redirect()->back()->with([
                'notif_status' => 'error',
                'notif_message' => 'Gagal update data pemesanan :(',
            ]);
        }
    }

    public function confirm(Request $req)
    {
        $pemesanan = PemesananModel::findOrFail($req->id_pemesanan);
        $beras = BerasModel::findOrFail($pemesanan->id_beras);

        $storeData = [
            'id_pemesanan' => $req->id_pemesanan,
            'tgl_transaksi' => NULL,
            'jmlh' => $pemesanan->jmlh,
            'harga_satuan' => $beras->harga_jual,
            'total_harga' => $beras->harga_jual * $pemesanan->jmlh,
            'bukti_bayar' => NULL,
            'status_pembayaran' => 'Pending',
            'status_pengiriman' => 'Pending',
            'tgl_pengiriman' => NULL,
            'catatan' => '-',
        ];

        $insert = TransaksiModel::create($storeData);

        if ($insert)
        {
            $pemesanan->status_pesanan = 'Telah Dikonfirmasi';
            $update = $pemesanan->save();

            if($update)
            {
                return redirect()->back()->with([
                    'notif_status' => 'success',
                    'notif_message' => 'Pemesanan berhasil dikonfirmasi!',
                ]);
            }
        }
        else
        {
            return redirect()->back()->with([
                'notif_status' => 'error',
                'notif_message' => 'Gagal konfirmasi pemesanan :(',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $req)
    {
        //
        $pemesanan = PemesananModel::findOrFail($req->id_pemesanan);

        if ($pemesanan->delete()) {
            return redirect()->back()->with([
                'notif_status' => 'success',
                'notif_message' => 'Data pemesanan berhasil dihapus.',
            ]);
        } else {
            return redirect()->back()->with([
                'notif_status' => 'error',
                'notif_message' => 'Gagal menghapus data pemesanan.',
            ]);
        }
    }
}
