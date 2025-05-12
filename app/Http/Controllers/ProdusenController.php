<?php

namespace App\Http\Controllers;

use App\Models\BerasModel;
use App\Models\ProdusenModel;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProdusenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dataProdusen = ProdusenModel::all();

        return Inertia::render('Admin/Produsen/Index', [
            'dataProdusen' => $dataProdusen,
        ]);
    }

    public function dashboardPage()
    {
        $loggedInUser = auth()->guard()->user();

        $produsen = ProdusenModel::where('user_id', $loggedInUser->user_id)->get('id_produsen');
        $idProdusen = $produsen[0]->id_produsen;

        $dataBeras = BerasModel::where('id_produsen', $idProdusen)->selectRaw('
            MIN(harga_jual) as min_harga_jual,
            AVG(harga_jual) as avg_harga_jual,
            MAX(harga_jual) as max_harga_jual,
            SUM(stok_tersedia) as total_stok_tersedia,
            COUNT(DISTINCT jenis_beras) as jenis_beras
        ')->get();

        $dataBerasChart = BerasModel::where('id_produsen', $idProdusen)->select('nama_beras','stok_tersedia')->get();

        return Inertia::render('Produsen/Dashboard',[
            'dataBeras' => $dataBeras,
            'dataBerasChart' => $dataBerasChart,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $req)
    {
        $req->validate([
            '*' => 'required',
            'nama_produsen' => 'required|unique:tb_produsen,nama_produsen',
            'email' => 'required|email:rfc|unique:tb_produsen,email',
            'no_telp' => 'required|numeric|min_digits:12|unique:tb_produsen,no_telp',
        ],
        [
            '*.required' => 'Kolom wajib diisi',
            'nama_produsen.unique' => $req->nama_produsen.' telah terdaftar',
            'email.unique' => $req->email.' telah terdaftar',
            'no_telp.min_digits' => 'nomor telepon minimal 12 angka',
            'no_telp.numeric' => 'nomor telepon harus berupa angka',
            'no_telp.unique' => $req->no_telp.' telah terdaftar',
        ]);

        $insert = ProdusenModel::create([
            'nama_produsen' => $req->nama_produsen,
            'alamat' => $req->alamat,
            'no_telp' => $req->no_telp,
            'email' => $req->email,
            'jenis_beras' => $req->jenis_beras,
            'harga_beras' => $req->harga_beras,
            'jml_stok' => $req->jml_stok,
        ]);

        if($insert)
        {
            return redirect()->back()->with([
                'notif_status' => 'success',
                'notif_message' => 'Berhasil menambahkan produsen : ' .$req->nama_produsen,
            ]);
        }
        else
        {
            return redirect()->back()->with([
                'notif_status' => 'error',
                'notif_message' => 'Gagal menambahkan produsen :(',
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $req)
    {
        $id_produsen = $req->id_produsen;

        $req->validate([
            '*' => 'required',
            'nama_produsen' => 'required|unique:tb_produsen,nama_produsen,' .$id_produsen.',id_produsen',
            'email' => 'required|email:rfc|unique:tb_produsen,email,' .$id_produsen. ',id_produsen',
            'no_telp' => 'required|numeric|min_digits:12|unique:tb_produsen,no_telp,' .$id_produsen. ',id_produsen',
        ], [
            '*.required' => 'Kolom wajib diisi.',
            'nama_produsen.unique' => $req->nama_produsen . ' telah terdaftar',
            'email.unique' => $req->email . ' telah terdaftar',
            'no_telp.min_digits' => 'Nomor telepon minimal 12 angka.',
            'no_telp.numeric' => 'Nomor telepon harus berupa angka.',
            'no_telp.unique' => $req->no_telp . ' telah terdaftar',
        ]);

        $update = ProdusenModel::where('id_produsen', $id_produsen)->update([
            'nama_produsen' => $req->nama_produsen,
            'alamat' => $req->alamat,
            'no_telp' => $req->no_telp,
            'email' => $req->email,
            'jenis_beras' => $req->jenis_beras,
            'harga_beras' => $req->harga_beras,
            'jml_stok' => $req->jml_stok,
            'status_stok' => $req->jml_stok > 0 ? 'Tersedia' : 'Kosong',
        ]);

        if ($update) {
            return redirect()->back()->with([
                'notif_status' => 'success',
                'notif_message' => 'Berhasil update data produsen: ' . $req->nama_produsen,
            ]);
        } else {
            return redirect()->back()->with([
                'notif_status' => 'error',
                'notif_message' => 'Gagal update data produsen.',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $req)
    {
        $produsen = ProdusenModel::findOrFail($req->id_produsen);

        if ($produsen->delete()) {
            return redirect()->back()->with([
                'notif_status' => 'success',
                'notif_message' => 'Data produsen berhasil dihapus.',
            ]);
        } else {
            return redirect()->back()->with([
                'notif_status' => 'error',
                'notif_message' => 'Gagal menghapus data produsen.',
            ]);
        }
    }
}
