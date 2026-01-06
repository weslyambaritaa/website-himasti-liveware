<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimModel extends Model
{
    protected $table = 't_standar_kelompok';

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'tahun_id',
        'nama',
        'urutan'
    ];

    public $timestamps = true;
}
