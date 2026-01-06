<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimModel extends Model
{
    protected $table = 't_standar_indikator_kinerja';

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'standar_id',
        'indikator',
        'urutan'
    ];

    public $timestamps = true;
}
