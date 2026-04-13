<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $table = 'absensi';

    protected $fillable = [
        'siswa_id',
        'tanggal',
        'jam_masuk',
        'jam_pulang',
        'foto_masuk',
        'foto_pulang',
        'status',
        'keterangan',
        'sumber',
        'terlambat',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
