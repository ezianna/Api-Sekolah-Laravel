<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $table = 'guru';

    protected $fillable = [
        'user_id',
        'nip',
        'nama',
        'tempat_lahir',
        'tgl_lahir',
        'gender',
        'phone_number',
        'email',
        'alamat',
        'pendidikan'
    ];
}