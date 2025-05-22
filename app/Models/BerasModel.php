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
        'stok_awal',
        'stok_tersedia',
        'tgl_produksi',
        'kualitas_beras',
        'sertifikat_beras',
        'status_beras',
    ];

    protected $casts = [
        'stok_awal' => 'integer',
        'stok_tersedia' => 'integer',
        'tgl_produksi' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // isi status secara otomatis
    protected static function booted(): void
    {
        static::creating(function ($model) {
            $model->status_beras = $model->stok_tersedia > 0 ? 'Tersedia' : 'Kosong';
        });

        static::updating(function ($model) {
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

    // Relasi ke model pemesanan
    public function pemesanan()
    {
        return $this->hasMany(PemesananModel::class, 'id_beras');
    }

    // Relasi ke model gudang
    public function gudang()
    {
        return $this->hasMany(GudangModel::class, 'id_beras');
    }

    // Relasi ke model detail_beras
    public function detail()
    {
        return $this->hasMany(DetailBerasModel::class, 'id_beras');
    }
}
