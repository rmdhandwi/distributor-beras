<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class LaporanController extends Controller
{
    //
    public function beras(Request $req)
    {
        return Inertia::render('Laporan/Beras', [
            'dataCetak' => $req->data
        ]);
    }
}
