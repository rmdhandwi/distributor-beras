<?php

namespace App\Http\Controllers;

use App\Models\BerasModel;
use App\Models\DetailGudangModel;
use App\Models\GudangModel;
use App\Models\ProdusenModel;
use App\Models\TransaksiModel;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class AdminController extends Controller
{
    //
    public function dashboardPage()
    {   
        $dataBeras = BerasModel::with('detail')->get();

        // Gabungkan semua detail dari setiap beras
        $allDetail = $dataBeras->flatMap->detail;

        // Group dan hitung manual
        $statistikHarga = $allDetail->groupBy('berat')->map(function ($group) {
            return [
                'rata_rata' => $group->avg('harga'),
                'termurah'  => $group->min('harga'),
                'termahal'  => $group->max('harga'),
            ];
        });

        $gudangDetails = DetailGudangModel::get();

        $dataGudang = (object)[
            'total_stok_awal' => $gudangDetails->sum('stok_awal'),
            'total_rusak'     => $gudangDetails->sum('rusak'),
            'total_hilang'    => $gudangDetails->sum('hilang'),
            'total_stok_sisa' => $gudangDetails->sum('stok_sisa'),
        ];

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

        return Inertia::render('Admin/Dashboard', [
            'statistikHarga' => $statistikHarga,
            'dataBerasChart' => $dataBerasChart,
            'dataGudang' => $dataGudang,
            'dataProdusen' => $dataProdusen,
            'dataTransaksi' => $dataTransaksi,
        ]);
    }
}
