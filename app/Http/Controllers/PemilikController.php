<?php

namespace App\Http\Controllers;

use App\Models\BerasModel;
use App\Models\GudangModel;
use App\Models\ProdusenModel;
use App\Models\TransaksiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PemilikController extends Controller
{

    //
    public function dashboardPage()
    {
        $dataBeras = BerasModel::selectRaw('
            MIN(harga_jual) as min_harga_jual,
            AVG(harga_jual) as avg_harga_jual,
            MAX(harga_jual) as max_harga_jual,
            SUM(stok_tersedia) as total_stok_tersedia,
            COUNT(DISTINCT jenis_beras) as jenis_beras
        ')->get();

        $dataGudang = GudangModel::selectRaw('SUM(stok_awal) as total_stok_awal, SUM(rusak) as total_rusak, SUM(hilang) as total_hilang, SUM(stok_sisa) as total_stok_sisa')
        ->first();

        $dataProdusen = ProdusenModel::selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as unverified,
            SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as verified
        ')->first();

        $dataTransaksi = TransaksiModel::select('status_pengiriman')
        ->selectRaw('COUNT(*) as jumlah')
        ->groupBy('status_pengiriman')
        ->get();

        $dataBerasChart = DB::table('tb_produsen')
        ->leftJoin('tb_beras', 'tb_beras.id_produsen', '=', 'tb_produsen.id_produsen')
        ->select(
            'tb_produsen.nama_produsen',
            DB::raw('COALESCE(SUM(tb_beras.stok_tersedia), 0) as total_stok')
        )
        ->groupBy('tb_produsen.id_produsen', 'tb_produsen.nama_produsen')
        ->orderBy('nama_produsen','ASC')
        ->get();

        return Inertia::render('Pemilik/Dashboard', [
            'dataBeras' => $dataBeras,
            'dataBerasChart' => $dataBerasChart,
            'dataGudang' => $dataGudang,
            'dataProdusen' => $dataProdusen,
            'dataTransaksi' => $dataTransaksi,
        ]);
    }

    public function produsenPage()
    {
        $dataProdusen = ProdusenModel::all();

        return Inertia::render('Pemilik/Produsen/Index',[
            'dataProdusen' => $dataProdusen,
        ]);
    }

    public function validasiProdusen(Request $req)
    {
        $produsen = ProdusenModel::findOrFail($req->id_produsen);
        $produsen->status = true;
        $update = $produsen->save();

        if($update)
        {
            $notification = [
                'notif_status' => 'success',
                'notif_message' => 'Validasi Berhasil !',
            ];

            return redirect()->back()->with($notification);
        }
    }
}
