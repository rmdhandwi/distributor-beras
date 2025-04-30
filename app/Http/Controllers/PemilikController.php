<?php

namespace App\Http\Controllers;

use App\Models\ProdusenModel;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PemilikController extends Controller
{

    //
    public function dashboardPage()
    {
        return Inertia::render('Pemilik/Dashboard');
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

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
