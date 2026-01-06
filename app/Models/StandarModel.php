<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimModel extends Model
{
    protected $table = 't_standar';

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'kelompok_id',
        'nama',
        'urutan'
    ];

    public $timestamps = true;
}
