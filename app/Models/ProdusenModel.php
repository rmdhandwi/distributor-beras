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
        'nama_produsen',
        'alamat',
        'no_telp',
        'email',
        'jenis_beras',
        'harga_beras',
        'jml_stok',
        'status_stok',
        'tgl_pendaftaran',
    ];

    protected $casts = [
        'harga_beras' => 'integer',
        'jml_stok' => 'integer',
        'tgl_pendaftaran' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // isi status dan tanggal_pendaftaran secara otomatis
    protected static function booted(): void
    {
        static::creating(function ($model) {
            $model->status_stok = $model->jml_stok > 0 ? 'Tersedia' : 'Kosong';
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
}
