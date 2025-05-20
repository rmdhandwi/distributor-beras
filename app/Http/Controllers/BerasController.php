<?php

namespace App\Http\Controllers;

use App\Models\BerasModel;
use App\Models\DetailBerasModel;
use App\Models\ProdusenModel;
use App\Services\ImageValidation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class BerasController extends Controller
{
    private $file_path = 'upload/sertifikat_beras/';
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $loggedInUser = auth()->guard()->user();
        if($loggedInUser->role === 'Admin')
        {
            $dataProdusen = ProdusenModel::select('id_produsen','nama_produsen')->get();
            $dataBeras = BerasModel::with(['produsen:id_produsen,nama_produsen'])->get();
            return Inertia::render('Admin/Beras/Index', [
                'dataProdusen' => $dataProdusen,
                'dataBeras' => $dataBeras,
            ]);
        }
        else if($loggedInUser->role === 'Produsen')
        {
            $dataProdusen = ProdusenModel::where('user_id', $loggedInUser->user_id)->select('id_produsen','nama_produsen')->get();
            $dataBeras = BerasModel::where('id_produsen', $dataProdusen[0]->id_produsen)->with(['produsen:id_produsen,nama_produsen'])->get();
            return Inertia::render('Produsen/Beras/Index', [
                'dataProdusen' => $dataProdusen,
                'dataBeras' => $dataBeras,
            ]);
        }
        else if($loggedInUser->role === 'Pemilik')
        {
            $dataProdusen = ProdusenModel::select('id_produsen','nama_produsen')->get();
            $dataBeras = BerasModel::with(['produsen:id_produsen,nama_produsen'])->get();
            return Inertia::render('Pemilik/Beras/Index', [
                'dataProdusen' => $dataProdusen,
                'dataBeras' => $dataBeras,
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $req)
    {
        // Format ulang tanggal dari ISO menjadi Y-m-d
        $req->merge([
            'tgl_produksi' => Carbon::parse($req->tgl_produksi)->timezone('Asia/Jayapura')->format('Y-m-d'),
            'tgl_kadaluarsa' => Carbon::parse($req->tgl_kadaluarsa)->timezone('Asia/Jayapura')->format('Y-m-d'),
        ]);

        $validated = $req->validate([
            'nama_beras' => 'required|string|max:255|unique:tb_beras,nama_beras',
            'id_produsen' => 'required|exists:tb_produsen,id_produsen',
            'jenis_beras' => 'required|string|max:255',
            'stok_tersedia' => 'required|integer|min:0',
            'tgl_produksi' => 'required|date',
            'kualitas_beras' => 'required|string|max:255',
            'sertifikat_beras' => 'required',
            'detail' => 'required|array',
            'detail.stok10kg.jumlah' => 'integer|min:0',
            'detail.stok20kg.jumlah' => 'integer|min:0',
            'detail.stok50kg.jumlah' => 'integer|min:0',
        ], [
            'required' => ':attribute wajib diisi.',
            'unique' => ':attribute sudah terdaftar.',
            'exists' => ':attribute tidak valid.',
            'min' => ':attribute tidak boleh kurang dari :min.',
            'max' => ':attribute terlalu panjang.',
        ]);

        $loggedInUser = auth()->guard()->user();

        $filePathFix = $this->file_path . $loggedInUser->name .'/';

        $imageValidation = new ImageValidation();

        $fileNameFix = 'sertifikat-'.$req->nama_beras;

        $linkFile = $imageValidation->validateImage($req, 'sertifikat_beras', $filePathFix, $fileNameFix);

        if($linkFile)
        {
            $validatedData = array_merge($validated, [
                'sertifikat_beras' => $linkFile,
            ]);

            $insert = BerasModel::create($validatedData);

            $idBeras = $insert->id_beras;

            if($idBeras)
            {
                foreach ($req->detail as $stok) {
                    if ((int)$stok['jumlah'] > 0) {
                        DetailBerasModel::create([
                            'id_beras' => $idBeras,
                            'berat' => $stok['berat'],
                            'jumlah' => $stok['jumlah'],
                            'harga' => $stok['harga'],
                        ]);
                    }
                }

                return redirect()->back()->with([
                    'notif_status' => 'success',
                    'notif_message' => 'Data beras berhasil ditambahkan!',
                ]);
            }

            else {
                return redirect()->back()->with([
                    'notif_status' => 'error',
                    'notif_message' => 'Gagal menambahkan data beras :(',
                ]);
            }
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $req)
    {
        $id = $req->id_beras;
        // Format tanggal ISO ke format MySQL
        $req->merge([
            'tgl_produksi' => Carbon::parse($req->tgl_produksi)->timezone('Asia/Jayapura')->format('Y-m-d'),
            'tgl_kadaluarsa' => Carbon::parse($req->tgl_kadaluarsa)->timezone('Asia/Jayapura')->format('Y-m-d'),
        ]);

        // Validasi input
        $validated = $req->validate([
            'nama_beras'       => 'required|string|max:255|unique:tb_beras,nama_beras,' . $id . ',id_beras',
            'id_produsen'      => 'required|exists:tb_produsen,id_produsen',
            'jenis_beras'      => 'required|string|max:255',
            'harga_jual'       => 'required|integer|min:0',
            'stok_awal'        => 'required|integer|min:0',
            'stok_tersedia'    => 'required|integer|min:0',
            'tgl_produksi'     => 'required|date',
            'tgl_kadaluarsa'   => 'required|date|after_or_equal:tgl_produksi',
            'kualitas_beras'   => 'nullable|string|max:255',
            'status_beras'     => 'required|string|max:255',
        ]);

        // Cari data berdasarkan ID
        $beras = BerasModel::findOrFail($id);

        if($req->hasFile('sertifikat_beras'))
        {
            $loggedInUser = auth()->guard()->user();

            $filePathFix = $this->file_path . $loggedInUser->name .'/';

            $imageValidation = new ImageValidation();

            $fileNameFix = 'sertifikat-'.$req->nama_beras;

            $linkFile = $imageValidation->validateImage($req, 'sertifikat_beras', $filePathFix, $fileNameFix);

            if($linkFile)
            {
                $validatedData = array_merge($validated, [
                    'sertifikat_beras' => $linkFile,
                ]);

                Storage::disk('public')->delete($filePathFix . basename($beras->sertifikat_beras));

                $update = $beras->update($validatedData);

                if ($update)
                {
                    return redirect()->back()->with([
                        'notif_status' => 'success',
                        'notif_message' => 'Data beras berhasil diupdate!',
                    ]);
                }
                else
                {
                    return redirect()->back()->with([
                        'notif_status' => 'error',
                        'notif_message' => 'Gagal update data beras :(',
                    ]);
                }

            }
        }

        // Update data
        $update = $beras->update($validated);

        if ($update) {
            return redirect()->back()->with([
                'notif_status' => 'success',
                'notif_message' => 'Data beras berhasil diupdate!',
            ]);
        } else {
            return redirect()->back()->with([
                'notif_status' => 'error',
                'notif_message' => 'Gagal update data beras :(',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $req)
    {
        $loggedInUser = auth()->guard()->user();
        $filePathFix = $this->file_path . $loggedInUser->name .'/';

        $beras = BerasModel::findOrFail($req->id_beras);

        Storage::disk('public')->delete($filePathFix . basename($beras->sertifikat_beras));

        if ($beras->delete()) {
            return redirect()->back()->with([
                'notif_status' => 'success',
                'notif_message' => 'Data beras berhasil dihapus.',
            ]);
        } else {
            return redirect()->back()->with([
                'notif_status' => 'error',
                'notif_message' => 'Gagal menghapus data beras.',
            ]);
        }
    }
}
