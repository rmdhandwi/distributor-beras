<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PemesananModel extends Model
{
    protected $table = 'tb_pemesanan';
    protected $primaryKey = 'id_pemesanan';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_produsen',
        'id_beras',
        'jmlh',
        'tgl_pemesanan',
        'status_pesanan',
        'catatan',
    ];

    protected $appends = ['editable', 'confirmed'];

    public function getEditableAttribute()
    {
        return !TransaksiModel::where('id_pemesanan', $this->id_pemesanan)->exists();
    }

    public function getConfirmedAttribute()
    {
        $transaksi = TransaksiModel::where('id_pemesanan', $this->id_pemesanan)->first();

        return $transaksi && $transaksi->bukti_bayar === NULL;
    }

    protected $casts = [
        'tgl_pemesanan' => 'string',
    ];

    // Relasi
    public function produsen()
    {
        return $this->belongsTo(ProdusenModel::class, 'id_produsen');
    }

    public function beras()
    {
        return $this->belongsTo(BerasModel::class, 'id_beras');
    }

    public function detail()
    {
        return $this->hasMany(DetailPemesananModel::class, 'id_pemesanan');
    }

    public function transaksi()
    {
        return $this->hasMany(TransaksiModel::class, 'id_pemesanan');
    }

}
