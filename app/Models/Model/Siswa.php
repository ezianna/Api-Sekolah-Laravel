<?php

namespace App\Models\Model;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'siswa';

    protected $fillable = [
        'nis','gender','nama','tempat_lahir',
        'tgl_lahir','nama_ortu','phone_number',
        'email','alamat','kelas_id'
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}