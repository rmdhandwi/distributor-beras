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
}
