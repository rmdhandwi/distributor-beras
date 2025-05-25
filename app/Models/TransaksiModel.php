<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiModel extends Model
{
    //
    protected $table = 'tb_transaksi';
    protected $primaryKey = 'id_transaksi';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_pemesanan',
        'tgl_transaksi',
        'jmlh',
        'total_bayar',
        'bukti_bayar',
        'status_pembayaran',
        'status_pengiriman',
        'tgl_pengiriman',
        'catatan',
    ];

    protected $casts = [
        'tgl_transaksi' => 'string',
        'tgl_pengiriman' => 'string',
    ];

    // relasi ke pemesanan
    public function pemesanan()
    {
        return $this->belongsTo(PemesananModel::class, 'id_pemesanan');
    }
}
