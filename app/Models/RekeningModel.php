<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekeningModel extends Model
{
    protected $table = 'tb_rekening';
    protected $primaryKey = 'id_rekening';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_rekening',
        'id_produsen',
        'no_rekening',
        'nama_rekening',
    ];

    public function transaksi()
    {
        return $this->hasMany(TransaksiModel::class, 'rekening', 'id_rekening');
    }

    public function produsen()
    {
        return $this->belongsTo(User::class, 'id_produsen', 'user_id');
    }
}
