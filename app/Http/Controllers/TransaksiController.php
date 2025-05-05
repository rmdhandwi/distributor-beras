<?php

namespace App\Http\Controllers;

use App\Models\TransaksiModel;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $loggedInUser = auth()->guard()->user();

        if($loggedInUser->role === 'Admin')
        {
            $dataTransaksi = TransaksiModel::with([
                'pemesanan:id_pemesanan,id_beras,id_produsen',
                'pemesanan.produsen:id_produsen,nama_produsen',
                'pemesanan.beras:id_beras,nama_beras'
            ])->get();
            
            return Inertia::render('Admin/Transaksi/Index', [
                'dataTransaksi' => $dataTransaksi,
            ]);
        }
        else if($loggedInUser->role === 'Produsen')
        {

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
