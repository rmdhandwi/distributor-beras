<?php

namespace App\Http\Controllers;

use App\Models\RekeningModel;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RekeningController extends Controller
{
    //
    public function index()
    {
        $loggedInUser = auth()->guard()->user();

        $dataRekening = RekeningModel::where('id_produsen', $loggedInUser->user_id)->get();

        return Inertia::render('Produsen/Rekening/Index', [
            'dataRekening' => $dataRekening,
        ]);
    }
}
