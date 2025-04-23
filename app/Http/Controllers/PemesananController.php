<?php

namespace App\Http\Controllers;

use App\Models\BerasModel;
use App\Models\PemesananModel;
use App\Models\ProdusenModel;
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
        $dataPemesanan = PemesananModel::all();
        return Inertia::render('Admin/Pemesanan/Index', [
            'dataBeras' => $dataBeras,
            'dataProdusen' => $dataProdusen,
            'dataPemesanan' => $dataPemesanan,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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
