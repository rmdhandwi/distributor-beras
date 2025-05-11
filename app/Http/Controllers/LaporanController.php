<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LaporanController extends Controller
{
    //
    public function beras(Request $req)
    {
        return Inertia::render('Laporan/Beras', [
            'dataCetak' => $req->data,
            'tanggalCetak' => Carbon::now()->format('d-m-Y'),
            'backRoute' => $req->fromRoute,
        ]);
    }

    public function gudang(Request $req)
    {
        return Inertia::render('Laporan/Gudang', [
            'dataCetak' => $req->data,
            'tanggalCetak' => Carbon::now()->format('d-m-Y'),
            'backRoute' => $req->fromRoute,
        ]);
    }
}
