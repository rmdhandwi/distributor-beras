<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailGudangModel extends Model
{
    //
    protected $table = 'tb_detail_gudang';
    protected $primaryKey = 'id_detail_gudang';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_detail_gudang',
        'id_gudang',
        'berat',
        'stok_awal',
        'rusak',
        'hilang',
        'stok_sisa',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // relasi ke model gudang
    public function gudang()
    {
        return $this->belongsTo(GudangModel::class, 'id_gudang');
    }
}
