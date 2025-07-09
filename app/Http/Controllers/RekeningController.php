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

    public function store(Request $req)
    {
        $loggedInUser = auth()->guard()->user();

        $validated = $req->validate([
            'no_rekening' => 'required|numeric|min_digits:8|max_digits:16|unique:tb_rekening,no_rekening',
            'nama_rekening' => 'required|alpha|max:255',
        ], [
            'numeric' => ':attribute wajib berupa angka.',
            'alpha' => ':attribute wajib berupa huruf.',
            'required' => ':attribute wajib diisi.',
            'unique' => ':attribute sudah terdaftar.',
            'exists' => ':attribute tidak valid.',
            'min' => 'tidak boleh kurang dari :min.',
            'max' => ':attribute terlalu panjang, maks :max',
            'min_digits' => ':attribute terlalu pendek, minimal :min digit.',
            'max_digits' => ':attribute terlalu panjang, maks :max digit.',
        ]);

        $insert = RekeningModel::create([
            'id_produsen' => $loggedInUser->user_id,
            'no_rekening' => $validated['no_rekening'],
            'nama_rekening' => $validated['nama_rekening'],
        ]);

        if($insert)
        {
            return redirect()->back()->with([
                'notif_status' => 'success',
                'notif_message' => 'Berhasil menambahkan rekening '. $validated['nama_rekening'],
            ]);
        }

        return redirect()->back()->with([
            'notif_status' => 'error',
            'notif_message' => 'Gagal menambahkan rekening ' . $validated['nama_rekening'],
        ]);
    }
}
