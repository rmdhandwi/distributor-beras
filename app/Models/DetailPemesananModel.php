<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPemesananModel extends Model
{
    //
    protected $table = 'tb_detail_pemesanan';
    protected $primaryKey = 'id_detail_pemesanan';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_detail_pemesanan',
        'id_pemesanan',
        'berat',
        'jumlah',
        'harga_satuan',
        'total_harga',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // relasi ke model pemesanan
    public function pemesanan()
    {
        return $this->belongsTo(PemesananModel::class, 'id_pemesanan');
    }
}
