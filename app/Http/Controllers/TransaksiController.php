<?php

namespace App\Http\Controllers;

use App\Models\PemesananModel;
use App\Models\ProdusenModel;
use App\Models\RekeningModel;
use App\Models\TransaksiModel;
use App\Services\ImageValidation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TransaksiController extends Controller
{
    private $file_path = 'upload/bukti_bayar_/';

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
                'pemesanan.produsen:id_produsen,user_id,nama_produsen',
                'pemesanan.beras:id_beras,nama_beras',
                'pemesanan.detail',
            ])->get();

            $daftarNoRek = RekeningModel::select('id_rekening','id_produsen','no_rekening','nama_rekening')->get();

            // pisahkan detail berdasarkan berat
            foreach ($dataTransaksi as $item) {
                $detailMap = $item->pemesanan->detail->keyBy('berat');

                $item->stok10kg = $detailMap->get(10); // bisa null kalau tidak ada
                $item->stok20kg = $detailMap->get(20);
                $item->stok50kg = $detailMap->get(50);
            }

            return Inertia::render('Admin/Transaksi/Index', [
                'dataTransaksi' => $dataTransaksi,
                'daftarNoRek' => $daftarNoRek,
            ]);
        }
        else if($loggedInUser->role === 'Pemilik')
        {
            $dataTransaksi = TransaksiModel::with([
                'pemesanan:id_pemesanan,id_beras,id_produsen',
                'pemesanan.produsen:id_produsen,nama_produsen',
                'pemesanan.beras:id_beras,nama_beras',
                'pemesanan.detail'
            ])->get();

            // pisahkan detail berdasarkan berat
            foreach ($dataTransaksi as $item) {
                $detailMap = $item->pemesanan->detail->keyBy('berat');

                $item->stok10kg = $detailMap->get(10); // bisa null kalau tidak ada
                $item->stok20kg = $detailMap->get(20);
                $item->stok50kg = $detailMap->get(50);
            }

            return Inertia::render('Pemilik/Transaksi/Index', [
                'dataTransaksi' => $dataTransaksi,
            ]);
        }
        else if($loggedInUser->role === 'Produsen')
        {
            $produsen = ProdusenModel::where('user_id', $loggedInUser->user_id)->first();

            $idProdusen = $produsen->id_produsen;

            $dataTransaksi = TransaksiModel::with([
                'pemesanan:id_pemesanan,id_beras,id_produsen',
                'pemesanan.produsen:id_produsen,nama_produsen',
                'pemesanan.beras:id_beras,nama_beras',
                'pemesanan.detail'
            ])->whereHas('pemesanan', function ($query) use ($idProdusen) {
                $query->where('id_produsen', $idProdusen);
            })->get();

            $daftarNoRek = RekeningModel::where('id_produsen', $loggedInUser->user_id)->select('id_rekening', 'id_produsen', 'no_rekening', 'nama_rekening')->get();

            // pisahkan detail berdasarkan berat
            foreach ($dataTransaksi as $item) {
                $detailMap = $item->pemesanan->detail->keyBy('berat');

                $item->stok10kg = $detailMap->get(10); // bisa null kalau tidak ada
                $item->stok20kg = $detailMap->get(20);
                $item->stok50kg = $detailMap->get(50);
            }

            return Inertia::render('Produsen/Transaksi/Index', [
                'dataTransaksi' => $dataTransaksi,
                'daftarNoRek' => $daftarNoRek,
            ]);
        }
    }

    public function uploadBukti(Request $req)
    {
        $req->validate([
            'id_transaksi' => 'required',
            'rekening' => 'required',
            'bukti_bayar' => 'required',
        ]);

        $today = Carbon::now()->format('d-m-Y');

        $filePathFix = $this->file_path.$today.'/';

        $imageValidation = new ImageValidation();

        $fileNameFix = $req->id_transaksi.'-'.$today;

        $linkFile = $imageValidation->validateImage($req, 'bukti_bayar', $filePathFix, $fileNameFix);

        if($linkFile)
        {

            $transaksi = transaksiModel::findOrFail($req->id_transaksi);
            $transaksi->rekening = $req->rekening;
            $transaksi->tgl_transaksi = Carbon::now()->format('Y-m-d');
            $transaksi->bukti_bayar = $linkFile;

            $update = $transaksi->save();

            if ($update)
            {
                return redirect()->back()->with([
                    'notif_status' => 'success',
                    'notif_message' => 'Berhasil upload bukti bayar!',
                ]);
            } else {
                return redirect()->back()->with([
                    'notif_status' => 'error',
                    'notif_message' => 'Gagal upload bukti bayar :(',
                ]);
            }
        }



    }

    public function konfirmJadwal(Request $req)
    {
         // Format ulang tanggal dari ISO menjadi Y-m-d
        $req->merge([
            'tgl_pengiriman' => Carbon::parse($req->tgl_produksi)->timezone('Asia/Jayapura')->format('Y-m-d'),
        ]);

        $validated = $req->validate([
            'id_transaksi' => 'required',
            'tgl_pengiriman' => 'required|date',
            'status_pembayaran' => 'required|string',
        ]);

        $transaksi = TransaksiModel::findOrFail($req->id_transaksi);

        $transaksi->tgl_pengiriman = $validated['tgl_pengiriman'];
        $transaksi->status_pembayaran = $validated['status_pembayaran'];
        $transaksi->status_pengiriman = 'Dijadwalkan';

        $update = $transaksi->save();

        if ($update) {
            return redirect()->back()->with([
                'notif_status' => 'success',
                'notif_message' => 'Berhasil menjadwalkan pengiriman!',
            ]);
        } else {
            return redirect()->back()->with([
                'notif_status' => 'error',
                'notif_message' => 'Gagal menjadwalkan pengiriman :(',
            ]);
        }
    }

    public function konfirmasiSelesai(Request $req)
    {
         $validated = $req->validate([
            'id_transaksi' => 'required',
            'status_pengiriman' => 'required|string',
            'catatan' => 'string',
        ]);

        $transaksi = TransaksiModel::findOrFail($req->id_transaksi);

        $update = $transaksi->update($validated);

        if ($update) {
            return redirect()->back()->with([
                'notif_status' => 'success',
                'notif_message' => 'Transaksi Selesai!',
            ]);
        } else {
            return redirect()->back()->with([
                'notif_status' => 'error',
                'notif_message' => 'Gagal konfirmasi transaksi :(',
            ]);
        }
    }
}
