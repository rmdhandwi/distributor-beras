<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BerasModel extends Model
{
    protected $table = 'tb_beras';
    protected $primaryKey = 'id_beras';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nama_beras',
        'id_produsen',
        'jenis_beras',
        'harga_jual',
        'stok_awal',
        'stok_tersedia',
        'tgl_produksi',
        'tgl_kadaluarsa',
        'kualitas_beras',
        'sertifikat_beras',
        'status_beras',
    ];

    protected $casts = [
        'harga_jual' => 'integer',
        'stok_awal' => 'integer',
        'stok_tersedia' => 'integer',
        'tgl_produksi' => 'string',
        'tgl_kadaluarsa' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // isi status secara otomatis
    protected static function booted(): void
    {
        static::creating(function ($model) {
            $model->status_beras = $model->stok_tersedia > 0 ? 'Tersedia' : 'Kosong';
        });
    }

     /**
     * Relasi ke model Produsen (satu beras dimiliki oleh satu produsen)
     */
    public function produsen()
    {
        return $this->belongsTo(ProdusenModel::class, 'id_produsen');
    }
}
