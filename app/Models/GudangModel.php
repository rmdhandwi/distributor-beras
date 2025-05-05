<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GudangModel extends Model
{
    protected $table = 'tb_gudang'; // Nama tabel
    protected $primaryKey = 'id_gudang'; // Primary key
    public $incrementing = true;
    protected $keyType = 'int';

    // Mass assignment (boleh diisi massal)
    protected $fillable = [
        'id_beras',
        'id_produsen',
        'stok_awal',
        'rusak',
        'hilang',
        'stok_sisa',
    ];

    // Casting (optional)
    protected $casts = [
        'stok_awal' => 'integer',
        'rusak' => 'integer',
        'hilang' => 'integer',
        'stok_sisa' => 'integer',
    ];

    /**
     * Relasi ke model Beras
     */
    public function beras()
    {
        return $this->belongsTo(BerasModel::class, 'id_beras');
    }

    /**
     * Relasi ke model Produsen
     */
    public function produsen()
    {
        return $this->belongsTo(ProdusenModel::class, 'id_produsen');
    }
}
