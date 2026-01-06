<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimModel extends Model
{
    protected $table = 'm_tahun';

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'tahun'
    ];

    public $timestamps = true;
}
