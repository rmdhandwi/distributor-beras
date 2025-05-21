<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailBerasModel extends Model
{
    //
    protected $table = 'tb_detail_beras';
    protected $primaryKey = 'id_detail';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_detail',
        'id_beras',
        'berat',
        'jumlah',
        'harga',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // relasi ke model beras
    public function beras()
    {
        return $this->belongsTo(BerasModel::class, 'id_beras');
    }
}
