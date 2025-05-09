<?php

namespace App\Http\Controllers;

use App\Models\BerasModel;
use App\Models\GudangModel;
use App\Models\ProdusenModel;
use App\Models\TransaksiModel;
use Inertia\Inertia;

class AdminController extends Controller
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

        return Inertia::render('Admin/Dashboard', [
            'dataBeras' => $dataBeras,
            'dataGudang' => $dataGudang,
            'dataProdusen' => $dataProdusen,
            'dataTransaksi' => $dataTransaksi,
        ]);
    }
}
