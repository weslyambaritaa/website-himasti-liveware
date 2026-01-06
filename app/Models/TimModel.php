<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimModel extends Model
{
    protected $table = 'm_tim';

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'posisi',  // Ketua, Anggota
        'tanggal_bergabung',
        'is_aktif',
    ];

    public $timestamps = true;
}
