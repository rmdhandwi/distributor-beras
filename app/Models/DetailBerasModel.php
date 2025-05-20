<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailBerasModel extends Model
{
    //
    protected $table = 'tb_detail_beras';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id',
        'id_beras',
        'berat',
        'jumlah',
        'harga',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
