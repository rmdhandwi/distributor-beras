<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ProdusenModel extends Model
{
    protected $table = 'tb_produsen';
    protected $primaryKey = 'id_produsen';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'user_id',
        'nama_produsen',
        'alamat',
        'no_telp',
        'email',
        'tgl_pendaftaran',
        'status'
    ];

    protected $casts = [
        'tgl_pendaftaran' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // isi status dan tanggal_pendaftaran secara otomatis
    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->tgl_pendaftaran)) {
                $model->tgl_pendaftaran = Carbon::now();
            }
        });
    }

    // relasi produsen ke beras
    public function daftarBeras()
    {
        return $this->hasMany(BerasModel::class, 'id_produsen');
    }

    // relasi produsen ke gudang
    public function gudang()
    {
        return $this->belongsTo(GudangModel::class, 'id_produsen');
    }

    // relasi produsen ke pemesanan
    public function daftarPemesanan()
    {
        return $this->hasMany(PemesananModel::class, 'id_produsen');
    }
}
