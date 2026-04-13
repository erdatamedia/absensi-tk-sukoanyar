<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Siswa extends Model
{
    protected $table = 'siswa';

    protected $fillable = [
        'nis',
        'nama',
        'kelas_id',
        'jenis_kelamin',
        'tanggal_lahir',
        'qr_token',
        'foto_referensi'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($siswa) {
            $siswa->qr_token = Str::uuid();
        });
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }

    public function relasiOrangTua()
    {
        return $this->hasMany(OrangTuaSiswa::class);
    }
}
