<?php

namespace App\Http\Controllers;

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
        $dataProdusen = ProdusenModel::with(['daftarBeras:id_beras,nama_beras'])->get();
        $dataPemesanan = PemesananModel::all();
        return Inertia::render('Admin/Pemesanan/Index', [
            'dataProdusen' => $dataProdusen,
            'dataPemesanan' => $dataPemesanan,
        ]);
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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
