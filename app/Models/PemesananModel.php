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

    protected $appends = ['editable'];

    public function getEditableAttribute()
    {
        return !TransaksiModel::where('id_pemesanan', $this->id_pemesanan)->exists();
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
}
