<?php

namespace App\Http\Controllers;

use App\Models\BerasModel;
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
            $dataBeras = BerasModel::whereDoesntHave('gudang')->with(['produsen:id_produsen,nama_produsen'])->get();
            $dataGudang = GudangModel::with(['beras:id_beras,nama_beras','produsen:id_produsen,nama_produsen'])->get();
            return Inertia::render('Admin/Gudang/Index', [
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
